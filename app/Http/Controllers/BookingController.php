<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Requests\StoreBookingEventRequest;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Requests\ProcessBalancePaymentRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Models\MenuItem;
use App\Models\CatererAvailability;
use App\Services\NotificationService;
use App\Traits\DatabaseLock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    use DatabaseLock, HandlesImageUploads;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show booking form (Step 1: Event Details)
     */
    public function create($catererId, $packageId)
    {
        $package = Package::where('id', $packageId)
            ->where('user_id', $catererId)
            ->where('status', 'active')
            ->with(['items.category', 'user'])
            ->firstOrFail();

        $customization = session('booking_customization', [
            'selected_items' => $package->items->pluck('id')->toArray(),
            'price_per_head' => $package->price,
            'guests'         => $package->pax,
        ]);

        return view('customer.booking.create', compact('package', 'customization'));
    }

    /**
     * Store event details and move to payment (Step 2)
     */
    public function storeEventDetails(StoreBookingEventRequest $request)
    {
        $validated = $request->validated();

        $eventDate = \Carbon\Carbon::parse($request->event_date);
        if ($eventDate->lt(\Carbon\Carbon::tomorrow())) {
            return back()->withInput()
                ->with('error', 'Event date must be at least 1 day in advance.');
        }

        $isBlocked = CatererAvailability::where('caterer_id', $request->caterer_id)
            ->where('date', $request->event_date)
            ->where('status', 'blocked')
            ->exists();

        if ($isBlocked) {
            return back()->withInput()
                ->with('error', 'Sorry, this caterer is not available on the selected date.');
        }

        $hasExistingBooking = Booking::where('caterer_id', $request->caterer_id)
            ->where('event_date', $request->event_date)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->exists();

        if ($hasExistingBooking) {
            return back()->withInput()
                ->with('error', 'Sorry, this caterer is already booked for the selected date.');
        }

        // Ensure the selected package really belongs to the caterer and is active
        $package = Package::where('id', $validated['package_id'] ?? null)
            ->where('user_id', $validated['caterer_id'] ?? null)
            ->where('status', 'active')
            ->first();

        if (! $package) {
            return back()->withInput()
                ->with('error', 'Invalid package / caterer selection. Please try again.');
        }

        // Ensure selected menu items belong to this caterer
        $validItemIds = MenuItem::where('user_id', $package->user_id)
            ->whereIn('id', $validated['selected_items'] ?? [])
            ->pluck('id')
            ->all();

        if (empty($validItemIds)) {
            return back()->withInput()
                ->with('error', 'Please select at least one valid menu item.');
        }

        // Recompute pricing server-side from authoritative data
        $guests       = (int) ($validated['guests'] ?? 0);
        $pricePerHead = (float) $package->price;
        $totalPrice   = $pricePerHead * max($guests, 0);

        $bookingDetails = [
            'package_id'           => $package->id,
            'caterer_id'           => $package->user_id,
            'event_type'           => $validated['event_type'],
            'event_date'           => $validated['event_date'],
            'time_slot'            => $validated['time_slot'],
            'guests'               => $guests,
            'venue_name'           => $validated['venue_name'],
            'venue_address'        => $validated['venue_address'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'selected_items'       => $validItemIds,
            'price_per_head'       => $pricePerHead,
            'total_price'          => $totalPrice,
        ];

        session(['booking_details' => $bookingDetails]);

        return redirect()->route('customer.booking.payment');
    }

    /**
     * Show payment page (Step 2)
     */
    public function payment()
    {
        $bookingDetails = session('booking_details');

        if (!$bookingDetails) {
            return redirect()->route('customer.caterers')->with('error', 'No booking details found.');
        }

        $package       = Package::with('user')->findOrFail($bookingDetails['package_id']);
        $selectedItems = MenuItem::whereIn('id', $bookingDetails['selected_items'])->get();
        $deposit       = $bookingDetails['total_price'] * 0.25;
        $serviceFee    = 500;
        $depositDue    = $deposit + $serviceFee;

        return view('customer.booking.payment', compact(
            'bookingDetails', 'package', 'selectedItems', 'deposit', 'serviceFee', 'depositDue'
        ));
    }

    /**
     * Process payment and create booking (Step 3)
     */
    public function processPayment(ProcessPaymentRequest $request)
    {
        $bookingDetails = session('booking_details');

        if (!$bookingDetails) {
            return redirect()->route('customer.caterers')->with('error', 'Booking session expired.');
        }

        $lockKey = null;

        try {
            DB::beginTransaction();

            // Acquire an advisory lock per caterer+date to prevent double-bookings
            // under concurrent requests (race condition fix).
            $lockKey = 'booking_' . $bookingDetails['caterer_id'] . '_' . $bookingDetails['event_date'];
            
            // Use database-agnostic lock (works with both MySQL and PostgreSQL)
            $lockAcquired = $this->acquireLock($lockKey, 5);
            
            if (!$lockAcquired) {
                DB::rollBack();
                return back()->with('error', 'Unable to process booking at this time. Please try again.');
            }

            // Re-check availability inside the lock
            $isBlocked = CatererAvailability::where('caterer_id', $bookingDetails['caterer_id'])
                ->where('date', $bookingDetails['event_date'])
                ->where('status', 'blocked')
                ->exists();

            if ($isBlocked) {
                $this->releaseLock($lockKey);
                DB::rollBack();
                session()->forget(['booking_details', 'booking_customization']);
                return redirect()->route('customer.caterers')
                    ->with('error', 'Sorry, this date is no longer available.');
            }

            $hasExistingBooking = Booking::where('caterer_id', $bookingDetails['caterer_id'])
                ->where('event_date', $bookingDetails['event_date'])
                ->whereIn('booking_status', ['pending', 'confirmed'])
                ->exists();

            if ($hasExistingBooking) {
                $this->releaseLock($lockKey);
                DB::rollBack();
                session()->forget(['booking_details', 'booking_customization']);
                return redirect()->route('customer.caterers')
                    ->with('error', 'Sorry, this date is no longer available.');
            }

            // Re-fetch package to ensure it is still valid and belongs to the same caterer
            $package = Package::where('id', $bookingDetails['package_id'] ?? null)
                ->where('user_id', $bookingDetails['caterer_id'] ?? null)
                ->where('status', 'active')
                ->firstOrFail();

            // Recompute financials from authoritative data (ignore any tampered session totals)
            $guests      = (int) ($bookingDetails['guests'] ?? 0);
            $totalPrice  = (float) $package->price * max($guests, 0);
            $deposit     = $totalPrice * 0.25;
            $serviceFee  = 500;
            $depositPaid = $deposit + $serviceFee;
            $balance     = $totalPrice - $deposit;

            // Uses Cloudinary when configured (production), falls back to local public disk (local dev)
            $receiptPath = $this->handleImageUpload($request->file('receipt'), 'receipts');

            $booking = Booking::create([
                'customer_id'         => auth()->id(),
                'caterer_id'          => $package->user_id,
                'package_id'          => $package->id,
                'booking_number'      => 'BK-' . strtoupper(uniqid()),
                'event_type'          => $bookingDetails['event_type'],
                'event_date'          => $bookingDetails['event_date'],
                'time_slot'           => $bookingDetails['time_slot'],
                'guests'              => $bookingDetails['guests'],
                'venue_name'          => $bookingDetails['venue_name'],
                'venue_address'       => $bookingDetails['venue_address'],
                'special_instructions'=> $bookingDetails['special_instructions'] ?? null,
                'price_per_head'      => $bookingDetails['price_per_head'],
                'total_price'         => $totalPrice,
                'deposit_amount'      => $deposit,
                'service_fee'         => $serviceFee,
                'deposit_paid'        => $depositPaid,
                'balance'             => $balance,
                'customer_name'       => $request->full_name,
                'customer_email'      => $request->email,
                'customer_phone'      => $request->phone,
                'payment_method'      => $request->payment_method,
                'receipt_path'        => $receiptPath,
                'payment_status'      => 'deposit_paid',
                'booking_status'      => 'pending',
            ]);

            $booking->menuItems()->attach($bookingDetails['selected_items']);

            try {
                $this->notificationService->notifyBookingCreated($booking);
                $this->notificationService->notifyCatererNewBooking($booking);
                $this->notificationService->notifyPaymentReceived($booking, 'deposit');
            } catch (\Exception $e) {
                Log::error('Notification failed after booking creation', [
                    'booking_id' => $booking->id, 'error' => $e->getMessage(),
                ]);
            }

            session()->forget(['booking_details', 'booking_customization']);
            DB::commit();
            $this->releaseLock($lockKey);

            return redirect()->route('customer.booking.confirmation', $booking->id)
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($lockKey) {
                $this->releaseLock($lockKey);
            }
            Log::error('Booking creation failed', [
                'user_id' => auth()->id(), 'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to create booking. Please try again.');
        }
    }

    /**
     * Show booking confirmation (Step 3)
     */
    public function confirmation($bookingId)
    {
        $booking = Booking::with(['package', 'caterer', 'menuItems.category'])
            ->where('customer_id', auth()->id())
            ->findOrFail($bookingId);

        return view('customer.booking.confirmation', compact('booking'));
    }

    /**
     * Abandon booking flow (clears session)
     */
    public function cancel()
    {
        session()->forget(['booking_details', 'booking_customization']);
        return redirect()->route('customer.caterers');
    }

    /**
     * Show booking details page
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['package', 'caterer', 'menuItems.category'])
            ->where('customer_id', auth()->id())
            ->findOrFail($bookingId);

        return view('customer.booking.details', compact('booking'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  CANCELLATION LOGIC
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Customer cancels their own booking.
     *
     * Rule: only allowed while status = 'pending' (not yet confirmed).
     * If a deposit was paid the customer must provide their GCash / bank details
     * so the caterer can contact them to send the refund manually.
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $booking = Booking::where('customer_id', auth()->id())
            ->where('id', $bookingId)
            ->firstOrFail();

        // ── Gate: only pending bookings ───────────────────────────────────────
        if (!$booking->canBeCancelledByCustomer()) {
            return back()->with('error',
                'This booking can no longer be cancelled. ' .
                'Once a caterer has confirmed your booking, cancellation is not allowed. ' .
                'Please contact the caterer directly.'
            );
        }

        $rules = [
            'cancellation_reason' => 'required|string|min:10|max:1000',
            'refund_details'      => 'nullable|string|max:500',
        ];

        if ($booking->hasRefundablePayment()) {
            $rules['refund_details'] = 'required|string|max:500';
        }

        $request->validate($rules, [
            'cancellation_reason.required' => 'Please provide a reason for cancelling.',
            'cancellation_reason.min'      => 'Please give a bit more detail (at least 10 characters).',
            'refund_details.required'      => 'Please provide your GCash / bank details so the refund can be processed.',
        ]);

        // Refund is only "pending" when refundable principal exists.
        // (Use refundable basis, not deposit_paid which includes service fee.)
        $refundStatus = $booking->hasRefundablePayment() ? 'pending' : 'none';

        $booking->update([
            'booking_status'      => 'cancelled',
            'cancelled_by'        => 'customer',
            'cancellation_reason' => $request->cancellation_reason,
            'refund_status'       => $refundStatus,
            'refund_details'      => $request->refund_details, // GCash / bank details
            'cancelled_at'        => now(),
        ]);

        try {
            $this->notificationService->notifyBookingCancelledByCustomer($booking);
        } catch (\Exception $e) {
            Log::error('Cancel notification failed', [
                'booking_id' => $booking->id, 'error' => $e->getMessage(),
            ]);
        }

        $successMsg = 'Your booking has been cancelled.';
        if ($refundStatus === 'pending') {
            $successMsg .= ' The caterer will contact you to arrange your refund using the details you provided.';
        }

        return redirect()->route('customer.bookings')->with('success', $successMsg);
    }

    /**
     * Caterer cancels a booking they can no longer fulfil.
     *
     * Rule: caterer must provide a reason.
     * If a deposit was paid, refund_status → 'pending'.
     * The caterer must contact the customer (email/phone shown in booking)
     * to collect their GCash / bank details and send the money back manually.
     */
    public function cancelByCaterer(Request $request, $bookingId)
    {
        $booking = Booking::where('caterer_id', auth()->id())
            ->where('id', $bookingId)
            ->firstOrFail();

        if (!$booking->canBeCancelledByCaterer()) {
            return back()->with('error',
                'This booking cannot be cancelled. It may already be cancelled, completed, or the event date has passed.'
            );
        }

        $rules = [
            'cancellation_reason' => 'required|string|min:10|max:1000',
            'refund_details'      => 'nullable|string|max:500',
        ];

        if ($booking->hasRefundablePayment()) {
            // Require payout details when caterer cancels a refundable booking.
            $rules['refund_details'] = 'required|string|max:500';
        }

        $request->validate($rules, [
            'cancellation_reason.required' => 'Please provide a reason for cancelling this booking.',
            'cancellation_reason.min'      => 'Please give a bit more detail (at least 10 characters).',
            'refund_details.required'      => 'Please provide customer payout details to process the refund.',
        ]);

        $refundStatus = $booking->hasRefundablePayment() ? 'pending' : 'none';

        $booking->update([
            'booking_status'      => 'cancelled',
            'cancelled_by'        => 'caterer',
            'cancellation_reason' => $request->cancellation_reason,
            'refund_status'       => $refundStatus,
            'refund_details'      => $request->refund_details,
            'cancelled_at'        => now(),
        ]);

        try {
            $this->notificationService->notifyBookingCancelledByCaterer($booking);
        } catch (\Exception $e) {
            Log::error('Caterer cancel notification failed', [
                'booking_id' => $booking->id, 'error' => $e->getMessage(),
            ]);
        }

        $successMsg = 'Booking has been cancelled.';
        if ($refundStatus === 'pending') {
            $successMsg .= ' A deposit was paid — please contact the customer via email or phone to get their GCash / bank details and arrange the refund.';
        }

        return redirect()->route('caterer.booking.details', $booking->id)
            ->with('success', $successMsg);
    }

    /**
     * Caterer marks refund as issued (money already sent back manually).
     */
    public function markRefundIssued(Request $request, $bookingId)
    {
        $booking = Booking::where('caterer_id', auth()->id())
            ->where('id', $bookingId)
            ->where('booking_status', 'cancelled')
            ->where('refund_status', 'pending')
            ->firstOrFail();

        if (! $booking->hasRefundablePayment()) {
            return back()->with('error', 'This booking has no refundable amount.');
        }

        $booking->update([
            'refund_status'  => 'issued',
            'payment_status' => 'refunded',
        ]);

        return back()->with('success', 'Refund marked as issued.');
    }

    /**
     * Caterer marks refund as waived (customer agreed — no money to return).
     */
    public function markRefundWaived(Request $request, $bookingId)
    {
        $booking = Booking::where('caterer_id', auth()->id())
            ->where('id', $bookingId)
            ->where('booking_status', 'cancelled')
            ->where('refund_status', 'pending')
            ->firstOrFail();

        $request->validate(['waiver_note' => 'nullable|string|max:500']);

        $note = $booking->refund_details;
        if ($request->waiver_note) {
            $note .= "\n\nWaiver note: " . $request->waiver_note;
        }

        $booking->update([
            'refund_status'  => 'waived',
            'refund_details' => $note,
        ]);

        return back()->with('success', 'Refund marked as waived.');
    }

    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Show pay balance page
     */
    public function payBalance($bookingId)
    {
        $booking = Booking::with(['package', 'caterer'])
            ->where('customer_id', auth()->id())
            ->where('payment_status', 'deposit_paid')
            ->findOrFail($bookingId);

        return view('customer.booking.pay-balance', compact('booking'));
    }

    /**
     * Process balance payment
     */
    public function processBalancePayment(ProcessBalancePaymentRequest $request, $bookingId)
    {
        $booking = Booking::where('customer_id', auth()->id())
            ->where('payment_status', 'deposit_paid')
            ->findOrFail($bookingId);

        try {
            // Uses Cloudinary when configured (production), falls back to local public disk (local dev)
            $receiptPath = $this->handleImageUpload($request->file('receipt'), 'receipts/balance');

            $booking->update([
                'payment_status'       => 'fully_paid',
                'balance_receipt_path' => $receiptPath,   // separate column — deposit receipt preserved
            ]);

            try {
                $this->notificationService->notifyPaymentReceived($booking, 'balance');
            } catch (\Exception $e) {
                Log::error('Balance payment notification failed', [
                    'booking_id' => $booking->id, 'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('customer.booking.details', $booking->id)
                ->with('success', 'Balance payment submitted! Your booking is now fully paid.');

        } catch (\Exception $e) {
            Log::error('Balance payment failed', [
                'booking_id' => $bookingId, 'user_id' => auth()->id(), 'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * AJAX availability check
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'caterer_id' => 'required|exists:users,id',
            'event_date' => 'required|date|after:today',
        ]);

        $isBlocked = CatererAvailability::where('caterer_id', $request->caterer_id)
            ->where('date', $request->event_date)
            ->where('status', 'blocked')
            ->exists();

        if ($isBlocked) {
            return response()->json(['available' => false, 'reason' => 'blocked', 'message' => 'This caterer is not available on this date.']);
        }

        $hasBooking = Booking::where('caterer_id', $request->caterer_id)
            ->where('event_date', $request->event_date)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->exists();

        if ($hasBooking) {
            return response()->json(['available' => false, 'reason' => 'booked', 'message' => 'This caterer is already booked for this date.']);
        }

        return response()->json(['available' => true, 'message' => 'This date is available!']);
    }
}
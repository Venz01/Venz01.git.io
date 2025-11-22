<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Package;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
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

        // Get customization from session if exists
        $customization = session('booking_customization', [
            'selected_items' => $package->items->pluck('id')->toArray(),
            'price_per_head' => $package->price,
            'guests' => $package->pax
        ]);

        return view('customer.booking.create', compact('package', 'customization'));
    }

    /**
     * Store event details and move to payment (Step 2)
     */
    public function storeEventDetails(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'caterer_id' => 'required|exists:users,id',
            'event_type' => 'required|string',
            'event_date' => 'required|date|after:today',
            'time_slot' => 'required|string',
            'guests' => 'required|integer|min:1',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:menu_items,id',
            'price_per_head' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ], [
            'event_date.after' => 'Event date must be at least 1 day in advance. Please select a future date.',
        ]);

        // Additional date validation
        $eventDate = \Carbon\Carbon::parse($request->event_date);
        $tomorrow = \Carbon\Carbon::tomorrow();
        
        if ($eventDate->lt($tomorrow)) {
            return back()->withInput()
                ->with('error', 'Event date must be at least 1 day in advance. Please select a future date.');
        }

        // Store booking details in session
        session([
            'booking_details' => $request->all()
        ]);

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

        $package = Package::with('user')->findOrFail($bookingDetails['package_id']);
        $selectedItems = MenuItem::whereIn('id', $bookingDetails['selected_items'])->get();

        // Calculate deposit (25% of total)
        $deposit = $bookingDetails['total_price'] * 0.25;
        $serviceFee = 500; // Fixed service fee
        $depositDue = $deposit + $serviceFee;

        return view('customer.booking.payment', compact('bookingDetails', 'package', 'selectedItems', 'deposit', 'serviceFee', 'depositDue'));
    }

    /**
     * Process payment and create booking (Step 3)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'receipt' => 'required|image|mimes:jpg,jpeg,png,gif,pdf|max:10240',
        ]);

        $bookingDetails = session('booking_details');
        
        if (!$bookingDetails) {
            return redirect()->route('customer.caterers')->with('error', 'Booking session expired.');
        }

        try {
            DB::beginTransaction();

            // Handle receipt upload
            $receiptPath = $request->file('receipt')->store('receipts', 'public');

            // Calculate amounts
            $totalPrice = $bookingDetails['total_price'];
            $deposit = $totalPrice * 0.25;
            $serviceFee = 500;
            $depositPaid = $deposit + $serviceFee;
            $balance = $totalPrice - $deposit;

            // Create booking
            $booking = Booking::create([
                'customer_id' => auth()->id(),
                'caterer_id' => $bookingDetails['caterer_id'],
                'package_id' => $bookingDetails['package_id'],
                'booking_number' => 'BK-' . strtoupper(uniqid()),
                'event_type' => $bookingDetails['event_type'],
                'event_date' => $bookingDetails['event_date'],
                'time_slot' => $bookingDetails['time_slot'],
                'guests' => $bookingDetails['guests'],
                'venue_name' => $bookingDetails['venue_name'],
                'venue_address' => $bookingDetails['venue_address'],
                'special_instructions' => $bookingDetails['special_instructions'] ?? null,
                'price_per_head' => $bookingDetails['price_per_head'],
                'total_price' => $totalPrice,
                'deposit_amount' => $deposit,
                'service_fee' => $serviceFee,
                'deposit_paid' => $depositPaid,
                'balance' => $balance,
                'customer_name' => $request->full_name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'receipt_path' => $receiptPath,
                'payment_status' => 'deposit_paid',
                'booking_status' => 'pending',
            ]);

            // Attach selected menu items
            $booking->menuItems()->attach($bookingDetails['selected_items']);

            // Clear session
            session()->forget(['booking_details', 'booking_customization']);

            DB::commit();

            return redirect()->route('customer.booking.confirmation', $booking->id)
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage());
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
     * Cancel booking
     */
    public function cancel()
    {
        session()->forget(['booking_details', 'booking_customization']);
        return redirect()->route('customer.caterers');
    }

    /**
     * Show booking details
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['package', 'caterer', 'menuItems.category'])
            ->where('customer_id', auth()->id())
            ->findOrFail($bookingId);

        return view('customer.booking.details', compact('booking'));
    }

    /**
     * Cancel a confirmed booking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $booking = Booking::where('customer_id', auth()->id())
            ->where('id', $bookingId)
            ->firstOrFail();

        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update([
            'booking_status' => 'cancelled',
            'special_instructions' => $booking->special_instructions . "\n\nCancellation Reason: " . ($request->cancellation_reason ?? 'No reason provided')
        ]);

        return redirect()->route('customer.bookings')
            ->with('success', 'Booking has been cancelled successfully.');
    }

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
    public function processBalancePayment(Request $request, $bookingId)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpg,jpeg,png,gif,pdf|max:10240',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
        ]);

        $booking = Booking::where('customer_id', auth()->id())
            ->where('payment_status', 'deposit_paid')
            ->findOrFail($bookingId);

        try {
            // Handle receipt upload
            $receiptPath = $request->file('receipt')->store('receipts/balance', 'public');

            $booking->update([
                'payment_status' => 'fully_paid',
                'receipt_path' => $receiptPath, // Update with balance payment receipt
            ]);

            return redirect()->route('customer.booking.details', $booking->id)
                ->with('success', 'Balance payment submitted successfully! Your booking is now fully paid.');

        } catch (\Exception $e) {
            \Log::error('Balance payment failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process payment. Please try again.');
        }
    }
}
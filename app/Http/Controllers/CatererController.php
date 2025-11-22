<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;

class CatererController extends Controller
{
    public function dashboard()
    {
        return view('caterer.dashboard');
    }

    public function bookings(Request $request)
    {
        $query = \App\Models\Booking::where('caterer_id', auth()->id())
            ->with(['customer', 'package', 'menuItems'])
            ->orderBy('created_at', 'desc');

        // Apply tab filter
        $tab = $request->get('tab', 'pending');
        if ($tab !== 'all') {
            $query->where('booking_status', $tab);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('booking_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('customer_email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Apply date filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        $bookings = $query->paginate(10);

        // Get statistics
        $stats = [
            'pending' => \App\Models\Booking::where('caterer_id', auth()->id())->where('booking_status', 'pending')->count(),
            'confirmed' => \App\Models\Booking::where('caterer_id', auth()->id())->where('booking_status', 'confirmed')->count(),
            'completed' => \App\Models\Booking::where('caterer_id', auth()->id())->where('booking_status', 'completed')->whereMonth('event_date', now()->month)->count(),
            'cancelled' => \App\Models\Booking::where('caterer_id', auth()->id())->where('booking_status', 'cancelled')->count(),
            'revenue' => \App\Models\Booking::where('caterer_id', auth()->id())
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->whereMonth('event_date', now()->month)
                ->sum('total_price'),
        ];

        return view('caterer.bookings', compact('bookings', 'stats'));
    }

    /**
     * Show booking details
     */
    public function showBooking($bookingId)
    {
        $booking = \App\Models\Booking::with(['package', 'customer', 'menuItems.category'])
            ->where('caterer_id', auth()->id())
            ->findOrFail($bookingId);

        return view('caterer.booking-details', compact('booking'));
    }

    /**
     * Confirm a booking
     */
    public function confirmBooking(Request $request, $bookingId)
    {
        $booking = \App\Models\Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'pending')
            ->findOrFail($bookingId);

        $booking->update([
            'booking_status' => 'confirmed',
            'special_instructions' => $booking->special_instructions . "\n\nCaterer Note: " . ($request->confirmation_message ?? 'Booking confirmed.')
        ]);

        // TODO: Send email notification to customer

        return redirect()->route('caterer.bookings', ['tab' => 'confirmed'])
            ->with('success', 'Booking confirmed successfully! Customer will be notified.');
    }

    /**
     * Reject a booking
     */
    public function rejectBooking(Request $request, $bookingId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $booking = \App\Models\Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'pending')
            ->findOrFail($bookingId);

        $booking->update([
            'booking_status' => 'cancelled',
            'special_instructions' => $booking->special_instructions . "\n\nRejection Reason: " . $request->rejection_reason
        ]);

        // TODO: Send email notification to customer

        return redirect()->route('caterer.bookings', ['tab' => 'cancelled'])
            ->with('success', 'Booking has been rejected. Customer will be notified.');
    }

    /**
     * Mark booking as complete
     */
    public function completeBooking(Request $request, $bookingId)
    {
        $booking = \App\Models\Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'confirmed')
            ->findOrFail($bookingId);

        // Check if event date has passed
        if ($booking->event_date->isFuture()) {
            return back()->with('error', 'Cannot mark as complete before the event date.');
        }

        $booking->update([
            'booking_status' => 'completed',
            'special_instructions' => $booking->special_instructions . "\n\nCompletion Notes: " . ($request->completion_notes ?? 'Event completed successfully.')
        ]);

        // TODO: Send email notification to customer (request review)

        return redirect()->route('caterer.bookings', ['tab' => 'completed'])
            ->with('success', 'Booking marked as complete!');
    }

    public function menus()
    {
        $userId = auth()->id();

        // categories owned by the current user (with their items)
        $categories = Category::with('items')
            ->where('user_id', $userId)     // or ->where('caterer_id', $userId) if your table uses 'caterer_id'
            ->get();

        // packages owned by the current user (with their items)
        $packages = Package::with('items')
            ->where('user_id', $userId)     // change to 'caterer_id' if needed
            ->get();

        return view('caterer.menus', compact('categories', 'packages'));
    }

    public function packages()
    {
        $categories = \App\Models\Category::with('items')
            ->where('user_id', auth()->id())
            ->get();

        return view('caterer.packages', compact('categories'));
    }

    public function verifyReceipt()
    {
        return view('caterer.verifyReceipt');
    }

    public function payments()
    {
        return view('caterer.payments');
    }

    public function reviews()
    {
        return view('caterer.reviews');
    }
}


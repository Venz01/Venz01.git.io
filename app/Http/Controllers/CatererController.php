<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\Booking;
use App\Models\CatererAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CatererController extends Controller
{
    public function dashboard()
    {
        $catererId = auth()->id();
        
        // Get current month statistics
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        // Revenue statistics
        $currentMonthRevenue = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->whereMonth('event_date', $currentMonth->month)
            ->whereYear('event_date', $currentMonth->year)
            ->sum('total_price');
            
        $lastMonthRevenue = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->whereMonth('event_date', $lastMonth->month)
            ->whereYear('event_date', $lastMonth->year)
            ->sum('total_price');
            
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        // Booking statistics
        $totalBookings = Booking::where('caterer_id', $catererId)->count();
        $pendingBookings = Booking::where('caterer_id', $catererId)
            ->where('booking_status', 'pending')
            ->count();
        $confirmedBookings = Booking::where('caterer_id', $catererId)
            ->where('booking_status', 'confirmed')
            ->count();
        $completedBookings = Booking::where('caterer_id', $catererId)
            ->where('booking_status', 'completed')
            ->count();
            
        // Upcoming bookings (next 30 days)
        $upcomingBookings = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->whereBetween('event_date', [now(), now()->addDays(30)])
            ->orderBy('event_date', 'asc')
            ->with('customer')
            ->limit(5)
            ->get();
        
        // Recent bookings
        $recentBookings = Booking::where('caterer_id', $catererId)
            ->orderBy('created_at', 'desc')
            ->with('customer')
            ->limit(5)
            ->get();
        
        // Monthly revenue chart data (last 6 months)
        $revenueChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Booking::where('caterer_id', $catererId)
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->whereMonth('event_date', $month->month)
                ->whereYear('event_date', $month->year)
                ->sum('total_price');
            
            $revenueChartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Bookings by status for pie chart
        $bookingsByStatus = [
            'pending' => $pendingBookings,
            'confirmed' => $confirmedBookings,
            'completed' => $completedBookings,
            'cancelled' => Booking::where('caterer_id', $catererId)
                ->where('booking_status', 'cancelled')
                ->count()
        ];
        
        // Popular packages
        $popularPackages = Package::where('user_id', $catererId)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();
        
        // Average booking value
        $avgBookingValue = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->avg('total_price') ?? 0;
        
        return view('caterer.dashboard', compact(
            'currentMonthRevenue',
            'revenueGrowth',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'completedBookings',
            'upcomingBookings',
            'recentBookings',
            'revenueChartData',
            'bookingsByStatus',
            'popularPackages',
            'avgBookingValue'
        ));
    }

    /**
     * Show calendar view
     */
    public function calendar(Request $request)
    {
        $catererId = auth()->id();
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get all bookings for the month
        $bookings = Booking::where('caterer_id', $catererId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function($booking) {
                return $booking->event_date->format('Y-m-d');
            });
        
        // Get availability records
        $availability = CatererAvailability::where('caterer_id', $catererId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });
        
        // Get statistics
        $stats = [
            'total_bookings' => Booking::where('caterer_id', $catererId)
                ->whereBetween('event_date', [$startDate, $endDate])
                ->count(),
            'confirmed' => Booking::where('caterer_id', $catererId)
                ->whereBetween('event_date', [$startDate, $endDate])
                ->where('booking_status', 'confirmed')
                ->count(),
            'pending' => Booking::where('caterer_id', $catererId)
                ->whereBetween('event_date', [$startDate, $endDate])
                ->where('booking_status', 'pending')
                ->count(),
            'blocked_days' => CatererAvailability::where('caterer_id', $catererId)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'blocked')
                ->count(),
        ];
        
        return view('caterer.calendar', compact(
            'bookings',
            'availability',
            'startDate',
            'endDate',
            'stats',
            'year',
            'month'
        ));
    }

    /**
     * Block/unblock a date
     */
    public function toggleAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:available,blocked',
            'notes' => 'nullable|string|max:500'
        ]);

        $catererId = auth()->id();
        
        // Check if date has confirmed bookings
        $hasBooking = Booking::where('caterer_id', $catererId)
            ->where('event_date', $request->date)
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->exists();
        
        if ($hasBooking && $request->status === 'blocked') {
            return back()->with('error', 'Cannot block a date with existing bookings.');
        }
        
        CatererAvailability::updateOrCreate(
            [
                'caterer_id' => $catererId,
                'date' => $request->date
            ],
            [
                'status' => $request->status,
                'notes' => $request->notes
            ]
        );
        
        $message = $request->status === 'blocked' 
            ? 'Date blocked successfully.' 
            : 'Date unblocked successfully.';
        
        return back()->with('success', $message);
    }

    /**
     * Block multiple dates
     */
    public function blockDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500'
        ]);

        $catererId = auth()->id();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Check for existing bookings in range
        $hasBookings = Booking::where('caterer_id', $catererId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->exists();
        
        if ($hasBookings) {
            return back()->with('error', 'Cannot block dates with existing bookings in the selected range.');
        }
        
        // Block all dates in range
        $blocked = 0;
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            CatererAvailability::updateOrCreate(
                [
                    'caterer_id' => $catererId,
                    'date' => $currentDate->format('Y-m-d')
                ],
                [
                    'status' => 'blocked',
                    'notes' => $request->notes
                ]
            );
            $blocked++;
            $currentDate->addDay();
        }
        
        return back()->with('success', "Successfully blocked {$blocked} date(s).");
    }

    public function bookings(Request $request)
    {
        $query = Booking::where('caterer_id', auth()->id())
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
            'pending' => Booking::where('caterer_id', auth()->id())->where('booking_status', 'pending')->count(),
            'confirmed' => Booking::where('caterer_id', auth()->id())->where('booking_status', 'confirmed')->count(),
            'completed' => Booking::where('caterer_id', auth()->id())->where('booking_status', 'completed')->whereMonth('event_date', now()->month)->count(),
            'cancelled' => Booking::where('caterer_id', auth()->id())->where('booking_status', 'cancelled')->count(),
            'revenue' => Booking::where('caterer_id', auth()->id())
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
        $booking = Booking::with(['package', 'customer', 'menuItems.category'])
            ->where('caterer_id', auth()->id())
            ->findOrFail($bookingId);

        return view('caterer.booking-details', compact('booking'));
    }

    /**
     * Confirm a booking
     */
    public function confirmBooking(Request $request, $bookingId)
    {
        $booking = Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'pending')
            ->findOrFail($bookingId);

        $booking->update([
            'booking_status' => 'confirmed',
            'special_instructions' => $booking->special_instructions . "\n\nCaterer Note: " . ($request->confirmation_message ?? 'Booking confirmed.')
        ]);

        // Mark date as booked in availability
        CatererAvailability::updateOrCreate(
            [
                'caterer_id' => auth()->id(),
                'date' => $booking->event_date->format('Y-m-d')
            ],
            [
                'status' => 'booked',
                'notes' => 'Booking #' . $booking->booking_number
            ]
        );

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

        $booking = Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'pending')
            ->findOrFail($bookingId);

        $booking->update([
            'booking_status' => 'cancelled',
            'special_instructions' => $booking->special_instructions . "\n\nRejection Reason: " . $request->rejection_reason
        ]);

        return redirect()->route('caterer.bookings', ['tab' => 'cancelled'])
            ->with('success', 'Booking has been rejected. Customer will be notified.');
    }

    /**
     * Mark booking as complete
     */
    public function completeBooking(Request $request, $bookingId)
    {
        $booking = Booking::where('caterer_id', auth()->id())
            ->where('booking_status', 'confirmed')
            ->findOrFail($bookingId);

        if ($booking->event_date->isFuture()) {
            return back()->with('error', 'Cannot mark as complete before the event date.');
        }

        $booking->update([
            'booking_status' => 'completed',
            'special_instructions' => $booking->special_instructions . "\n\nCompletion Notes: " . ($request->completion_notes ?? 'Event completed successfully.')
        ]);

        return redirect()->route('caterer.bookings', ['tab' => 'completed'])
            ->with('success', 'Booking marked as complete!');
    }

    public function menus()
    {
        $userId = auth()->id();

        $categories = Category::with('items')
            ->where('user_id', $userId)
            ->get();

        $packages = Package::with('items')
            ->where('user_id', $userId)
            ->get();

        return view('caterer.menus', compact('categories', 'packages'));
    }

    public function packages()
    {
        $categories = Category::with('items')
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
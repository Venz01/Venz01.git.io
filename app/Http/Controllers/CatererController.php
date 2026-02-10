<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\Booking;
use App\Models\CatererAvailability;
use App\Models\Order;
use App\Models\DisplayMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\NotificationService;
use App\Models\MenuItem;

class CatererController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $caterer = auth()->user();
        $catererId = $caterer->id;

        // Get bookings statistics
        $bookingStats = [
            'pending' => Booking::where('caterer_id', $catererId)->where('booking_status', 'pending')->count(),
            'confirmed' => Booking::where('caterer_id', $catererId)->where('booking_status', 'confirmed')->count(),
            'completed' => Booking::where('caterer_id', $catererId)->where('booking_status', 'completed')->count(),
            'total' => Booking::where('caterer_id', $catererId)->count(),
        ];

        // ✅ NEW: Get orders statistics
        $orderStats = [
            'pending' => Order::where('caterer_id', $catererId)->where('order_status', 'pending')->count(),
            'confirmed' => Order::where('caterer_id', $catererId)->where('order_status', 'confirmed')->count(),
            'completed' => Order::where('caterer_id', $catererId)->where('order_status', 'completed')->count(),
            'total' => Order::where('caterer_id', $catererId)->count(),
        ];

        // ✅ UPDATED: Revenue from BOTH bookings and orders
        $revenueStats = [
            'bookings_total' => Booking::where('caterer_id', $catererId)
                ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
                ->sum('total_price'),
            'orders_total' => Order::where('caterer_id', $catererId)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'bookings_pending' => Booking::where('caterer_id', $catererId)
                ->where('payment_status', 'deposit_paid')
                ->sum('balance'),
            'orders_pending' => Order::where('caterer_id', $catererId)
                ->where('payment_status', 'pending')
                ->sum('total_amount'),
        ];

        // Calculate combined total revenue
        $revenueStats['total_revenue'] = $revenueStats['bookings_total'] + $revenueStats['orders_total'];
        $revenueStats['pending_revenue'] = $revenueStats['bookings_pending'] + $revenueStats['orders_pending'];

        // ✅ UPDATED: Recent bookings AND orders combined
        $recentBookings = Booking::where('caterer_id', $catererId)
            ->with(['customer', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($booking) {
                return [
                    'type' => 'booking',
                    'id' => $booking->id,
                    'number' => $booking->booking_number,
                    'customer_name' => $booking->customer_name,
                    'date' => $booking->event_date,
                    'amount' => $booking->total_price,
                    'status' => $booking->booking_status,
                    'payment_status' => $booking->payment_status,
                    'created_at' => $booking->created_at,
                ];
            });

        $recentOrders = Order::where('caterer_id', $catererId)
            ->with(['customer', 'items.displayMenu'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'id' => $order->id,
                    'number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'date' => $order->fulfillment_date,
                    'amount' => $order->total_amount,
                    'status' => $order->order_status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at,
                ];
            });

        // Combine and sort by created_at
        $recentTransactions = $recentBookings->concat($recentOrders)
            ->sortByDesc('created_at')
            ->take(10);

        // ✅ UPDATED: Upcoming events from both bookings and orders
        $upcomingEvents = collect();

        // Get upcoming bookings
        $upcomingBookings = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function($booking) {
                return [
                    'type' => 'booking',
                    'id' => $booking->id,
                    'number' => $booking->booking_number,
                    'customer_name' => $booking->customer_name,
                    'date' => $booking->event_date,
                    'time' => $booking->time_slot,
                    'venue' => $booking->venue_name,
                    'status' => $booking->booking_status,
                    'event_date' => $booking->event_date,
                ];
            });

        // Get upcoming orders
        $upcomingOrders = Order::where('caterer_id', $catererId)
            ->whereIn('order_status', ['pending', 'confirmed', 'preparing'])
            ->where('fulfillment_date', '>=', now())
            ->orderBy('fulfillment_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'id' => $order->id,
                    'number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'date' => $order->fulfillment_date,
                    'time' => $order->fulfillment_time,
                    'venue' => $order->fulfillment_type === 'delivery' ? $order->delivery_address : 'Pickup',
                    'status' => $order->order_status,
                    'event_date' => $order->fulfillment_date,
                ];
            });

        $upcomingEvents = $upcomingBookings->concat($upcomingOrders)
            ->sortBy('event_date')
            ->take(10);

        // Menu statistics
        $menuStats = [
            'total_items' => MenuItem::where('user_id', $catererId)->count(),
            'active_packages' => Package::where('user_id', $catererId)->where('status', 'active')->count(),
            'display_menus' => DisplayMenu::where('user_id', $catererId)->where('status', 'active')->count(),
        ];

        // ✅ NEW: Monthly revenue chart data (last 6 months)
        $monthlyRevenue = $this->getMonthlyRevenue($catererId);

        return view('caterer.dashboard', compact(
            'bookingStats',
            'orderStats',
            'revenueStats',
            'recentTransactions',
            'upcomingEvents',
            'menuStats',
            'monthlyRevenue'
        ));
    }

    private function getMonthlyRevenue($catererId)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
        }

        $data = [];
        foreach ($months as $index => $month) {
            $date = Carbon::now()->subMonths(5 - $index);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Bookings revenue
            $bookingRevenue = Booking::where('caterer_id', $catererId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
                ->sum('total_price');

            // Orders revenue
            $orderRevenue = Order::where('caterer_id', $catererId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $data[] = [
                'month' => $month,
                'booking_revenue' => $bookingRevenue,
                'order_revenue' => $orderRevenue,
                'total_revenue' => $bookingRevenue + $orderRevenue,
            ];
        }

        return $data;
    }

    // NEW: Helper methods for reports functionality
    
    private function getDateRange($period)
    {
        $end = Carbon::now();
        
        switch ($period) {
            case 'weekly':
                $start = Carbon::now()->startOfWeek();
                break;
            case 'monthly':
                $start = Carbon::now()->startOfMonth();
                break;
            case 'yearly':
                $start = Carbon::now()->startOfYear();
                break;
            default:
                $start = Carbon::now()->startOfMonth();
        }
        
        return ['start' => $start, 'end' => $end];
    }
    
    private function calculateMetrics($bookings)
    {
        return [
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->sum('total_price'),
            'total_deposits' => $bookings->sum('deposit_amount'),
            'total_balance' => $bookings->sum('balance'),
            'average_booking_value' => $bookings->avg('total_price') ?? 0,
            'total_guests' => $bookings->sum('guests'),
            'paid_bookings' => $bookings->where('payment_status', 'paid')->count(),
            'pending_bookings' => $bookings->where('payment_status', 'pending')->count(),
            'confirmed_bookings' => $bookings->where('booking_status', 'confirmed')->count(),
        ];
    }
    
    private function getPaymentStatusData($caterer_id, $dates)
    {
        return Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select('payment_status', DB::raw('count(*) as count'), DB::raw('sum(total_price) as total'))
            ->groupBy('payment_status')
            ->get();
    }
    
    private function getBookingStatusData($caterer_id, $dates)
    {
        return Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select('booking_status', DB::raw('count(*) as count'))
            ->groupBy('booking_status')
            ->get();
    }
    
    private function getRevenueTrends($caterer_id, $dates, $period)
    {
        // FIXED: PostgreSQL-compatible date formatting
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL syntax
            $format = $period === 'yearly' ? 'YYYY-MM' : 'YYYY-MM-DD';
            
            return Booking::where('caterer_id', $caterer_id)
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->select(
                    DB::raw("TO_CHAR(created_at, '{$format}') as date"),
                    DB::raw('sum(total_price) as revenue'),
                    DB::raw('count(*) as bookings')
                )
                ->groupBy(DB::raw("TO_CHAR(created_at, '{$format}')"))
                ->orderBy('date')
                ->get();
        } else {
            // MySQL syntax
            $format = $period === 'yearly' ? '%Y-%m' : '%Y-%m-%d';
            
            return Booking::where('caterer_id', $caterer_id)
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '{$format}') as date"),
                    DB::raw('sum(total_price) as revenue'),
                    DB::raw('count(*) as bookings')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }
    }
    
    private function getPopularMenuItems($caterer_id, $dates)
    {
        return DB::table('booking_menu_items')
            ->join('bookings', 'booking_menu_items.booking_id', '=', 'bookings.id')
            ->join('menu_items', 'booking_menu_items.menu_item_id', '=', 'menu_items.id')
            ->where('bookings.caterer_id', $caterer_id)
            ->whereBetween('bookings.created_at', [$dates['start'], $dates['end']])
            ->select(
                'menu_items.name',
                'menu_items.price',
                DB::raw('count(*) as times_ordered'),
                DB::raw('sum(menu_items.price) as total_revenue')
            )
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price')
            ->orderBy('times_ordered', 'desc')
            ->limit(10)
            ->get();
    }
    
    private function getEventTypesData($caterer_id, $dates)
    {
        return Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select('event_type', DB::raw('count(*) as count'), DB::raw('sum(total_price) as revenue'))
            ->groupBy('event_type')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Show calendar view
     */
     public function calendar()
    {
        $catererId = auth()->id();

        // Get bookings for calendar
        $bookings = Booking::where('caterer_id', $catererId)
            ->with(['customer', 'package'])
            ->get()
            ->map(function($booking) {
                return [
                    'id' => 'booking-' . $booking->id,
                    'type' => 'booking',
                    'title' => $booking->customer_name . ' - ' . $booking->event_type,
                    'start' => $booking->event_date->format('Y-m-d'),
                    'backgroundColor' => $this->getBookingColor($booking->booking_status),
                    'borderColor' => $this->getBookingColor($booking->booking_status),
                    'extendedProps' => [
                        'booking_id' => $booking->id,
                        'customer_name' => $booking->customer_name,
                        'event_type' => $booking->event_type,
                        'guests' => $booking->guests,
                        'venue' => $booking->venue_name,
                        'status' => $booking->booking_status,
                        'payment_status' => $booking->payment_status,
                    ]
                ];
            });

        // ✅ NEW: Get orders for calendar
        $orders = Order::where('caterer_id', $catererId)
            ->with(['customer', 'items.displayMenu'])
            ->get()
            ->map(function($order) {
                return [
                    'id' => 'order-' . $order->id,
                    'type' => 'order',
                    'title' => $order->customer_name . ' - Order',
                    'start' => Carbon::parse($order->fulfillment_date)->format('Y-m-d'),
                    'backgroundColor' => $this->getOrderColor($order->order_status),
                    'borderColor' => $this->getOrderColor($order->order_status),
                    'extendedProps' => [
                        'order_id' => $order->id,
                        'customer_name' => $order->customer_name,
                        'fulfillment_type' => $order->fulfillment_type,
                        'fulfillment_time' => $order->fulfillment_time,
                        'status' => $order->order_status,
                        'payment_status' => $order->payment_status,
                    ]
                ];
            });

        // Combine bookings and orders
        $events = $bookings->concat($orders);

        // Get blocked dates
        $blockedDates = CatererAvailability::where('caterer_id', $catererId)
            ->where('status', 'blocked')
            ->get()
            ->map(function($availability) {
                return [
                    'id' => 'blocked-' . $availability->id,
                    'type' => 'blocked',
                    'title' => 'Blocked',
                    'start' => $availability->date->format('Y-m-d'),
                    'backgroundColor' => '#DC2626',
                    'borderColor' => '#DC2626',
                    'display' => 'background',
                ];
            });

        $allEvents = $events->concat($blockedDates);

        return view('caterer.calendar', compact('allEvents'));
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

        try {
            $this->notificationService->notifyBookingConfirmed($booking);
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmed notification', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }

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

        try {
            $this->notificationService->notifyBookingRejected($booking);
        } catch (\Exception $e) {
            \Log::error('Failed to send booking rejected notification', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }

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

        try {
            $this->notificationService->notifyBookingCompleted($booking);
        } catch (\Exception $e) {
            \Log::error('Failed to send booking completed notification', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }

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

        // Get display menus grouped by category
        $displayMenus = \App\Models\DisplayMenu::where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        // Get unique categories from display menus
        $displayCategories = \App\Models\DisplayMenu::where('user_id', $userId)
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('caterer.menus', compact('categories', 'packages', 'displayMenus', 'displayCategories'));
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

    public function payments(Request $request)
    {
        $catererId = auth()->id();

        // ✅ UPDATED: Get bookings payments
        $bookingPayments = Booking::where('caterer_id', $catererId)
            ->with(['customer', 'package'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('payment_status')) {
            $bookingPayments->where('payment_status', $request->payment_status);
        }

        // ✅ NEW: Get orders payments
        $orderPayments = Order::where('caterer_id', $catererId)
            ->with(['customer', 'items.displayMenu'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('payment_status')) {
            $orderPayments->where('payment_status', $request->payment_status);
        }

        $bookings = $bookingPayments->paginate(10, ['*'], 'bookings_page');
        $orders = $orderPayments->paginate(10, ['*'], 'orders_page');

        // ✅ UPDATED: Payment statistics from both sources
        $paymentStats = [
            'total_revenue' => Booking::where('caterer_id', $catererId)
                    ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
                    ->sum('total_price') +
                Order::where('caterer_id', $catererId)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount'),
            
            'pending_payments' => Booking::where('caterer_id', $catererId)
                    ->where('payment_status', 'deposit_paid')
                    ->sum('balance') +
                Order::where('caterer_id', $catererId)
                    ->where('payment_status', 'pending')
                    ->sum('total_amount'),
            
            'bookings_deposit_paid' => Booking::where('caterer_id', $catererId)
                ->where('payment_status', 'deposit_paid')
                ->count(),
            
            'bookings_fully_paid' => Booking::where('caterer_id', $catererId)
                ->where('payment_status', 'fully_paid')
                ->count(),

            'orders_paid' => Order::where('caterer_id', $catererId)
                ->where('payment_status', 'paid')
                ->count(),

            'orders_pending' => Order::where('caterer_id', $catererId)
                ->where('payment_status', 'pending')
                ->count(),
        ];

        return view('caterer.payments', compact('bookings', 'orders', 'paymentStats'));
    }

    public function reviews()
    {
        return view('caterer.reviews');
    }

    /**
     * Handle bulk actions for categories and menu items
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'category_ids' => 'nullable|json',
                'item_ids' => 'nullable|json',
                'action' => 'required|in:delete,change_status',
                'value' => 'nullable|string',
            ]);

            $categoryIds = json_decode($request->category_ids ?? '[]', true);
            $itemIds = json_decode($request->item_ids ?? '[]', true);
            $action = $request->action;
            $value = $request->value;

            // Verify ownership
            $userId = auth()->id();
            
            if (!empty($categoryIds)) {
                $categoryIds = Category::where('user_id', $userId)
                    ->whereIn('id', $categoryIds)
                    ->pluck('id')
                    ->toArray();
            }

            if (!empty($itemIds)) {
                $itemIds = MenuItem::where('user_id', $userId)
                    ->whereIn('id', $itemIds)
                    ->pluck('id')
                    ->toArray();
            }

            DB::beginTransaction();
            
            try {
                $result = match($action) {
                    'delete' => $this->bulkDelete($categoryIds, $itemIds, $userId),
                    'change_status' => $this->bulkChangeStatus($itemIds, $value, $userId),
                    default => ['success' => false, 'message' => 'Invalid action']
                };

                DB::commit();
                return response()->json($result);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
            ], 500);
        }
    }

    /**
     * Bulk delete categories and items
     */
    protected function bulkDelete(array $categoryIds, array $itemIds, int $userId): array
    {
        $deletedCategories = 0;
        $deletedItems = 0;
        $errors = [];

        // Delete menu items first
        if (!empty($itemIds)) {
            try {
                // Detach from packages
                DB::table('menu_item_package')
                    ->whereIn('menu_item_id', $itemIds)
                    ->delete();

                $deletedItems = MenuItem::where('user_id', $userId)
                    ->whereIn('id', $itemIds)
                    ->delete();
                    
            } catch (\Exception $e) {
                $errors[] = 'Failed to delete some menu items';
            }
        }

        // Delete categories (only empty ones)
        if (!empty($categoryIds)) {
            foreach ($categoryIds as $categoryId) {
                try {
                    $category = Category::where('user_id', $userId)
                        ->where('id', $categoryId)
                        ->first();

                    if ($category) {
                        $itemCount = $category->items()->count();
                        
                        if ($itemCount > 0) {
                            $errors[] = "Category '{$category->name}' has {$itemCount} item(s) and cannot be deleted";
                        } else {
                            $category->delete();
                            $deletedCategories++;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = 'Failed to delete some categories';
                }
            }
        }

        // Build success message
        $messages = [];
        if ($deletedItems > 0) {
            $messages[] = "{$deletedItems} item(s) deleted";
        }
        if ($deletedCategories > 0) {
            $messages[] = "{$deletedCategories} category(ies) deleted";
        }

        $success = ($deletedItems > 0 || $deletedCategories > 0);
        
        return [
            'success' => $success,
            'message' => $success 
                ? implode(', ', $messages)
                : 'No items were deleted. ' . implode('. ', $errors),
            'deleted_categories' => $deletedCategories,
            'deleted_items' => $deletedItems,
            'errors' => $errors,
        ];
    }

    /**
     * Bulk change status for menu items
     */
    protected function bulkChangeStatus(array $itemIds, ?string $status, int $userId): array
    {
        if (empty($itemIds)) {
            return [
                'success' => false,
                'message' => 'No items selected'
            ];
        }

        if (!in_array($status, ['available', 'unavailable'])) {
            return [
                'success' => false,
                'message' => 'Invalid status value'
            ];
        }

        try {
            $updated = MenuItem::where('user_id', $userId)
                ->whereIn('id', $itemIds)
                ->update(['status' => $status]);

            return [
                'success' => true,
                'message' => "{$updated} item(s) set as {$status}",
                'updated_count' => $updated,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update items'
            ];
        }
    }

    /**
     * Display orders for the caterer
     */
    public function orders(Request $request)
    {
        $catererId = auth()->id();
        
        $query = \App\Models\Order::where('caterer_id', $catererId)
            ->with(['customer', 'items.displayMenu'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('fulfillment_type')) {
            $query->where('fulfillment_type', $request->fulfillment_type);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('customer_phone', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $orders = $query->paginate(15);

        // Get statistics
        $stats = [
            'pending' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'pending')->count(),
            'confirmed' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'confirmed')->count(),
            'preparing' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'preparing')->count(),
            'ready' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'ready')->count(),
            'completed' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'completed')->count(),
            'cancelled' => \App\Models\Order::where('caterer_id', $catererId)->where('order_status', 'cancelled')->count(),
        ];

        // Today's orders
        $todaysOrders = \App\Models\Order::where('caterer_id', $catererId)
            ->whereDate('fulfillment_date', today())
            ->count();

        // Total revenue
        $totalRevenue = \App\Models\Order::where('caterer_id', $catererId)
            ->whereIn('order_status', ['completed', 'confirmed', 'preparing', 'ready'])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('caterer.orders.index', compact('orders', 'stats', 'todaysOrders', 'totalRevenue'));
    }

    /**
     * Show order details for caterer
     */
    public function showOrder($orderId)
    {
        $order = \App\Models\Order::where('caterer_id', auth()->id())
            ->with(['customer', 'items.displayMenu'])
            ->findOrFail($orderId);

        return view('caterer.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,completed,cancelled'
        ]);

        $order = \App\Models\Order::where('caterer_id', auth()->id())
            ->findOrFail($orderId);

        $order->update([
            'order_status' => $request->status
        ]);

        try {
            $order->load('caterer');
            $this->notificationService->notifyOrderStatusUpdate($order);
        } catch (\Exception $e) {
            \Log::error('Failed to send order status notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Confirm payment received
     */
    public function confirmPayment($orderId)
    {
        $order = \App\Models\Order::where('caterer_id', auth()->id())
            ->findOrFail($orderId);

        $order->update([
            'payment_status' => 'paid'
        ]);

        try {
            $order->load('caterer');
            $this->notificationService->notifyOrderPaymentConfirmed($order);
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Payment confirmed!');
    }
}
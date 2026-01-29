<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\Booking;
use App\Models\CatererAvailability;
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

    public function dashboard(Request $request)
    {
        $catererId = auth()->id();
        $period = $request->get('period', 'monthly'); // NEW: Add period parameter
        
        // NEW: Get date range based on period
        $dates = $this->getDateRange($period);
        
        // Get bookings for the selected period
        $bookings = Booking::where('caterer_id', $catererId)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->get();
        
        // Get current month statistics (keep your existing logic)
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        // Revenue statistics (existing)
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
        
        // Booking statistics (existing)
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
            
        // Upcoming bookings (next 30 days) - existing
        $upcomingBookings = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->whereBetween('event_date', [now(), now()->addDays(30)])
            ->orderBy('event_date', 'asc')
            ->with('customer')
            ->limit(5)
            ->get();
        
        // Recent bookings - existing
        $recentBookings = Booking::where('caterer_id', $catererId)
            ->orderBy('created_at', 'desc')
            ->with('customer')
            ->limit(5)
            ->get();
        
        // Monthly revenue chart data (last 6 months) - existing
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
        
        // Bookings by status for pie chart - existing
        $bookingsByStatus = [
            'pending' => $pendingBookings,
            'confirmed' => $confirmedBookings,
            'completed' => $completedBookings,
            'cancelled' => Booking::where('caterer_id', $catererId)
                ->where('booking_status', 'cancelled')
                ->count()
        ];
        
        // Popular packages - existing
        $popularPackages = Package::where('id', $catererId)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();
        
        // Average booking value - existing
        $avgBookingValue = Booking::where('caterer_id', $catererId)
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->avg('total_price') ?? 0;
        
        // NEW: Calculate metrics for selected period
        $metrics = $this->calculateMetrics($bookings);
        
        // NEW: Get payment status breakdown
        $paymentStatusData = $this->getPaymentStatusData($catererId, $dates);
        
        // NEW: Get booking status breakdown
        $bookingStatusData = $this->getBookingStatusData($catererId, $dates);
        
        // NEW: Get revenue trends
        $revenueTrends = $this->getRevenueTrends($catererId, $dates, $period);
        
        // NEW: Get popular menu items
        $popularItems = $this->getPopularMenuItems($catererId, $dates);
        
        // NEW: Get event types breakdown
        $eventTypes = $this->getEventTypesData($catererId, $dates);
        
        return view('caterer.dashboard', compact(
            // Existing variables
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
            'avgBookingValue',
            // NEW: Reports variables
            'metrics',
            'paymentStatusData',
            'bookingStatusData',
            'revenueTrends',
            'popularItems',
            'eventTypes',
            'period'
        ));
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
        // PostgreSQL uses TO_CHAR instead of DATE_FORMAT
        $format = $period === 'yearly' ? 'YYYY-MM' : 'YYYY-MM-DD';
        
        return Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select(
                DB::raw("TO_CHAR(created_at, '$format') as date"),
                DB::raw('sum(total_price) as revenue'),
                DB::raw('count(*) as bookings')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
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
}
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $caterer_id = Auth::id();
        $period = $request->get('period', 'monthly'); // weekly, monthly, yearly
        
        // Calculate date ranges
        $dates = $this->getDateRange($period);
        
        // Get bookings for the caterer in the selected period
        $bookings = Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->get();
        
        // ✅ NEW: Get orders for the caterer in the selected period
        $orders = \App\Models\Order::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->get();
        
        // Calculate metrics (now includes orders)
        $metrics = $this->calculateMetrics($bookings, $orders);
        
        // Get payment status breakdown
        $paymentStatusData = $this->getPaymentStatusData($caterer_id, $dates);
        
        // Get booking status breakdown
        $bookingStatusData = $this->getBookingStatusData($caterer_id, $dates);
        
        // ✅ NEW: Get order status breakdown
        $orderStatusData = $this->getOrderStatusData($caterer_id, $dates);
        
        // Get revenue trends (daily data for charts)
        $revenueTrends = $this->getRevenueTrends($caterer_id, $dates, $period);
        
        // Get popular menu items (from bookings)
        $popularItems = $this->getPopularMenuItems($caterer_id, $dates);
        
        // ✅ NEW: Get popular display menu items (from orders)
        $popularDisplayItems = $this->getPopularDisplayMenuItems($caterer_id, $dates);
        
        // Get event types breakdown
        $eventTypes = $this->getEventTypesData($caterer_id, $dates);
        
        // ✅ NEW: Get fulfillment types breakdown (for orders)
        $fulfillmentTypes = $this->getFulfillmentTypesData($caterer_id, $dates);
        
        return view('caterer.reports', compact(
            'metrics',
            'paymentStatusData',
            'bookingStatusData',
            'orderStatusData',
            'revenueTrends',
            'popularItems',
            'popularDisplayItems',
            'eventTypes',
            'fulfillmentTypes',
            'period'
        ));
    }
    
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
    
    private function calculateMetrics($bookings, $orders = null)
    {
        // Handle both possible column names for guests and balance in bookings
        $totalGuests = 0;
        $totalBalance = 0;
        
        foreach ($bookings as $booking) {
            // Try both possible column names for guests
            $guests = $booking->number_of_guests ?? $booking->guests ?? 0;
            $totalGuests += $guests;
            
            // Try both possible column names for balance
            $balance = $booking->balance ?? $booking->balance_amount ?? 0;
            $totalBalance += $balance;
        }

        // ✅ NEW: Calculate order metrics
        $orderRevenue = 0;
        $ordersPaid = 0;
        $ordersPending = 0;
        $ordersCount = 0;

        if ($orders) {
            $orderRevenue = $orders->where('payment_status', 'paid')->sum('total_amount');
            $ordersPaid = $orders->where('payment_status', 'paid')->count();
            $ordersPending = $orders->where('payment_status', 'pending')->count();
            $ordersCount = $orders->count();
        }

        // ✅ UPDATED: Combined metrics
        return [
            // Bookings
            'total_bookings' => $bookings->count(),
            'booking_revenue' => $bookings->sum('total_price'),
            'total_deposits' => $bookings->sum('deposit_amount'),
            'total_balance' => $totalBalance,
            'total_guests' => $totalGuests,
            'paid_bookings' => $bookings->where('payment_status', 'paid')->count(),
            'pending_bookings' => $bookings->where('payment_status', 'pending')->count(),
            'confirmed_bookings' => $bookings->where('booking_status', 'confirmed')->count(),
            
            // ✅ NEW: Orders
            'total_orders' => $ordersCount,
            'order_revenue' => $orderRevenue,
            'paid_orders' => $ordersPaid,
            'pending_orders' => $ordersPending,
            
            // ✅ NEW: Combined totals
            'total_revenue' => $bookings->sum('total_price') + $orderRevenue,
            'total_transactions' => $bookings->count() + $ordersCount,
            'average_booking_value' => $bookings->avg('total_price') ?? 0,
            'average_order_value' => $orders ? $orders->avg('total_amount') ?? 0 : 0,
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
        // Check database driver
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");
        
        if ($connection === 'pgsql') {
            // PostgreSQL uses TO_CHAR
            $format = $period === 'yearly' ? 'YYYY-MM' : 'YYYY-MM-DD';
            $dateColumn = DB::raw("TO_CHAR(created_at, '$format') as date");
        } else {
            // MySQL uses DATE_FORMAT
            $format = $period === 'yearly' ? '%Y-%m' : '%Y-%m-%d';
            $dateColumn = DB::raw("DATE_FORMAT(created_at, '$format') as date");
        }
        
        // Get booking revenue trends
        $bookingTrends = Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select(
                $dateColumn,
                DB::raw('sum(total_price) as booking_revenue'),
                DB::raw('count(*) as booking_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // ✅ NEW: Get order revenue trends
        $orderTrends = \App\Models\Order::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select(
                $dateColumn,
                DB::raw('sum(total_amount) as order_revenue'),
                DB::raw('count(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // ✅ UPDATED: Merge booking and order trends
        $allDates = $bookingTrends->keys()->merge($orderTrends->keys())->unique()->sort();
        
        return $allDates->map(function($date) use ($bookingTrends, $orderTrends) {
            $booking = $bookingTrends->get($date);
            $order = $orderTrends->get($date);
            
            return [
                'date' => $date,
                'booking_revenue' => $booking->booking_revenue ?? 0,
                'booking_count' => $booking->booking_count ?? 0,
                'order_revenue' => $order->order_revenue ?? 0,
                'order_count' => $order->order_count ?? 0,
                'total_revenue' => ($booking->booking_revenue ?? 0) + ($order->order_revenue ?? 0),
                'total_count' => ($booking->booking_count ?? 0) + ($order->order_count ?? 0),
            ];
        })->values();
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
     * ✅ NEW: Get order status breakdown
     */
    private function getOrderStatusData($caterer_id, $dates)
    {
        return \App\Models\Order::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->get();
    }
    
    /**
     * ✅ NEW: Get popular display menu items from orders
     */
    private function getPopularDisplayMenuItems($caterer_id, $dates)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('display_menus', 'order_items.display_menu_id', '=', 'display_menus.id')
            ->where('orders.caterer_id', $caterer_id)
            ->whereBetween('orders.created_at', [$dates['start'], $dates['end']])
            ->select(
                'display_menus.name',
                'display_menus.price',
                DB::raw('sum(order_items.quantity) as total_quantity'),
                DB::raw('count(distinct orders.id) as times_ordered'),
                DB::raw('sum(order_items.subtotal) as total_revenue')
            )
            ->groupBy('display_menus.id', 'display_menus.name', 'display_menus.price')
            ->orderBy('times_ordered', 'desc')
            ->limit(10)
            ->get();
    }
    
    /**
     * ✅ NEW: Get fulfillment types breakdown for orders
     */
    private function getFulfillmentTypesData($caterer_id, $dates)
    {
        return \App\Models\Order::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select('fulfillment_type', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('fulfillment_type')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    public function exportPdf(Request $request)
    {
        $caterer_id = Auth::id();
        $period = $request->get('period', 'monthly');
        
        $dates = $this->getDateRange($period);
        
        // Get all bookings for the period
        $bookings = Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ NEW: Get all orders for the period
        $orders = \App\Models\Order::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $metrics = $this->calculateMetrics($bookings, $orders);
        $paymentStatusData = $this->getPaymentStatusData($caterer_id, $dates);
        $bookingStatusData = $this->getBookingStatusData($caterer_id, $dates);
        $orderStatusData = $this->getOrderStatusData($caterer_id, $dates);
        $popularItems = $this->getPopularMenuItems($caterer_id, $dates);
        $popularDisplayItems = $this->getPopularDisplayMenuItems($caterer_id, $dates);
        $eventTypes = $this->getEventTypesData($caterer_id, $dates);
        $fulfillmentTypes = $this->getFulfillmentTypesData($caterer_id, $dates);
        
        $caterer = Auth::user();
        
        $pdf = Pdf::loadView('caterer.reports-pdf', compact(
            'metrics',
            'paymentStatusData',
            'bookingStatusData',
            'orderStatusData',
            'popularItems',
            'popularDisplayItems',
            'eventTypes',
            'fulfillmentTypes',
            'period',
            'dates',
            'caterer',
            'bookings',
            'orders'  // ✅ NEW: Pass orders to PDF view
        ))->setPaper('a4', 'landscape');
        
        $filename = 'report_' . $period . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $filename = 'report_' . $period . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new ReportsExport($period), $filename);
    }
}
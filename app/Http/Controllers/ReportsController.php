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
        
        // Calculate metrics
        $metrics = $this->calculateMetrics($bookings);
        
        // Get payment status breakdown
        $paymentStatusData = $this->getPaymentStatusData($caterer_id, $dates);
        
        // Get booking status breakdown
        $bookingStatusData = $this->getBookingStatusData($caterer_id, $dates);
        
        // Get revenue trends (daily data for charts)
        $revenueTrends = $this->getRevenueTrends($caterer_id, $dates, $period);
        
        // Get popular menu items
        $popularItems = $this->getPopularMenuItems($caterer_id, $dates);
        
        // Get event types breakdown
        $eventTypes = $this->getEventTypesData($caterer_id, $dates);
        
        return view('caterer.reports', compact(
            'metrics',
            'paymentStatusData',
            'bookingStatusData',
            'revenueTrends',
            'popularItems',
            'eventTypes',
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
        // MySQL uses DATE_FORMAT instead of TO_CHAR
        // Format: %Y-%m-%d for daily, %Y-%m for monthly
        $format = $period === 'yearly' ? '%Y-%m' : '%Y-%m-%d';
        
        return Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$format') as date"),
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
    
    public function exportPdf(Request $request)
    {
        $caterer_id = Auth::id();
        $period = $request->get('period', 'monthly');
        
        $dates = $this->getDateRange($period);
        $bookings = Booking::where('caterer_id', $caterer_id)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->get();
        
        $metrics = $this->calculateMetrics($bookings);
        $paymentStatusData = $this->getPaymentStatusData($caterer_id, $dates);
        $bookingStatusData = $this->getBookingStatusData($caterer_id, $dates);
        $popularItems = $this->getPopularMenuItems($caterer_id, $dates);
        $eventTypes = $this->getEventTypesData($caterer_id, $dates);
        
        $caterer = Auth::user();
        
        $pdf = Pdf::loadView('caterer.reports-pdf', compact(
            'metrics',
            'paymentStatusData',
            'bookingStatusData',
            'popularItems',
            'eventTypes',
            'period',
            'dates',
            'caterer'
        ));
        
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
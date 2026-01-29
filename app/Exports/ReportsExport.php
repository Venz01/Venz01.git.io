<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        $caterer = auth()->user();
        $dates = $this->getDateRange($this->period);
        
        // Get bookings using the relationship
        $bookings = $caterer->bookings()
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->with('customer')
            ->get();
        
        // Format data for Excel
        $data = collect();
        
        foreach ($bookings as $booking) {
            $data->push([
                $booking->id,
                $booking->customer->name ?? 'N/A',
                ucfirst($booking->event_type ?? 'N/A'),
                $booking->event_date ? $booking->event_date->format('Y-m-d') : 'N/A',
                $booking->number_of_guests ?? 0,
                '₱' . number_format($booking->total_price ?? 0, 2),
                '₱' . number_format($booking->deposit_amount ?? 0, 2),
                '₱' . number_format($booking->balance_amount ?? 0, 2),
                ucfirst($booking->payment_status ?? 'N/A'),
                ucfirst($booking->booking_status ?? 'N/A'),
                $booking->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Booking ID',
            'Customer Name',
            'Event Type',
            'Event Date',
            'Number of Guests',
            'Total Price',
            'Deposit Amount',
            'Balance Amount',
            'Payment Status',
            'Booking Status',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
            ],
        ];
    }

    private function getDateRange($period)
    {
        $end = now();
        
        switch ($period) {
            case 'weekly':
                $start = now()->subWeek();
                break;
            case 'yearly':
                $start = now()->subYear();
                break;
            case 'monthly':
            default:
                $start = now()->subMonth();
                break;
        }
        
        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}
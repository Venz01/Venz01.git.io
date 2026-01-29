<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsExport implements WithMultipleSheets
{
    protected $period;
    
    public function __construct($period)
    {
        $this->period = $period;
    }
    
    public function sheets(): array
    {
        return [
            new BookingsSheet($this->period),
            new SummarySheet($this->period),
        ];
    }
}

class BookingsSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $period;
    
    public function __construct($period)
    {
        $this->period = $period;
    }
    
    public function collection()
    {
        $dates = $this->getDateRange($this->period);
        
        return Booking::where('caterer_id', Auth::id())
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->orderBy('created_at', 'desc')
            ->get();
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
    
    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->customer_name,
            ucfirst($booking->event_type),
            $booking->event_date,
            $booking->guests ?? 0,
            '₱' . number_format($booking->total_price, 2),
            '₱' . number_format($booking->deposit_amount, 2),
            '₱' . number_format($booking->balance, 2),
            ucfirst(str_replace('_', ' ', $booking->payment_status)),
            ucfirst($booking->booking_status),
            $booking->created_at->format('Y-m-d H:i:s'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F46E5'], // Indigo/Blue color like in your image
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Auto-size all columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Get the last row with data
        $lastRow = $sheet->getHighestRow();

        // Apply borders to all data cells
        if ($lastRow > 1) {
            $sheet->getStyle('A2:K' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB'], // Light gray borders
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Apply alternating row colors for better readability
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => ['rgb' => 'F9FAFB'], // Very light gray
                        ],
                    ]);
                }
            }
        }

        // Set header row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Center align specific columns (ID, Guests, Status columns)
        if ($lastRow > 1) {
            $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I2:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
    
    public function title(): string
    {
        return 'Bookings';
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
}

class SummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $period;
    
    public function __construct($period)
    {
        $this->period = $period;
    }
    
    public function collection()
    {
        $dates = $this->getDateRange($this->period);
        $bookings = Booking::where('caterer_id', Auth::id())
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->get();
        
        return collect([
            ['Metric', 'Value'],
            ['Total Bookings', $bookings->count()],
            ['Total Revenue', '₱' . number_format($bookings->sum('total_price'), 2)],
            ['Total Deposits', '₱' . number_format($bookings->sum('deposit_amount'), 2)],
            ['Outstanding Balance', '₱' . number_format($bookings->sum('balance'), 2)],
            ['Average Booking Value', '₱' . number_format($bookings->avg('total_price') ?? 0, 2)],
            ['Total Guests Served', $bookings->sum('guests')],
            ['Paid Bookings', $bookings->where('payment_status', 'paid')->count()],
            ['Pending Payments', $bookings->where('payment_status', 'pending')->count()],
            ['Confirmed Bookings', $bookings->where('booking_status', 'confirmed')->count()],
        ]);
    }
    
    public function headings(): array
    {
        return [];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        // Get last row
        $lastRow = $sheet->getHighestRow();

        // Apply borders to all cells
        if ($lastRow > 1) {
            $sheet->getStyle('A2:B' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB'],
                    ],
                ],
            ]);

            // Alternate row colors
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => ['rgb' => 'F9FAFB'],
                        ],
                    ]);
                }
            }

            // Make metric column bold
            $sheet->getStyle('A2:A' . $lastRow)->getFont()->setBold(true);
        }

        // Set header row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
    
    public function title(): string
    {
        return 'Summary';
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
}
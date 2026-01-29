<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($period) }} Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 14px;
            color: #111827;
            line-height: 1.6;
            padding: 40px;
            background: #ffffff;
        }

        /* ===== Header ===== */
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .date-range,
        .generated-date {
            font-size: 12px;
            color: #6b7280;
        }

        /* ===== Metrics ===== */
        .metrics-grid {
            display: table;
            width: 100%;
            border-spacing: 15px;
            margin-bottom: 40px;
        }

        .metric-box {
            display: table-cell;
            width: 25%;
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
        }

        .metric-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        /* ===== Sections ===== */
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 18px;
            padding: 6px 0 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            border-left: 4px solid #3b82f6;
        }

        /* ===== Tables ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px;
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }

        tbody td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-style: italic;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .payment-paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .payment-deposit {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .payment-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* ===== Summary ===== */
        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 15px;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .summary-item-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .summary-item-value {
            font-size: 20px;
            font-weight: 700;
        }

        /* ===== Footer ===== */
        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        /* ===== Print ===== */
        @media print {
            body {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h1>{{ $caterer->business_name ?? $caterer->name }}</h1>
        <div class="report-title">{{ ucfirst($period) }} Performance Report</div>
        <div class="date-range">
            {{ $dates['start']->format('F d, Y') }} – {{ $dates['end']->format('F d, Y') }}
        </div>
        <div class="generated-date">
            Generated on {{ now()->format('F d, Y') }}
        </div>
    </div>

    <!-- Metrics -->
    <div class="metrics-grid">
        <div class="metric-box">
            <div class="metric-label">Total Revenue</div>
            <div class="metric-value">₱{{ number_format($metrics['total_revenue'], 2) }}</div>
            <div class="metric-subtitle">{{ ucfirst($period) }} earnings</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Total Bookings</div>
            <div class="metric-value">{{ $metrics['total_bookings'] }}</div>
            <div class="metric-subtitle">{{ $metrics['confirmed_bookings'] }} confirmed</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Average Booking Value</div>
            <div class="metric-value">₱{{ number_format($metrics['average_booking_value'], 2) }}</div>
            <div class="metric-subtitle">Per booking</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Total Guests</div>
            <div class="metric-value">{{ number_format($metrics['total_guests']) }}</div>
            <div class="metric-subtitle">Across all events</div>
        </div>
    </div>

    <!-- Detailed Bookings Table -->
    <div class="section">
        <div class="section-title">Detailed Bookings</div>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Event Type</th>
                    <th>Event Date</th>
                    <th class="text-center">Guests</th>
                    <th class="text-right">Total Price</th>
                    <th class="text-right">Deposit</th>
                    <th class="text-right">Balance</th>
                    <th>Payment Status</th>
                    <th>Booking Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->event_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                    <td class="text-center">
                        @php
                            // Handle both possible column names
                            $guestCount = $booking->number_of_guests ?? $booking->guests ?? null;
                        @endphp
                        {{ $guestCount ?? 'N/A' }}
                    </td>
                    <td class="text-right">₱{{ number_format($booking->total_price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($booking->deposit_amount, 2) }}</td>
                    <td class="text-right">
                        @php
                            // Handle both possible column names for balance
                            $balance = $booking->balance ?? $booking->balance_amount ?? 0;
                        @endphp
                        ₱{{ number_format($balance, 2) }}
                    </td>
                    <td>
                        @php
                            $statusClass = 'payment-pending';
                            if ($booking->payment_status === 'Paid' || $booking->payment_status === 'paid') {
                                $statusClass = 'payment-paid';
                            } elseif ($booking->payment_status === 'Deposit_paid' || $booking->payment_status === 'deposit_paid') {
                                $statusClass = 'payment-deposit';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($booking->payment_status) }}</span>
                    </td>
                    <td>
                        @php
                            $bookingClass = 'status-pending';
                            if ($booking->booking_status === 'Confirmed' || $booking->booking_status === 'confirmed') {
                                $bookingClass = 'status-confirmed';
                            } elseif ($booking->booking_status === 'Cancelled' || $booking->booking_status === 'cancelled') {
                                $bookingClass = 'status-cancelled';
                            }
                        @endphp
                        <span class="status-badge {{ $bookingClass }}">{{ ucfirst($booking->booking_status) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="no-data">No bookings found for this period</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Payment Status -->
    <div class="section">
        <div class="section-title">Payment Status Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Bookings</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentStatusData as $status)
                <tr>
                    <td>{{ ucfirst($status->payment_status) }}</td>
                    <td class="text-right">{{ $status->count }}</td>
                    <td class="text-right">₱{{ number_format($status->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="no-data">No payment data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Financial Summary -->
    <div class="section">
        <div class="section-title">Financial Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-item-label">Deposits Collected</div>
                <div class="summary-item-value">₱{{ number_format($metrics['total_deposits'], 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Outstanding Balance</div>
                <div class="summary-item-value">₱{{ number_format($metrics['total_balance'], 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Paid Bookings</div>
                <div class="summary-item-value">
                    {{ $metrics['paid_bookings'] }} / {{ $metrics['total_bookings'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-brand">Powered by CaterEase</div>
        <div class="footer-info">
            {{ $caterer->business_name ?? $caterer->name }} | {{ $caterer->email }}
        </div>
    </div>

</body>
</html>
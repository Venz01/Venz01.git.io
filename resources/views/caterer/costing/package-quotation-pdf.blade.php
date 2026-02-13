<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation — {{ $package->name }}</title>
    <style>
        /* ── Reset & Base ──────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f5f5f0;
            color: #1a1a1a;
            min-height: 100vh;
            padding: 40px 20px;
        }

        /* ── Paper ─────────────────────────────────────────────── */
        .paper {
            background: #ffffff;
            max-width: 680px;
            margin: 0 auto;
            padding: 0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            border-radius: 2px;
        }

        /* ── Header Band ────────────────────────────────────────── */
        .header-band {
            background: #1a1a1a;
            color: #ffffff;
            padding: 36px 40px 28px;
            position: relative;
            overflow: hidden;
        }

        .header-band::after {
            content: '';
            position: absolute;
            bottom: 0; right: 0;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            transform: translate(60px, 60px);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .business-name {
            font-family: 'Georgia', serif;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .business-tagline {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            margin-top: 3px;
            font-style: italic;
            font-family: 'Georgia', serif;
        }

        .quote-badge {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 10px 16px;
            text-align: right;
            border-radius: 2px;
        }

        .quote-badge .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.5);
        }

        .quote-badge .ref {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            font-family: 'Courier New', monospace;
            margin-top: 2px;
        }

        .header-meta {
            display: flex;
            gap: 32px;
        }

        .meta-item .meta-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.45);
        }

        .meta-item .meta-value {
            font-size: 13px;
            color: rgba(255,255,255,0.85);
            margin-top: 2px;
        }

        /* ── Accent Strip ───────────────────────────────────────── */
        .accent-strip {
            height: 4px;
            background: linear-gradient(90deg, #d4a017 0%, #e8b84b 50%, #d4a017 100%);
        }

        /* ── Body ───────────────────────────────────────────────── */
        .body-wrap { padding: 40px; }

        /* ── To Section ─────────────────────────────────────────── */
        .to-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 36px;
            padding-bottom: 28px;
            border-bottom: 1px solid #e8e8e0;
        }

        .to-block .to-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #888;
            margin-bottom: 6px;
        }

        .to-block .to-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            line-height: 1.2;
        }

        .to-block .to-sub {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
        }

        .validity-box {
            text-align: right;
            background: #fffbf0;
            border: 1px solid #e8d88a;
            padding: 12px 16px;
            border-radius: 2px;
            align-self: flex-start;
        }

        .validity-box .v-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #b8960a;
        }

        .validity-box .v-date {
            font-size: 13px;
            font-weight: bold;
            color: #7a6000;
            margin-top: 3px;
        }

        /* ── Package Section ────────────────────────────────────── */
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #888;
            margin-bottom: 14px;
        }

        .package-card {
            background: #f8f8f5;
            border: 1px solid #e0e0d8;
            border-left: 4px solid #1a1a1a;
            padding: 16px 20px;
            margin-bottom: 28px;
            border-radius: 0 2px 2px 0;
        }

        .package-card .pkg-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .package-card .pkg-desc {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ── Menu Items ─────────────────────────────────────────── */
        .menu-section { margin-bottom: 28px; }

        .category-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #555;
            border-bottom: 1px solid #e0e0d8;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .menu-items-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 20px;
        }

        .menu-item-row {
            font-size: 12px;
            color: #444;
            padding: 3px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .menu-item-row::before {
            content: '•';
            color: #d4a017;
            font-size: 14px;
            line-height: 1;
            flex-shrink: 0;
        }

        /* ── Inclusions ─────────────────────────────────────────── */
        .inclusions {
            background: #f0f4f8;
            padding: 14px 18px;
            border-radius: 2px;
            margin-bottom: 28px;
        }

        .inclusions .inc-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #5a7a9a;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .inclusions-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .inclusion-tag {
            font-size: 11px;
            background: rgba(90,122,154,0.12);
            color: #3a5a7a;
            padding: 3px 10px;
            border-radius: 12px;
        }

        /* ── Pricing Table ──────────────────────────────────────── */
        .pricing-table {
            border: 1px solid #e0e0d8;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .pricing-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .pricing-table thead {
            background: #f0f0e8;
        }

        .pricing-table thead th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #666;
            padding: 10px 16px;
            text-align: left;
            font-weight: 600;
        }

        .pricing-table thead th:last-child { text-align: right; }

        .pricing-table tbody td {
            padding: 12px 16px;
            font-size: 13px;
            color: #333;
            border-top: 1px solid #ebebeb;
        }

        .pricing-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #1a1a1a;
        }

        .pricing-table .total-row {
            background: #1a1a1a;
        }

        .pricing-table .total-row td {
            color: #ffffff;
            border-top: none;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: bold;
        }

        .pricing-table .total-row td:last-child { color: #e8d88a; font-size: 16px; }

        /* ── Payment Terms ──────────────────────────────────────── */
        .payment-terms {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }

        .payment-box {
            background: #f8f8f5;
            border: 1px solid #e0e0d8;
            padding: 14px 16px;
            border-radius: 2px;
        }

        .payment-box .pb-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #888;
            margin-bottom: 6px;
        }

        .payment-box .pb-amount {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .payment-box .pb-sub {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }

        .deposit-box {
            background: #fffbf0;
            border-color: #e8d88a;
        }

        .deposit-box .pb-amount { color: #b8860a; }

        /* ── Terms ──────────────────────────────────────────────── */
        .terms-section {
            border-top: 1px solid #e0e0d8;
            padding-top: 20px;
            margin-bottom: 28px;
        }

        .terms-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #888;
            margin-bottom: 10px;
        }

        .terms-list {
            list-style: none;
            space-y: 4px;
        }

        .terms-list li {
            font-size: 11px;
            color: #666;
            padding: 2px 0;
            padding-left: 14px;
            position: relative;
        }

        .terms-list li::before {
            content: '—';
            position: absolute;
            left: 0;
            color: #bbb;
        }

        /* ── Footer ─────────────────────────────────────────────── */
        .footer-band {
            background: #f0f0e8;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #dcdcd4;
        }

        .footer-band .footer-text {
            font-size: 11px;
            color: #888;
            font-style: italic;
        }

        .footer-band .footer-contact {
            font-size: 11px;
            color: #555;
            text-align: right;
        }

        /* ── Print ───────────────────────────────────────────────── */
        @media print {
            body { background: white; padding: 0; }
            .paper { box-shadow: none; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

{{-- Print Button (hidden when printing) --}}
<div class="no-print" style="text-align:center; margin-bottom: 24px;">
    <button onclick="window.print()"
            style="background:#1a1a1a; color:#fff; border:none; padding:12px 28px; font-size:14px; cursor:pointer; border-radius:4px; font-family:Georgia,serif; letter-spacing:0.5px;">
        🖨️ Print / Save as PDF
    </button>
    <button onclick="window.close()"
            style="background:transparent; color:#666; border:1px solid #ccc; padding:12px 20px; font-size:14px; cursor:pointer; border-radius:4px; font-family:Georgia,serif; margin-left:8px;">
        Close
    </button>
</div>

<div class="paper">

    {{-- Header --}}
    <div class="header-band">
        <div class="header-top">
            <div>
                <div class="business-name">
                    {{ $caterer->business_name ?? $caterer->name }}
                </div>
                <div class="business-tagline">Catering & Event Services</div>
            </div>
            <div class="quote-badge">
                <div class="label">Quotation</div>
                <div class="ref">{{ $reference_no }}</div>
            </div>
        </div>
        <div class="header-meta">
            <div class="meta-item">
                <div class="meta-label">Prepared For</div>
                <div class="meta-value">{{ $customer_name }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Event Type</div>
                <div class="meta-value">{{ $event_type }}</div>
            </div>
            @if($event_date)
            <div class="meta-item">
                <div class="meta-label">Event Date</div>
                <div class="meta-value">{{ $event_date->format('F d, Y') }}</div>
            </div>
            @endif
            <div class="meta-item">
                <div class="meta-label">Date Issued</div>
                <div class="meta-value">{{ $generated_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="accent-strip"></div>

    {{-- Body --}}
    <div class="body-wrap">

        {{-- To / Validity --}}
        <div class="to-section">
            <div class="to-block">
                <div class="to-label">Prepared For</div>
                <div class="to-name">{{ $customer_name }}</div>
                <div class="to-sub">{{ $event_type }} · {{ $guest_count }} Guests</div>
            </div>
            <div class="validity-box">
                <div class="v-label">Valid Until</div>
                <div class="v-date">{{ $valid_until->format('M d, Y') }}</div>
            </div>
        </div>

        {{-- Package Info --}}
        <p class="section-title">Package Details</p>
        <div class="package-card">
            <div class="pkg-name">{{ $package->name }}</div>
            @if($package->description)
                <div class="pkg-desc">{{ $package->description }}</div>
            @endif
        </div>

        {{-- Menu Items --}}
        @if($package->items->count() > 0)
        <div class="menu-section">
            <p class="section-title">Menu Inclusions</p>
            @foreach($package->items->groupBy('category.name') as $catName => $items)
            <div style="margin-bottom: 16px;">
                <div class="category-header">{{ $catName ?? 'Others' }}</div>
                <div class="menu-items-grid">
                    @foreach($items as $item)
                        <div class="menu-item-row">{{ $item->name }}</div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Standard Inclusions --}}
        <div class="inclusions">
            <div class="inc-label">Standard Inclusions</div>
            <div class="inclusions-grid">
                @foreach(['Buffet Table Setup', 'Serving Utensils', 'Water Dispenser', 'Steaming Equipment', 'Service Crew'] as $inc)
                    <span class="inclusion-tag">{{ $inc }}</span>
                @endforeach
                @if($package->dietary_tags)
                    @foreach($package->dietary_labels as $tag)
                        <span class="inclusion-tag">🌿 {{ $tag }}</span>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Pricing Table --}}
        <p class="section-title">Pricing Breakdown</p>
        <div class="pricing-table">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Guests</th>
                        <th>Rate / Head</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $package->name }}<br>
                            <span style="font-size:11px;color:#888;">Complete catering package</span>
                        </td>
                        <td>{{ $guest_count }}</td>
                        <td>₱{{ number_format($package->price, 2) }}</td>
                        <td>₱{{ number_format($total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right; font-size:12px; color:#666;">Subtotal</td>
                        <td>₱{{ number_format($total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right; font-size:12px; color:#666;">Service Fee</td>
                        <td>₱500.00</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align:right;">TOTAL AMOUNT DUE</td>
                        <td>₱{{ number_format($total_amount + 500, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Payment Terms --}}
        <p class="section-title">Payment Schedule</p>
        <div class="payment-terms">
            <div class="payment-box deposit-box">
                <div class="pb-label">Deposit Required (25%)</div>
                <div class="pb-amount">₱{{ number_format($deposit_amount, 2) }}</div>
                <div class="pb-sub">Due upon booking confirmation</div>
            </div>
            <div class="payment-box">
                <div class="pb-label">Remaining Balance (75%)</div>
                <div class="pb-amount">₱{{ number_format(($total_amount + 500) - $deposit_amount, 2) }}</div>
                <div class="pb-sub">Due on or before event date</div>
            </div>
        </div>

        {{-- Terms --}}
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <ul class="terms-list">
                <li>This quotation is valid for {{ $valid_until->diffInDays($generated_at) }} days from date of issue.</li>
                <li>A 25% non-refundable deposit is required to confirm the reservation.</li>
                <li>Final guest count must be confirmed at least 3 days before the event.</li>
                <li>Price may be adjusted based on final confirmed guest count.</li>
                <li>Cancellations within 7 days of the event may forfeit the full deposit.</li>
                <li>{{ $caterer->business_name ?? $caterer->name }} reserves the right to adjust pricing for venues outside service area.</li>
            </ul>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer-band">
        <div class="footer-text">
            Thank you for considering {{ $caterer->business_name ?? $caterer->name }}.<br>
            We look forward to serving you.
        </div>
        <div class="footer-contact">
            @if($caterer->contact_number ?? $caterer->phone ?? null)
                📞 {{ $caterer->contact_number ?? $caterer->phone }}<br>
            @endif
            @if($caterer->email)
                ✉ {{ $caterer->email }}<br>
            @endif
            @if($caterer->business_address)
                📍 {{ $caterer->business_address }}
            @endif
        </div>
    </div>

</div>

</body>
</html>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports & Analytics') }}
            </h2>
            
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <div class="flex gap-1 bg-gray-200 dark:bg-gray-700 rounded-lg p-1">
                    <a href="?period=weekly" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded text-xs sm:text-sm font-medium text-center {{ $period === 'weekly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Weekly
                    </a>
                    <a href="?period=monthly" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded text-xs sm:text-sm font-medium text-center {{ $period === 'monthly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Monthly
                    </a>
                    <a href="?period=yearly" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded text-xs sm:text-sm font-medium text-center {{ $period === 'yearly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Yearly
                    </a>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('caterer.reports.pdf', ['period' => $period]) }}" 
                       class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center justify-center gap-2 text-xs sm:text-sm font-medium transition">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>PDF</span>
                    </a>
                    <a href="{{ route('caterer.reports.excel', ['period' => $period]) }}" 
                       class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center gap-2 text-xs sm:text-sm font-medium transition">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium">Total Revenue</h3>
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold">₱{{ number_format($metrics['total_revenue'], 2) }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Bookings: ₱{{ number_format($metrics['booking_revenue'], 2) }}<br>
                            Orders: ₱{{ number_format($metrics['order_revenue'], 2) }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium">Total Transactions</h3>
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold">{{ $metrics['total_transactions'] }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $metrics['total_bookings'] }} bookings | {{ $metrics['total_orders'] }} orders
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium">Avg Transaction Value</h3>
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold">₱{{ number_format($metrics['average_booking_value'], 2) }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Orders: ₱{{ number_format($metrics['average_order_value'], 2) }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium">Total Guests Served</h3>
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold">{{ number_format($metrics['total_guests']) }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">From event bookings</p>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Revenue Trends</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="revenueTrendsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Payment Status</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Booking Status</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Order Status</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 3 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Event Types (Bookings)</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="eventTypesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Fulfillment Types (Orders)</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="fulfillmentTypesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Items Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                @if($popularItems->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                            🎉 Popular Package Items
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Orders</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($popularItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->times_ordered }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($item->total_revenue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($popularDisplayItems) && $popularDisplayItems->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                            📦 Popular Menu Items (Orders)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Times Ordered</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($popularDisplayItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->times_ordered }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->total_quantity }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($item->total_revenue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── THEME HELPERS ──────────────────────────────────────────────────────
        // These functions read the CURRENT theme every time they're called,
        // so charts always get the right colours whether in light or dark mode.
        function isDarkMode() {
            return document.documentElement.classList.contains('dark') ||
                   window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        function getTextColor()  { return isDarkMode() ? '#e5e7eb' : '#374151'; }
        function getGridColor()  { return isDarkMode() ? '#374151' : '#e5e7eb'; }

        // ── CHART DATA (from PHP) ──────────────────────────────────────────────
        const isMobile       = window.innerWidth < 640;
        const fontSize       = isMobile ? 10 : 12;
        const legendFontSize = isMobile ? 10 : 12;

        const revenueTrendsLabels  = {!! json_encode($revenueTrends->pluck('date')) !!};
        const bookingRevenueData   = {!! json_encode($revenueTrends->pluck('booking_revenue')) !!};
        const orderRevenueData     = {!! json_encode($revenueTrends->pluck('order_revenue')) !!};
        const totalRevenueData     = {!! json_encode($revenueTrends->pluck('total_revenue')) !!};

        const paymentLabels        = {!! json_encode($paymentStatusData->pluck('payment_status')) !!};
        const paymentCounts        = {!! json_encode($paymentStatusData->pluck('count')) !!};

        const bookingStatusLabels  = {!! json_encode($bookingStatusData->pluck('booking_status')) !!};
        const bookingStatusCounts  = {!! json_encode($bookingStatusData->pluck('count')) !!};

        const orderStatusLabels    = {!! isset($orderStatusData) ? json_encode($orderStatusData->pluck('order_status')) : '[]' !!};
        const orderStatusCounts    = {!! isset($orderStatusData) ? json_encode($orderStatusData->pluck('count')) : '[]' !!};

        const eventTypeLabels      = {!! json_encode($eventTypes->pluck('event_type')) !!};
        const eventTypeCounts      = {!! json_encode($eventTypes->pluck('count')) !!};

        const fulfillmentLabels    = {!! isset($fulfillmentTypes) ? json_encode($fulfillmentTypes->pluck('fulfillment_type')) : '[]' !!};
        const fulfillmentCounts    = {!! isset($fulfillmentTypes) ? json_encode($fulfillmentTypes->pluck('count')) : '[]' !!};

        // ── BUILD CHART OPTIONS ────────────────────────────────────────────────
        // Called every time we need fresh colours (initial render + theme switch)
        function buildScalesXY(extra) {
            return {
                y: {
                    beginAtZero: true,
                    ticks: { color: getTextColor(), font: { size: fontSize }, ...(extra && extra.yTicks) },
                    grid: { color: getGridColor() }
                },
                x: {
                    ticks: {
                        color: getTextColor(),
                        font: { size: fontSize },
                        maxRotation: isMobile ? 45 : 0,
                        minRotation: isMobile ? 45 : 0
                    },
                    grid: { color: getGridColor() }
                }
            };
        }

        function buildLegend(position) {
            return {
                position: position || 'top',
                labels: {
                    color: getTextColor(),
                    font: { size: legendFontSize },
                    padding: isMobile ? 8 : 10,
                    boxWidth: isMobile ? 30 : 40
                }
            };
        }

        // ── 1. REVENUE TRENDS ─────────────────────────────────────────────────
        const revenueTrendsChart = new Chart(
            document.getElementById('revenueTrendsChart').getContext('2d'),
            {
                type: 'line',
                data: {
                    labels: revenueTrendsLabels,
                    datasets: [
                        {
                            label: 'Booking Revenue',
                            data: bookingRevenueData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Order Revenue',
                            data: orderRevenueData,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Total Revenue',
                            data: totalRevenueData,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            fill: false,
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: buildLegend('top'),
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.dataset.label + ': ₱' + ctx.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: buildScalesXY({
                        yTicks: { callback: v => '₱' + v.toLocaleString() }
                    })
                }
            }
        );

        // ── 2. PAYMENT STATUS ─────────────────────────────────────────────────
        const paymentStatusChart = new Chart(
            document.getElementById('paymentStatusChart').getContext('2d'),
            {
                type: 'doughnut',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentCounts,
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(251, 191, 36)',
                            'rgb(239, 68, 68)',
                            'rgb(59, 130, 246)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: buildLegend('bottom') }
                }
            }
        );

        // ── 3. BOOKING STATUS ─────────────────────────────────────────────────
        const bookingStatusChart = new Chart(
            document.getElementById('bookingStatusChart').getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: bookingStatusLabels,
                    datasets: [{
                        label: 'Bookings',
                        data: bookingStatusCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.85)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: buildScalesXY()
                }
            }
        );

        // ── 4. ORDER STATUS ───────────────────────────────────────────────────
        const orderStatusChart = new Chart(
            document.getElementById('orderStatusChart').getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: orderStatusLabels,
                    datasets: [{
                        label: 'Orders',
                        data: orderStatusCounts,
                        backgroundColor: 'rgba(16, 185, 129, 0.85)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: buildScalesXY()
                }
            }
        );

        // ── 5. EVENT TYPES ────────────────────────────────────────────────────
        const eventTypesChart = new Chart(
            document.getElementById('eventTypesChart').getContext('2d'),
            {
                type: 'pie',
                data: {
                    labels: eventTypeLabels,
                    datasets: [{
                        data: eventTypeCounts,
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)',
                            'rgb(236, 72, 153)',
                            'rgb(251, 191, 36)',
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: buildLegend('bottom') }
                }
            }
        );

        // ── 6. FULFILLMENT TYPES ──────────────────────────────────────────────
        const fulfillmentTypesChart = new Chart(
            document.getElementById('fulfillmentTypesChart').getContext('2d'),
            {
                type: 'pie',
                data: {
                    labels: fulfillmentLabels,
                    datasets: [{
                        data: fulfillmentCounts,
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: buildLegend('bottom') }
                }
            }
        );

        // ── THEME SWITCH: update all charts when dark/light toggles ───────────
        function updateAllChartColors() {
            const text = getTextColor();
            const grid = getGridColor();

            // Helper: update a line/bar chart's scales
            function refreshScales(chart) {
                if (!chart.options.scales) return;
                ['x', 'y'].forEach(axis => {
                    if (chart.options.scales[axis]) {
                        chart.options.scales[axis].ticks.color = text;
                        chart.options.scales[axis].grid.color  = grid;
                    }
                });
            }

            // Helper: update legend label colour
            function refreshLegend(chart) {
                if (chart.options.plugins && chart.options.plugins.legend) {
                    chart.options.plugins.legend.labels.color = text;
                }
            }

            [revenueTrendsChart, bookingStatusChart, orderStatusChart].forEach(c => {
                refreshScales(c);
                refreshLegend(c);
                c.update();
            });

            [paymentStatusChart, eventTypesChart, fulfillmentTypesChart].forEach(c => {
                refreshLegend(c);
                c.update();
            });
        }

        // Watch Tailwind .dark class on <html>
        new MutationObserver(updateAllChartColors)
            .observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Watch OS-level preference
        window.matchMedia('(prefers-color-scheme: dark)')
              .addEventListener('change', updateAllChartColors);

        // ── RESIZE ────────────────────────────────────────────────────────────
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                [revenueTrendsChart, paymentStatusChart, bookingStatusChart,
                 orderStatusChart, eventTypesChart, fulfillmentTypesChart]
                .forEach(c => c.resize());
            }, 250);
        });
    </script>
</x-app-layout>
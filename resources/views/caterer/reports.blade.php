<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports & Analytics') }}
            </h2>
            
            <!-- Period Selector and Export Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <!-- Period Selector -->
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
                
                <!-- Export Buttons -->
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
            
            <!-- ✅ UPDATED: Key Metrics with Orders -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Total Revenue (Combined) -->
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

                <!-- Total Transactions (Combined) -->
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

                <!-- Average Values -->
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

                <!-- Total Guests -->
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

            <!-- ✅ UPDATED: Charts Row with Combined Data -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Revenue Trends Chart (Combined) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Revenue Trends</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="revenueTrendsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Payment Status</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Booking Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Booking Status</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- ✅ NEW: Order Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Order Status</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Event Types Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Event Types (Bookings)</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="eventTypesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- ✅ NEW: Fulfillment Types Chart (Orders) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Fulfillment Types (Orders)</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="fulfillmentTypesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ UPDATED: Popular Items Tables (Both Types) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Popular Package Items (Bookings) -->
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

                <!-- ✅ NEW: Popular Display Menu Items (Orders) -->
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
        // ✅ Chart configuration
        const isMobile = window.innerWidth < 640;
        const fontSize = isMobile ? 10 : 12;
        const legendFontSize = isMobile ? 10 : 12;
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e5e7eb' : '#374151';
        const gridColor = isDark ? '#374151' : '#e5e7eb';

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: { size: legendFontSize }
                    }
                }
            }
        };

        // ✅ UPDATED: Revenue Trends Chart with Booking + Order Data
        const revenueTrendsCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        const revenueTrendsChart = new Chart(revenueTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrends->pluck('date')) !!},
                datasets: [
                    {
                        label: 'Booking Revenue',
                        data: {!! json_encode($revenueTrends->pluck('booking_revenue')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Order Revenue',
                        data: {!! json_encode($revenueTrends->pluck('order_revenue')) !!},
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Total Revenue',
                        data: {!! json_encode($revenueTrends->pluck('total_revenue')) !!},
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: false,
                        borderWidth: 3,
                        tension: 0.4
                    }
                ]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize },
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize },
                            maxRotation: isMobile ? 45 : 0,
                            minRotation: isMobile ? 45 : 0
                        },
                        grid: { color: gridColor }
                    }
                }
            }
        });

        // Payment Status Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        const paymentStatusChart = new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentStatusData->pluck('payment_status')) !!},
                datasets: [{
                    data: {!! json_encode($paymentStatusData->pluck('count')) !!},
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)'
                    ]
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: legendFontSize },
                            padding: isMobile ? 8 : 10,
                            boxWidth: isMobile ? 30 : 40
                        }
                    }
                }
            }
        });

        // Booking Status Chart
        const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
        const bookingStatusChart = new Chart(bookingStatusCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($bookingStatusData->pluck('booking_status')) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($bookingStatusData->pluck('count')) !!},
                    backgroundColor: 'rgb(59, 130, 246)'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize }
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize }
                        },
                        grid: { color: gridColor }
                    }
                }
            }
        });

        // ✅ NEW: Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'bar',
            data: {
                labels: {!! isset($orderStatusData) ? json_encode($orderStatusData->pluck('order_status')) : '[]' !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! isset($orderStatusData) ? json_encode($orderStatusData->pluck('count')) : '[]' !!},
                    backgroundColor: 'rgb(16, 185, 129)'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize }
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { 
                            color: textColor,
                            font: { size: fontSize }
                        },
                        grid: { color: gridColor }
                    }
                }
            }
        });

        // Event Types Chart
        const eventTypesCtx = document.getElementById('eventTypesChart').getContext('2d');
        const eventTypesChart = new Chart(eventTypesCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($eventTypes->pluck('event_type')) !!},
                datasets: [{
                    data: {!! json_encode($eventTypes->pluck('count')) !!},
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
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: legendFontSize },
                            padding: isMobile ? 8 : 10,
                            boxWidth: isMobile ? 30 : 40
                        }
                    }
                }
            }
        });

        // ✅ NEW: Fulfillment Types Chart (Orders)
        const fulfillmentTypesCtx = document.getElementById('fulfillmentTypesChart').getContext('2d');
        const fulfillmentTypesChart = new Chart(fulfillmentTypesCtx, {
            type: 'pie',
            data: {
                labels: {!! isset($fulfillmentTypes) ? json_encode($fulfillmentTypes->pluck('fulfillment_type')) : '[]' !!},
                datasets: [{
                    data: {!! isset($fulfillmentTypes) ? json_encode($fulfillmentTypes->pluck('count')) : '[]' !!},
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)'
                    ]
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: legendFontSize },
                            padding: isMobile ? 8 : 10,
                            boxWidth: isMobile ? 30 : 40
                        }
                    }
                }
            }
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                revenueTrendsChart.resize();
                paymentStatusChart.resize();
                bookingStatusChart.resize();
                orderStatusChart.resize();
                eventTypesChart.resize();
                fulfillmentTypesChart.resize();
            }, 250);
        });
    </script>
</x-app-layout>
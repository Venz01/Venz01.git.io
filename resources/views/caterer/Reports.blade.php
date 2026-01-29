<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports & Analytics') }}
            </h2>
            
            <!-- Period Selector and Export Buttons -->
            <div class="flex gap-4">
                <div class="flex gap-2 bg-gray-200 dark:bg-gray-700 rounded-lg p-1">
                    <a href="?period=weekly" class="px-4 py-2 rounded text-sm font-medium {{ $period === 'weekly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Weekly
                    </a>
                    <a href="?period=monthly" class="px-4 py-2 rounded text-sm font-medium {{ $period === 'monthly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Monthly
                    </a>
                    <a href="?period=yearly" class="px-4 py-2 rounded text-sm font-medium {{ $period === 'yearly' ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        Yearly
                    </a>
                </div>
                
                <!-- Export Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('caterer.reports.pdf', ['period' => $period]) }}" 
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center gap-2 text-sm font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('caterer.reports.excel', ['period' => $period]) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 text-sm font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Revenue -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Revenue</h3>
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold">₱{{ number_format($metrics['total_revenue'], 2) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ ucfirst($period) }} earnings</p>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Bookings</h3>
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold">{{ $metrics['total_bookings'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $metrics['confirmed_bookings'] }} confirmed</p>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Avg Booking Value</h3>
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold">₱{{ number_format($metrics['average_booking_value'], 2) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Per booking</p>
                    </div>
                </div>

                <!-- Total Guests -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Guests Served</h3>
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($metrics['total_guests']) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Across all events</p>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Trends Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Revenue Trends</h3>
                        <canvas id="revenueTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Payment Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Payment Status</h3>
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Second Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Booking Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Booking Status</h3>
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>

                <!-- Event Types Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Event Types</h3>
                        <canvas id="eventTypesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Popular Menu Items Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Top Menu Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-4 text-gray-900 dark:text-gray-100">Item Name</th>
                                    <th class="text-right py-3 px-4 text-gray-900 dark:text-gray-100">Times Ordered</th>
                                    <th class="text-right py-3 px-4 text-gray-900 dark:text-gray-100">Price</th>
                                    <th class="text-right py-3 px-4 text-gray-900 dark:text-gray-100">Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popularItems as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-4 text-gray-900 dark:text-gray-100">{{ $item->name }}</td>
                                    <td class="text-right py-3 px-4 text-gray-900 dark:text-gray-100">{{ $item->times_ordered }}</td>
                                    <td class="text-right py-3 px-4 text-gray-900 dark:text-gray-100">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-right py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($item->total_revenue, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No menu items data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Financial Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Total Deposits Collected</p>
                            <p class="text-2xl font-bold text-green-500">₱{{ number_format($metrics['total_deposits'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Outstanding Balance</p>
                            <p class="text-2xl font-bold text-yellow-500">₱{{ number_format($metrics['total_balance'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Paid Bookings</p>
                            <p class="text-2xl font-bold text-blue-500">{{ $metrics['paid_bookings'] }} / {{ $metrics['total_bookings'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Determine if dark mode is active
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
        const gridColor = isDarkMode ? '#374151' : '#E5E7EB';

        // Revenue Trends Chart
        const revenueTrendsCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        const revenueTrendsChart = new Chart(revenueTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrends->pluck('date')) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($revenueTrends->pluck('revenue')) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
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
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor }
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
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
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
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor }
                    }
                }
            }
        });
    </script>
</x-app-layout>
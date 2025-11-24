<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Monthly Revenue</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format($currentMonthRevenue, 2) }}
                                </p>
                                <p class="text-xs mt-1 {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueGrowth), 1) }}% from last month
                                </p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalBookings }}</p>
                                <p class="text-xs mt-1 text-gray-500">All time</p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Bookings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Bookings</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $pendingBookings }}</p>
                                <a href="{{ route('caterer.bookings', ['tab' => 'pending']) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-block">View all →</a>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Avg. Booking Value</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">₱{{ number_format($avgBookingValue, 2) }}</p>
                                <p class="text-xs mt-1 text-gray-500">Per booking</p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Revenue Trend (Last 6 Months)</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Bookings by Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Bookings by Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-yellow-600">{{ $bookingsByStatus['pending'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                            </div>
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600">{{ $bookingsByStatus['confirmed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Confirmed</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-green-600">{{ $bookingsByStatus['completed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-red-600">{{ $bookingsByStatus['cancelled'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming & Recent Bookings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Bookings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events (Next 30 Days)</h3>
                            <a href="{{ route('caterer.calendar') }}" class="text-sm text-blue-600 hover:text-blue-800">View Calendar →</a>
                        </div>
                        @if($upcomingBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingBookings as $booking)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->customer_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->event_type }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->guests }} guests</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->event_date->format('M d, Y') }}</p>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                                @if($booking->booking_status === 'confirmed') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No upcoming events in the next 30 days</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Bookings</h3>
                            <a href="{{ route('caterer.bookings') }}" class="text-sm text-blue-600 hover:text-blue-800">View All →</a>
                        </div>
                        @if($recentBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentBookings as $booking)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->customer_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->booking_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($booking->total_price, 2) }}</p>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full
                                                @if($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->booking_status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->booking_status === 'completed') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No bookings yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Popular Packages -->
            @if($popularPackages->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Most Popular Packages</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach($popularPackages as $package)
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $package->name }}</p>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $package->bookings_count }}</p>
                            <p class="text-xs text-gray-500">bookings</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($revenueChartData, 'month')) !!},
                datasets: [{
                    label: 'Revenue (₱)',
                    data: {!! json_encode(array_column($revenueChartData, 'revenue')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
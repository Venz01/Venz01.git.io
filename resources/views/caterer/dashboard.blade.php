<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- ✅ UPDATED: Combined Stats Grid with Orders -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue (Bookings + Orders) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format($revenueStats['total_revenue'], 2) }}
                                </p>
                                <p class="text-xs mt-1 text-gray-500">
                                    Bookings: ₱{{ number_format($revenueStats['bookings_total'], 2) }}<br>
                                    Orders: ₱{{ number_format($revenueStats['orders_total'], 2) }}
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

                <!-- Total Transactions (Bookings + Orders) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Transactions</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $bookingStats['total'] + $orderStats['total'] }}
                                </p>
                                <p class="text-xs mt-1 text-gray-500">
                                    {{ $bookingStats['total'] }} bookings | {{ $orderStats['total'] }} orders
                                </p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Actions</p>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{ $bookingStats['pending'] + $orderStats['pending'] }}
                                </p>
                                <p class="text-xs mt-1 text-gray-500">
                                    {{ $bookingStats['pending'] }} bookings | {{ $orderStats['pending'] }} orders
                                </p>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Payments</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format($revenueStats['pending_revenue'], 2) }}
                                </p>
                                <p class="text-xs mt-1 text-gray-500">Awaiting payment</p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ NEW: Transaction Type Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bookings Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <span class="bg-blue-100 dark:bg-blue-900 p-2 rounded">🎉</span>
                            Event Bookings
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-yellow-600">{{ $bookingStats['pending'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                            </div>
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600">{{ $bookingStats['confirmed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Confirmed</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-green-600">{{ $bookingStats['completed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-gray-600">{{ $bookingStats['total'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ NEW: Orders Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 dark:bg-green-900 p-2 rounded">📦</span>
                            Menu Orders
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-amber-600">{{ $orderStats['pending'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                            </div>
                            <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-indigo-600">{{ $orderStats['confirmed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Confirmed</p>
                            </div>
                            <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-emerald-600">{{ $orderStats['completed'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                                <p class="text-3xl font-bold text-gray-600">{{ $orderStats['total'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ UPDATED: Revenue Chart with Both Data Series -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Revenue Trend (Last 6 Months)</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- ✅ UPDATED: Recent Transactions (Combined Bookings & Orders) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Events (Both Types) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events</h3>
                            <a href="{{ route('caterer.calendar') }}" class="text-sm text-blue-600 hover:text-blue-800">View Calendar →</a>
                        </div>
                        @if($upcomingEvents->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingEvents as $event)
                                <div class="border-l-4 {{ $event['type'] === 'booking' ? 'border-blue-500' : 'border-green-500' }} pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs px-2 py-1 rounded {{ $event['type'] === 'booking' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $event['type'] === 'booking' ? '🎉 Booking' : '📦 Order' }}
                                                </span>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $event['customer_name'] }}</p>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                @if($event['type'] === 'booking')
                                                    {{ $event['venue'] }}
                                                @else
                                                    {{ $event['venue'] }}
                                                @endif
                                            </p>
                                            @if($event['time'])
                                                <p class="text-xs text-gray-500">{{ $event['time'] }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}
                                            </p>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                                @if($event['status'] === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($event['status'] === 'preparing') bg-purple-100 text-purple-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($event['status']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No upcoming events</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Transactions (Combined) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Transactions</h3>
                            <div class="flex gap-2">
                                <a href="{{ route('caterer.bookings') }}" class="text-xs text-blue-600 hover:text-blue-800">Bookings →</a>
                                <a href="{{ route('caterer.orders') }}" class="text-xs text-green-600 hover:text-green-800">Orders →</a>
                            </div>
                        </div>
                        @if($recentTransactions->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentTransactions as $transaction)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs px-2 py-1 rounded {{ $transaction['type'] === 'booking' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $transaction['type'] === 'booking' ? '🎉' : '📦' }}
                                                </span>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $transaction['customer_name'] }}</p>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction['number'] }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaction['created_at'])->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($transaction['amount'], 2) }}</p>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full
                                                @if($transaction['status'] === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($transaction['status'] === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($transaction['status'] === 'completed') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($transaction['status']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No transactions yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Menu Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Menu Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600">{{ $menuStats['active_packages'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Active Packages</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <p class="text-3xl font-bold text-purple-600">{{ $menuStats['total_items'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Menu Items</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-3xl font-bold text-green-600">{{ $menuStats['display_menus'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Display Menus</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ✅ UPDATED: Revenue Chart with Booking + Order Data
        const revenueCtx = document.getElementById('revenueChart');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
                datasets: [
                    {
                        label: 'Booking Revenue (₱)',
                        data: {!! json_encode(array_column($monthlyRevenue, 'booking_revenue')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Order Revenue (₱)',
                        data: {!! json_encode(array_column($monthlyRevenue, 'order_revenue')) !!},
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Total Revenue (₱)',
                        data: {!! json_encode(array_column($monthlyRevenue, 'total_revenue')) !!},
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
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
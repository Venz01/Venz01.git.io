<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payments & Revenue') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- ✅ UPDATED: Revenue Summary Cards with Orders --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                {{-- Total Revenue (Bookings + Orders) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Revenue</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">
                                        ₱{{ number_format($paymentStats['total_revenue'], 2) }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">All time earnings</p>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Payments (Combined) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Payments</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">
                                        ₱{{ number_format($paymentStats['pending_payments'], 2) }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Awaiting payment</p>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Booking Deposits Pending --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Balance Due (Bookings)</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $paymentStats['bookings_deposit_paid'] }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Events awaiting balance</p>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Orders Pending Payment --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Orders</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $paymentStats['orders_pending'] }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Orders awaiting payment</p>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ UPDATED: Tabbed Payment Transactions (Bookings + Orders) --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payment Transactions</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage all payment transactions</p>
                        </div>
                        {{-- Tabs --}}
                        <div class="flex gap-2 bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                            <button onclick="showTab('bookings')" id="bookings-tab-btn" 
                                    class="px-4 py-2 text-sm font-medium rounded-md tab-button active">
                                🎉 Bookings ({{ $bookings->total() }})
                            </button>
                            <button onclick="showTab('orders')" id="orders-tab-btn"
                                    class="px-4 py-2 text-sm font-medium rounded-md tab-button">
                                📦 Orders ({{ $orders->total() }})
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Bookings Tab Content --}}
                <div id="bookings-tab" class="tab-content">
                    @if($bookings->count() > 0)
                        {{-- Desktop Table View --}}
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Booking #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Event Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($bookings as $booking)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $booking->booking_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $booking->customer_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->customer_email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->event_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($booking->total_price, 2) }}</div>
                                            @if($booking->payment_status === 'deposit_paid')
                                                <div class="text-xs text-gray-500">Balance: ₱{{ number_format($booking->balance, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($booking->payment_status === 'fully_paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($booking->payment_status === 'deposit_paid') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($booking->booking_status === 'completed') bg-green-100 text-green-800
                                                @elseif($booking->booking_status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('caterer.bookings', $booking->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Mobile Card View --}}
                        <div class="lg:hidden divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($bookings as $booking)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->booking_number }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->customer_name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($booking->payment_status === 'fully_paid') bg-green-100 text-green-800
                                        @elseif($booking->payment_status === 'deposit_paid') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <p>Event: {{ $booking->event_date->format('M d, Y') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($booking->total_price, 2) }}</p>
                                    @if($booking->payment_status === 'deposit_paid')
                                        <p class="text-xs">Balance: ₱{{ number_format($booking->balance, 2) }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('caterer.bookings', $booking->id) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    View Details →
                                </a>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            No bookings found
                        </div>
                    @endif
                </div>

                {{-- ✅ NEW: Orders Tab Content --}}
                <div id="orders-tab" class="tab-content hidden">
                    @if($orders->count() > 0)
                        {{-- Desktop Table View --}}
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Order #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fulfillment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $order->customer_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($order->fulfillment_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->fulfillment_type === 'delivery') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($order->fulfillment_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->order_status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->order_status === 'confirmed') bg-indigo-100 text-indigo-800
                                                @elseif($order->order_status === 'preparing') bg-purple-100 text-purple-800
                                                @elseif($order->order_status === 'ready') bg-teal-100 text-teal-800
                                                @elseif($order->order_status === 'pending') bg-amber-100 text-amber-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('caterer.orders.show', $order->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Mobile Card View --}}
                        <div class="lg:hidden divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer_name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <p>Fulfillment: {{ \Carbon\Carbon::parse($order->fulfillment_date)->format('M d, Y') }} - {{ ucfirst($order->fulfillment_type) }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                <a href="{{ route('caterer.orders.show', $order->id) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    View Details →
                                </a>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            No orders found
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'dark:bg-gray-800', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Add active class to selected button
            const activeBtn = document.getElementById(tabName + '-tab-btn');
            activeBtn.classList.add('active', 'bg-white', 'dark:bg-gray-800', 'text-blue-600', 'dark:text-blue-400');
            activeBtn.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
        }
    </script>
</x-app-layout>
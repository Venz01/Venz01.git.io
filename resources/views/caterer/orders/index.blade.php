<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Confirmed</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['confirmed'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Preparing</div>
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['preparing'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ready</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['ready'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cancelled</div>
                    <div class="text-3xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-purple-200 text-sm mb-1">Today's Orders</div>
                            <div class="text-3xl font-bold">{{ $todaysOrders }}</div>
                        </div>
                        <svg class="w-16 h-16 text-purple-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-green-200 text-sm mb-1">Total Revenue</div>
                            <div class="text-3xl font-bold">₱{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <svg class="w-16 h-16 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('caterer.orders') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Order #, Name, Phone..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <select name="fulfillment_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="delivery" {{ request('fulfillment_type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                            <option value="pickup" {{ request('fulfillment_type') === 'pickup' ? 'selected' : '' }}>Pickup</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('caterer.orders') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders List -->
            <div class="space-y-4">
                @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Order #{{ $order->order_number }}
                                    @if($order->fulfillment_type === 'delivery')
                                        <span class="text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                            Delivery
                                        </span>
                                    @else
                                        <span class="text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pickup
                                        </span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $order->created_at->format('F d, Y - g:i A') }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 dark:bg-{{ $order->status_color }}-900 dark:text-{{ $order->status_color }}-200 rounded-full text-sm font-medium">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                <span class="px-3 py-1 bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800 dark:bg-{{ $order->payment_status_color }}-900 dark:text-{{ $order->payment_status_color }}-200 rounded-full text-sm font-medium">
                                    Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Customer</div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer_phone }}</div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Fulfillment Date</div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->fulfillment_date->format('M d, Y') }}
                                </div>
                                @if($order->fulfillment_time)
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Items</div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->items->count() }} items
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Amount</div>
                                <div class="text-xl font-bold text-purple-600 dark:text-purple-400">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('caterer.orders.show', $order->id) }}" 
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm font-medium">
                                View Details
                            </a>
                            
                            @if($order->order_status === 'pending')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                                        Confirm Order
                                    </button>
                                </form>
                            @endif

                            @if(in_array($order->order_status, ['confirmed', 'preparing']))
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->order_status === 'confirmed' ? 'preparing' : 'ready' }}">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors text-sm font-medium">
                                        Mark as {{ $order->order_status === 'confirmed' ? 'Preparing' : 'Ready' }}
                                    </button>
                                </form>
                            @endif

                            @if($order->order_status === 'ready')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
                                        Mark as Completed
                                    </button>
                                </form>
                            @endif

                            @if($order->payment_status === 'pending')
                                <form action="{{ route('caterer.orders.confirm-payment', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
                                        Confirm Payment
                                    </button>
                                </form>
                            @endif

                            <a href="tel:{{ $order->customer_phone }}" 
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-sm font-medium">
                                Call Customer
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-32 h-32 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No orders found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Orders will appear here when customers place them.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Confirmed</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['confirmed'] }}</div>
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

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('customer.orders.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
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
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('customer.orders.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
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
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    Order #{{ $order->order_number }}
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
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Caterer -->
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Caterer</div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->caterer->business_name ?? $order->caterer->name }}
                                </div>
                            </div>

                            <!-- Fulfillment -->
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    {{ ucfirst($order->fulfillment_type) }}
                                </div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->fulfillment_date->format('M d, Y') }}
                                    @if($order->fulfillment_time)
                                        - {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                    @endif
                                </div>
                            </div>

                            <!-- Total -->
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Amount</div>
                                <div class="text-xl font-bold text-purple-600 dark:text-purple-400">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="mb-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Items ({{ $order->items->count() }})</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items->take(3) as $item)
                                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg">
                                    @if($item->displayMenu->image_path)
                                    <img src="{{ $item->displayMenu->image_path }}" alt="{{ $item->displayMenu->name }}"
                                        class="w-8 h-8 object-cover rounded">
                                    @endif
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $item->quantity }}x {{ $item->displayMenu->name }}
                                    </span>
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400">
                                    +{{ $order->items->count() - 3 }} more
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('customer.orders.show', $order->id) }}" 
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm font-medium">
                                View Details
                            </a>
                            
                            @if($order->canBeCancelled())
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium">
                                    Cancel Order
                                </button>
                            </form>
                            @endif

                            @if($order->caterer->contact_number || $order->caterer->phone)
                            <a href="tel:{{ $order->caterer->contact_number ?? $order->caterer->phone }}" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
                                Contact Caterer
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-32 h-32 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No orders found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Start ordering from your favorite caterers!</p>
                    <a href="{{ route('customer.caterers') }}" 
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors duration-200">
                        Browse Caterers
                    </a>
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
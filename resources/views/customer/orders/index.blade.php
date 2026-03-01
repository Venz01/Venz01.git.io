<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirmed</p>
                    <p class="text-3xl font-bold text-blue-500">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cancelled</p>
                    <p class="text-3xl font-bold text-red-500">{{ $stats['cancelled'] }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-6 mb-6">
                <form method="GET" action="{{ route('customer.orders.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Order Status</label>
                        <select name="status"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Statuses</option>
                            <option value="pending"   {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed'  ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ request('status') === 'preparing'  ? 'selected' : '' }}>Preparing</option>
                            <option value="ready"     {{ request('status') === 'ready'      ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ request('status') === 'completed'  ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Payment Status</label>
                        <select name="payment_status"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Payments</option>
                            <option value="pending"  {{ request('payment_status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="paid"     {{ request('payment_status') === 'paid'     ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('customer.orders.index') }}"
                           class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Orders List --}}
            <div class="space-y-4">
                @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden transition-all duration-200 hover:-translate-y-0.5">
                    <div class="p-6">
                        {{-- Header row --}}
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Order #{{ $order->order_number }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $order->created_at->format('F d, Y · g:i A') }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $statusStyles = [
                                        'pending'   => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                        'confirmed' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                        'preparing' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300',
                                        'ready'     => 'bg-teal-100 dark:bg-teal-900/40 text-teal-800 dark:text-teal-300',
                                        'completed' => 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    ];
                                    $paymentStyles = [
                                        'paid'     => 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                                        'pending'  => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                        'refunded' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                        'failed'   => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    ];
                                    $orderStyle   = $statusStyles[$order->order_status]     ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                    $paymentStyle = $paymentStyles[$order->payment_status]  ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $orderStyle }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $paymentStyle }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                        </div>

                        {{-- Detail grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">Caterer</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->caterer->business_name ?? $order->caterer->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">{{ ucfirst($order->fulfillment_type) }}</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->fulfillment_date->format('M d, Y') }}
                                    @if($order->fulfillment_time)
                                        · {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">Total Amount</p>
                                <p class="text-xl font-extrabold text-green-600 dark:text-green-400">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </p>
                            </div>
                        </div>

                        {{-- Items preview --}}
                        <div class="mb-5">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Items ({{ $order->items->count() }})
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items->take(3) as $item)
                                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 px-3 py-1.5 rounded-xl">
                                    @if($item->displayMenu->image_path)
                                    <img src="{{ $item->displayMenu->image_path }}" alt="{{ $item->displayMenu->name }}"
                                        class="w-6 h-6 object-cover rounded-md flex-shrink-0">
                                    @endif
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                        {{ $item->quantity }}× {{ $item->displayMenu->name }}
                                    </span>
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="flex items-center px-3 py-1.5 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 rounded-xl">
                                    +{{ $order->items->count() - 3 }} more
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('customer.orders.show', $order->id) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                View Details
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            @if($order->caterer->contact_number || $order->caterer->phone)
                            <a href="tel:{{ $order->caterer->contact_number ?? $order->caterer->phone }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-xl transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Contact Caterer
                            </a>
                            @endif

                            @if($order->canBeCancelled())
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 text-sm font-semibold rounded-xl border border-red-200 dark:border-red-800 transition-colors">
                                    Cancel Order
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No orders found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Start ordering from your favourite packages!</p>
                    <a href="{{ route('customer.caterers') }}"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-6 py-3 rounded-xl font-semibold transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse Packages
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $orders->appends(request()->query())->links() }}
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
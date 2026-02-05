<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.orders.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Order #{{ $order->order_number }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Status</h3>
                        
                        <div class="relative">
                            @php
                            $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed'];
                            $currentIndex = array_search($order->order_status, $statuses);
                            if ($order->order_status === 'cancelled') {
                                $currentIndex = 0;
                            }
                            @endphp

                            @if($order->order_status === 'cancelled')
                            <div class="text-center p-6 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-16 h-16 mx-auto text-red-600 dark:text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <h4 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-2">Order Cancelled</h4>
                                @if($order->cancellation_reason)
                                <p class="text-sm text-red-800 dark:text-red-200">{{ $order->cancellation_reason }}</p>
                                @endif
                            </div>
                            @else
                            <div class="space-y-4">
                                @foreach($statuses as $index => $status)
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        @if($index <= $currentIndex)
                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        @else
                                        <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white {{ $index <= $currentIndex ? '' : 'opacity-50' }}">
                                            {{ ucfirst($status) }}
                                        </h4>
                                        @if($index === $currentIndex)
                                        <p class="text-sm text-purple-600 dark:text-purple-400">Current Status</p>
                                        @endif
                                    </div>
                                </div>
                                @if($index < count($statuses) - 1)
                                <div class="ml-5 w-0.5 h-6 {{ $index < $currentIndex ? 'bg-purple-600' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Order Items</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start gap-4">
                                    @if($item->displayMenu->image_path)
                                    <img src="{{ $item->displayMenu->image_path }}" alt="{{ $item->displayMenu->name }}"
                                        class="w-24 h-24 object-cover rounded-lg">
                                    @else
                                    <div class="w-24 h-24 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif

                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $item->displayMenu->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            {{ $item->displayMenu->category }}
                                        </p>
                                        @if($item->displayMenu->description)
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                            {{ $item->displayMenu->description }}
                                        </p>
                                        @endif
                                        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <span>{{ $item->quantity }} {{ Str::plural($item->displayMenu->unit_type ?? 'item', $item->quantity) }}</span>
                                            <span>₱{{ number_format($item->price, 2) }} per {{ $item->displayMenu->unit_type ?? 'item' }}</span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                                            ₱{{ number_format($item->subtotal, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Summary -->
                        <div class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <div class="space-y-2">
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->delivery_fee > 0)
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Delivery Fee</span>
                                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <span>Total</span>
                                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Special Instructions</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $order->special_instructions }}</p>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Order Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">
                        <div class="space-y-6">
                            <!-- Caterer Info -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Caterer
                                </h4>
                                <div class="flex items-center gap-3 mb-3">
                                    @if($order->caterer->profile_photo)
                                    <img src="{{ $order->caterer->profile_photo }}" alt="{{ $order->caterer->business_name ?? $order->caterer->name }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                    @else
                                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($order->caterer->business_name ?? $order->caterer->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $order->caterer->business_name ?? $order->caterer->name }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->caterer->business_address }}
                                        </div>
                                    </div>
                                </div>
                                <a href="tel:{{ $order->caterer->contact_number ?? $order->caterer->phone }}" 
                                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Call Caterer
                                </a>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Order Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Order Date:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Fulfillment:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ ucfirst($order->fulfillment_type) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ $order->fulfillment_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                    @if($order->fulfillment_time)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Time:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($order->fulfillment_type === 'delivery' && $order->delivery_address)
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Delivery Address</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->delivery_address }}</p>
                            </div>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Payment</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Method:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ strtoupper($order->payment_method) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="px-2 py-1 bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800 dark:bg-{{ $order->payment_status_color }}-900 dark:text-{{ $order->payment_status_color }}-200 rounded text-xs font-medium">
                                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($order->canBeCancelled())
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Cancel Order
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-8 mb-8 text-center">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Order Placed Successfully!</h2>
                <p class="text-gray-600 dark:text-gray-400">Thank you for your order. We've sent a confirmation to your email.</p>
            </div>

            <!-- Order Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">Order #{{ $order->order_number }}</h3>
                            <p class="text-purple-100 mt-1">{{ $order->created_at->format('F d, Y - g:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-purple-100">Status</div>
                            <span class="inline-block px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-semibold mt-1">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Caterer Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Caterer
                            </h4>
                            <div class="text-gray-700 dark:text-gray-300">
                                <div class="font-medium">{{ $order->caterer->business_name ?? $order->caterer->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $order->caterer->business_address }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $order->caterer->contact_number ?? $order->caterer->phone }}</div>
                            </div>
                        </div>

                        <!-- Fulfillment Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ ucfirst($order->fulfillment_type) }} Details
                            </h4>
                            <div class="text-gray-700 dark:text-gray-300">
                                <div class="text-sm">
                                    <strong>Date:</strong> {{ $order->fulfillment_date->format('F d, Y') }}
                                </div>
                                @if($order->fulfillment_time)
                                <div class="text-sm">
                                    <strong>Time:</strong> {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                </div>
                                @endif
                                @if($order->fulfillment_type === 'delivery')
                                <div class="text-sm mt-2">
                                    <strong>Address:</strong><br>
                                    {{ $order->delivery_address }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Information
                            </h4>
                            <div class="text-gray-700 dark:text-gray-300 text-sm">
                                <div>{{ $order->customer_name }}</div>
                                <div>{{ $order->customer_email }}</div>
                                <div>{{ $order->customer_phone }}</div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment
                            </h4>
                            <div class="text-gray-700 dark:text-gray-300 text-sm">
                                <div><strong>Method:</strong> {{ strtoupper($order->payment_method) }}</div>
                                <div><strong>Status:</strong> 
                                    <span class="inline-block px-2 py-1 bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800 dark:bg-{{ $order->payment_status_color }}-900 dark:text-{{ $order->payment_status_color }}-200 rounded text-xs font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->special_instructions)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Special Instructions</h4>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $order->special_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Order Items</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($order->items as $item)
                    <div class="p-6 flex items-center gap-4">
                        @if($item->displayMenu->image_path)
                        <img src="{{ $item->displayMenu->image_path }}" alt="{{ $item->displayMenu->name }}"
                            class="w-20 h-20 object-cover rounded-lg">
                        @else
                        <div class="w-20 h-20 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item->displayMenu->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->displayMenu->category }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }} per {{ $item->displayMenu->unit_type ?? 'item' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('customer.orders.show', $order->id) }}" 
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-xl text-center transition-colors duration-200">
                    View Order Details
                </a>
                <a href="{{ route('customer.orders.index') }}" 
                    class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl text-center transition-colors duration-200">
                    View All Orders
                </a>
                <a href="{{ route('customer.caterers') }}" 
                    class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl text-center transition-colors duration-200">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
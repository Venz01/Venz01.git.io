<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('caterer.orders') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
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
                    <!-- Order Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Info -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Customer Information
                                    </h4>
                                    <div class="text-gray-700 dark:text-gray-300 space-y-1">
                                        <div class="font-medium">{{ $order->customer_name }}</div>
                                        <div class="text-sm">{{ $order->customer_email }}</div>
                                        <div class="text-sm">{{ $order->customer_phone }}</div>
                                        <a href="tel:{{ $order->customer_phone }}" 
                                           class="inline-flex items-center gap-2 text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 mt-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            Call Customer
                                        </a>
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
                                    <div class="text-gray-700 dark:text-gray-300 space-y-1">
                                        <div class="text-sm">
                                            <strong>Date:</strong> {{ $order->fulfillment_date->format('F d, Y') }}
                                        </div>
                                        @if($order->fulfillment_time)
                                        <div class="text-sm">
                                            <strong>Time:</strong> {{ date('g:i A', strtotime($order->fulfillment_time)) }}
                                        </div>
                                        @endif
                                        @if($order->fulfillment_type === 'delivery' && $order->delivery_address)
                                        <div class="text-sm mt-2">
                                            <strong>Delivery Address:</strong><br>
                                            {{ $order->delivery_address }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($order->special_instructions)
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    Special Instructions
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300 text-sm bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    {{ $order->special_instructions }}
                                </p>
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
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            <!-- Status Updates -->
                            @if($order->order_status === 'pending')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                                        ✓ Confirm Order
                                    </button>
                                </form>
                            @endif

                            @if($order->order_status === 'confirmed')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors font-medium">
                                        🍳 Mark as Preparing
                                    </button>
                                </form>
                            @endif

                            @if($order->order_status === 'preparing')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium">
                                        ✓ Mark as Ready
                                    </button>
                                </form>
                            @endif

                            @if($order->order_status === 'ready')
                                <form action="{{ route('caterer.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                        ✓ Mark as Completed
                                    </button>
                                </form>
                            @endif

                            <!-- Payment Confirmation -->
                            @if($order->payment_status === 'pending')
                                <form action="{{ route('caterer.orders.confirm-payment', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                        💰 Confirm Payment
                                    </button>
                                </form>
                            @endif

                            <!-- Contact Customer -->
                            <a href="tel:{{ $order->customer_phone }}" 
                                class="block w-full px-4 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors font-medium text-center">
                                📞 Call Customer
                            </a>

                            <a href="{{ route('caterer.orders') }}" 
                                class="block w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors font-medium text-center border border-gray-300 dark:border-gray-600">
                                ← Back to Orders
                            </a>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Payment Information</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Method:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->payment_method) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="px-2 py-1 bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800 dark:bg-{{ $order->payment_status_color }}-900 dark:text-{{ $order->payment_status_color }}-200 rounded text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                            @if($order->receipt_path)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ asset('storage/' . $order->receipt_path) }}" target="_blank"
                                   class="text-purple-600 hover:text-purple-700 dark:text-purple-400 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Receipt
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
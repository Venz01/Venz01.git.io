<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    Cart Items ({{ count($cartItems) }})
                                </h3>
                                <form action="{{ route('customer.orders.clear-cart') }}" method="POST" onsubmit="return confirm('Clear entire cart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 text-sm font-medium">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($cartItems as $item)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start gap-4">
                                    <!-- Item Image -->
                                    @if($item['menu_item']->image_path)
                                    <img src="{{ $item['menu_item']->image_path }}" 
                                         alt="{{ $item['menu_item']->name }}"
                                         class="w-24 h-24 object-cover rounded-lg">
                                    @else
                                    <div class="w-24 h-24 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif

                                    <!-- Item Details -->
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $item['menu_item']->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            {{ $item['menu_item']->category }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                ₱{{ number_format($item['menu_item']->price, 2) }} per {{ $item['menu_item']->unit_type ?? 'item' }}
                                            </span>
                                        </div>

                                        <!-- Caterer Info -->
                                        <div class="mt-2 flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $item['menu_item']->caterer->business_name ?? $item['menu_item']->caterer->name }}
                                        </div>
                                    </div>

                                    <!-- Quantity & Price -->
                                    <div class="text-right space-y-3">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                                            ₱{{ number_format($item['subtotal'], 2) }}
                                        </div>

                                        <!-- Quantity Controls -->
                                        <form action="{{ route('customer.orders.update-cart', $item['menu_item']->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                min="1" max="100" 
                                                class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                                onchange="this.form.submit()">
                                        </form>

                                        <!-- Remove Button -->
                                        <form action="{{ route('customer.orders.remove-from-cart', $item['menu_item']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 text-sm">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span class="font-semibold">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Delivery Fee</span>
                                <span>Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total</span>
                                <span>₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">+ delivery fee</p>
                        </div>

                        <a href="{{ route('customer.orders.checkout') }}" 
                            class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-xl text-center transition-colors duration-200">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('customer.caterers') }}" 
                            class="block w-full text-center text-purple-600 hover:text-purple-700 dark:text-purple-400 font-medium py-3 mt-3">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty Cart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-32 h-32 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Your cart is empty</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start adding some delicious items!</p>
                <a href="{{ route('customer.caterers') }}" 
                    class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors duration-200">
                    Browse Caterers
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
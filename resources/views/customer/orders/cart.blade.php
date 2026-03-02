<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Cart Items
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400">
                                    {{ count($cartItems) }}
                                </span>
                            </h3>
                            <form action="{{ route('customer.orders.clear-cart') }}" method="POST"
                                  onsubmit="return confirm('Clear entire cart?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors">
                                    Clear Cart
                                </button>
                            </form>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($cartItems as $item)
                            <div class="p-6 hover:bg-green-50/30 dark:hover:bg-green-900/10 transition-colors">
                                <div class="flex items-start gap-4">

                                    {{-- Item Image --}}
                                    @if($item['menu_item']->image_path)
                                    <img src="{{ $item['menu_item']->image_path }}"
                                         alt="{{ $item['menu_item']->name }}"
                                         class="w-24 h-24 object-cover rounded-xl flex-shrink-0">
                                    @else
                                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-10 h-10 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    @endif

                                    {{-- Item Details --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-0.5">
                                            {{ $item['menu_item']->name }}
                                        </h4>
                                        <span class="inline-block text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full mb-2">
                                            {{ $item['menu_item']->category }}
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ₱{{ number_format($item['menu_item']->price, 2) }} per {{ $item['menu_item']->unit_type ?? 'item' }}
                                        </p>
                                        <div class="mt-2 flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $item['menu_item']->caterer->business_name ?? $item['menu_item']->caterer->name }}
                                        </div>
                                    </div>

                                    {{-- Quantity & Price --}}
                                    <div class="text-right space-y-3 shrink-0">
                                        <div class="text-xl font-extrabold text-gray-900 dark:text-white">
                                            ₱{{ number_format($item['subtotal'], 2) }}
                                        </div>

                                        <form action="{{ route('customer.orders.update-cart', $item['menu_item']->id) }}" method="POST"
                                              class="flex items-center justify-end gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                   min="1" max="100"
                                                   class="w-20 px-2 py-1.5 text-center text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                   onchange="this.form.submit()">
                                        </form>

                                        <form action="{{ route('customer.orders.remove-from-cart', $item['menu_item']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-sm font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
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

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">Order Summary</h3>

                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="font-semibold text-gray-900 dark:text-white">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Delivery Fee</span>
                                <span class="text-gray-500 dark:text-gray-400 italic">Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700 mb-5">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900 dark:text-white">Total</span>
                                <span class="text-2xl font-extrabold text-green-600 dark:text-green-400">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">+ delivery fee at checkout</p>
                        </div>

                        <div class="space-y-2.5">
                            <a href="{{ route('customer.orders.checkout') }}"
                                class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Proceed to Checkout
                            </a>

                            <a href="{{ route('customer.caterers') }}"
                                class="flex items-center justify-center gap-2 w-full text-sm font-medium text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 py-2.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            @else
            {{-- Empty Cart --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-16 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Your cart is empty</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Start adding some delicious items!</p>
                <a href="{{ route('customer.caterers') }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-6 py-3 rounded-xl font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Browse Packages
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
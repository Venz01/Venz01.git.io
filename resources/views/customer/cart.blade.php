<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif

            @if(session('info'))
            <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
                {{ session('info') }}
            </div>
            @endif

            @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Your cart is empty</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Start adding packages to your cart to plan your event!
                </p>
                <div class="mt-6">
                    <a href="{{ route('customer.caterers') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Browse Caterers
                    </a>
                </div>
            </div>
            @else
            <!-- Cart with Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Cart Items (Left Side) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Cart Items ({{ $cartItems->count() }})
                            </h3>
                            <form action="{{ route('customer.cart.clear') }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to clear your cart?')">
                                @csrf
                                <button type="submit" 
                                        class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    Clear Cart
                                </button>
                            </form>
                        </div>

                        <!-- Cart Items List -->
                        @foreach($cartItems as $cartItem)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Package Image -->
                                <div class="flex-shrink-0">
                                    @if($cartItem->package->image_path)
                                    <img src="{{ asset('storage/' . $cartItem->package->image_path) }}" 
                                         alt="{{ $cartItem->package->name }}"
                                         class="w-full md:w-32 h-32 object-cover rounded-lg">
                                    @else
                                    <div class="w-full md:w-32 h-32 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <!-- Package Details & Form -->
                                <div class="flex-1">
                                    <!-- Package Info -->
                                    <div class="mb-4">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $cartItem->package->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            by {{ $cartItem->caterer->business_name }}
                                        </p>
                                        <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-2">
                                            ₱{{ number_format($cartItem->price, 2) }} per head
                                        </p>
                                    </div>

                                    <!-- Update Form -->
                                    <form action="{{ route('customer.cart.update', $cartItem->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PATCH')

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <!-- Quantity -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Quantity
                                                </label>
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $cartItem->quantity }}" 
                                                       min="1" 
                                                       max="10"
                                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                            </div>

                                            <!-- Guest Count -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Number of Guests
                                                </label>
                                                <input type="number" 
                                                       name="guest_count" 
                                                       value="{{ $cartItem->guest_count }}" 
                                                       min="1"
                                                       placeholder="e.g., 150"
                                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                            </div>
                                        </div>

                                        <!-- Event Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Event Date
                                            </label>
                                            <input type="date" 
                                                   name="event_date" 
                                                   value="{{ $cartItem->event_date?->format('Y-m-d') }}"
                                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                        </div>

                                        <!-- Special Requests -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Special Requests (Optional)
                                            </label>
                                            <textarea name="special_requests" 
                                                      rows="2"
                                                      maxlength="500"
                                                      placeholder="Dietary restrictions, setup preferences, etc."
                                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">{{ $cartItem->special_requests }}</textarea>
                                        </div>

                                        <!-- Subtotal & Actions -->
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <div>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                                                <span class="text-lg font-bold text-gray-900 dark:text-white ml-2">
                                                    ₱{{ number_format($cartItem->subtotal, 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex gap-2">
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Update
                                                </button>
                                                
                                                <button type="button"
                                                        onclick="document.getElementById('delete-form-{{ $cartItem->id }}').submit()"
                                                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Hidden Delete Form -->
                                    <form id="delete-form-{{ $cartItem->id }}" 
                                          action="{{ route('customer.cart.destroy', $cartItem->id) }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Continue Shopping -->
                    <a href="{{ route('customer.caterers') }}" 
                       class="inline-flex items-center text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Continue Shopping
                    </a>
                </div>

                <!-- Order Summary (Right Side) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Order Summary
                        </h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Tax (12%)</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900 dark:text-white">Total</span>
                                    <span class="text-xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button"
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                Proceed to Checkout
                            </button>
                            
                            <button type="button"
                                    onclick="if(confirm('Are you sure you want to clear your cart?')) { document.getElementById('clear-cart-form').submit(); }"
                                    class="w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Clear Cart
                            </button>
                        </div>

                        <form id="clear-cart-form" action="{{ route('customer.cart.clear') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <!-- Cart Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Prices and availability are subject to confirmation by the caterer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
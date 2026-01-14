<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            @if($cartItems->isEmpty())
                <!-- Empty Cart State -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Your cart is empty</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Start adding packages to your cart to plan your event!
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('customer.caterers') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            Browse Caterers
                        </a>
                    </div>
                </div>
            @else
                <!-- Cart Items -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cart Items List -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <!-- Package Image -->
                                    <div class="w-full sm:w-32 h-32 flex-shrink-0">
                                        @if($item->package->image_path)
                                            <img src="{{ asset('storage/' . $item->package->image_path) }}" 
                                                 alt="{{ $item->package->name }}"
                                                 class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Package Details -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $item->package->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    by {{ $item->caterer->business_name ?? $item->caterer->name }}
                                                </p>
                                                @if($item->package->pax)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $item->package->pax }} pax
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                                    ₱{{ number_format($item->price, 2) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Event Details -->
                                        <div class="mt-4 space-y-2 text-sm">
                                            @if($item->event_date)
                                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Event Date: {{ $item->event_date->format('M d, Y') }}
                                                </div>
                                            @endif
                                            @if($item->guest_count)
                                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Guests: {{ number_format($item->guest_count) }}
                                                </div>
                                            @endif
                                            @if($item->special_requests)
                                                <div class="flex items-start text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                    </svg>
                                                    <span class="flex-1">{{ $item->special_requests }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-4 flex items-center gap-4">
                                            <button onclick="openEditModal({{ $item->id }}, {{ json_encode($item) }})" 
                                                    class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                Edit Details
                                            </button>
                                            <form action="{{ route('customer.cart.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Remove this item from cart?')"
                                                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal ({{ $cartItems->count() }} items)</span>
                                    <span class="text-gray-900 dark:text-white font-medium">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Tax (12%)</span>
                                    <span class="text-gray-900 dark:text-white font-medium">₱{{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-base font-semibold text-gray-900 dark:text-white">Total</span>
                                        <span class="text-xl font-bold text-green-600">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <button class="w-full mt-6 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                Proceed to Checkout
                            </button>

                            <a href="{{ route('customer.caterers') }}" 
                               class="block w-full mt-3 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                                Continue Shopping
                            </a>

                            <form action="{{ route('customer.cart.clear') }}" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to clear your entire cart?')"
                                        class="w-full text-red-600 hover:text-red-700 text-sm font-medium">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Cart Item Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeEditModal()"></div>
            
            <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 z-10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Edit Cart Item</h3>
                
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Event Date
                            </label>
                            <input type="date" 
                                   name="event_date" 
                                   id="edit_event_date"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Number of Guests
                            </label>
                            <input type="number" 
                                   name="guest_count" 
                                   id="edit_guest_count"
                                   min="1"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Special Requests
                            </label>
                            <textarea name="special_requests" 
                                      id="edit_special_requests"
                                      rows="3"
                                      maxlength="500"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                      placeholder="Any special dietary requirements or preferences..."></textarea>
                        </div>

                        <input type="hidden" name="quantity" value="1">
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(itemId, item) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            // Set form action
            form.action = `/customer/cart/${itemId}`;
            
            // Populate form fields
            document.getElementById('edit_event_date').value = item.event_date || '';
            document.getElementById('edit_guest_count').value = item.guest_count || '';
            document.getElementById('edit_special_requests').value = item.special_requests || '';
            
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
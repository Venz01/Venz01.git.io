<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('customer.orders.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Caterer Info -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ordering From</h3>
                            <div class="flex items-center gap-4">
                                @if($caterer->profile_photo)
                                <img src="{{ $caterer->profile_photo }}" alt="{{ $caterer->business_name ?? $caterer->name }}"
                                    class="w-16 h-16 rounded-full object-cover">
                                @else
                                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($caterer->business_name ?? $caterer->name, 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $caterer->business_name ?? $caterer->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $caterer->business_address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Fulfillment Type -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Fulfillment Type</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="fulfillment_type" value="delivery" 
                                        class="peer sr-only" checked onchange="toggleDeliveryAddress()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition-all">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">Delivery</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">₱100 fee</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="fulfillment_type" value="pickup" 
                                        class="peer sr-only" onchange="toggleDeliveryAddress()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition-all">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">Pickup</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Free</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Schedule</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="fulfillment_date" required
                                        min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Time (Optional)
                                    </label>
                                    <input type="time" name="fulfillment_time"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div id="deliveryAddressSection" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Delivery Address</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Complete Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="delivery_address" rows="3" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="Street, Barangay, City, Province"></textarea>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_name" value="{{ auth()->user()->name }}" required
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="customer_email" value="{{ auth()->user()->email }}" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Phone <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" name="customer_phone" value="{{ auth()->user()->phone }}" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Special Instructions</h3>
                            <textarea name="special_instructions" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Any special requests or instructions for your order..."></textarea>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Payment Method</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="gcash" 
                                        class="peer sr-only" checked onchange="toggleReceiptUpload()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 text-center transition-all">
                                        <div class="font-semibold text-gray-900 dark:text-white">GCash</div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="paymaya" 
                                        class="peer sr-only" onchange="toggleReceiptUpload()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 text-center transition-all">
                                        <div class="font-semibold text-gray-900 dark:text-white">PayMaya</div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="bank_transfer" 
                                        class="peer sr-only" onchange="toggleReceiptUpload()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 text-center transition-all">
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Bank Transfer</div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" 
                                        class="peer sr-only" onchange="toggleReceiptUpload()">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 text-center transition-all">
                                        <div class="font-semibold text-gray-900 dark:text-white">COD</div>
                                    </div>
                                </label>
                            </div>

                            <div id="receiptUploadSection">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Payment Receipt <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="receipt" accept="image/*,.pdf"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">JPG, PNG, or PDF (max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h3>

                            <!-- Cart Items -->
                            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                                @foreach($cartItems as $item)
                                <div class="flex gap-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                                    @if($item['menu_item']->image_path)
                                    <img src="{{ $item['menu_item']->image_path }}" alt="{{ $item['menu_item']->name }}"
                                        class="w-12 h-12 object-cover rounded-lg">
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item['menu_item']->name }}
                                        </h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $item['quantity'] }} x ₱{{ number_format($item['menu_item']->price, 2) }}
                                        </p>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        ₱{{ number_format($item['subtotal'], 2) }}
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Pricing -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Subtotal</span>
                                    <span class="font-semibold">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Delivery Fee</span>
                                    <span id="deliveryFeeDisplay" class="font-semibold">₱{{ number_format($deliveryFee, 2) }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                                <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                    <span>Total</span>
                                    <span id="totalDisplay">₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-colors duration-200">
                                Place Order
                            </button>

                            <a href="{{ route('customer.orders.cart') }}" 
                                class="block w-full text-center text-purple-600 hover:text-purple-700 dark:text-purple-400 font-medium py-3 mt-3">
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDeliveryAddress() {
            const deliveryType = document.querySelector('input[name="fulfillment_type"]:checked').value;
            const addressSection = document.getElementById('deliveryAddressSection');
            const addressInput = document.querySelector('textarea[name="delivery_address"]');
            const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
            const totalDisplay = document.getElementById('totalDisplay');
            
            const subtotal = {{ $subtotal }};
            const deliveryFee = deliveryType === 'delivery' ? 100 : 0;
            const total = subtotal + deliveryFee;
            
            if (deliveryType === 'pickup') {
                addressSection.style.display = 'none';
                addressInput.removeAttribute('required');
            } else {
                addressSection.style.display = 'block';
                addressInput.setAttribute('required', 'required');
            }
            
            deliveryFeeDisplay.textContent = '₱' + deliveryFee.toFixed(2);
            totalDisplay.textContent = '₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        function toggleReceiptUpload() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const receiptSection = document.getElementById('receiptUploadSection');
            const receiptInput = document.querySelector('input[name="receipt"]');
            
            if (paymentMethod === 'cod') {
                receiptSection.style.display = 'none';
                receiptInput.removeAttribute('required');
            } else {
                receiptSection.style.display = 'block';
                receiptInput.setAttribute('required', 'required');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDeliveryAddress();
            toggleReceiptUpload();
        });
    </script>
</x-app-layout>
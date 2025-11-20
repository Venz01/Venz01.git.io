<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Your Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <!-- Step 1 - Completed -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-600">Event Details</p>
                        </div>
                    </div>
                    
                    <div class="w-24 h-1 bg-green-600 mx-4"></div>
                    
                    <!-- Step 2 - Current -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-semibold">
                            2
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-600">Payment</p>
                        </div>
                    </div>
                    
                    <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                    
                    <!-- Step 3 -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full font-semibold">
                            3
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Confirmation</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Package Link -->
            <a href="{{ route('customer.package.details', [$package->user_id, $package->id]) }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Package
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Payment Information</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">We require a 25% deposit to confirm your booking.</p>

                <!-- Order Summary -->
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>{{ $package->name }} ({{ $bookingDetails['guests'] }} guests)</span>
                            <span class="font-medium">₱{{ number_format($bookingDetails['total_price'], 0) }}</span>
                        </div>
                        
                        @if($selectedItems->count() < $package->items->count())
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Customized menu items ({{ $selectedItems->count() }} items)</span>
                            <span class="text-sm text-gray-500">Included</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Service fee</span>
                            <span class="font-medium">₱{{ number_format($serviceFee, 0) }}</span>
                        </div>
                        
                        <div class="border-t border-gray-300 dark:border-gray-600 my-3"></div>
                        
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                            <span>Total</span>
                            <span>₱{{ number_format($bookingDetails['total_price'] + $serviceFee, 0) }}</span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-bold text-green-600">
                            <span>Deposit due now (25%)</span>
                            <span>₱{{ number_format($depositDue, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <form action="{{ route('customer.booking.process-payment') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                                <input 
                                    type="text" 
                                    name="full_name"
                                    value="{{ auth()->user()->name }}"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                                <input 
                                    type="email" 
                                    name="email"
                                    value="{{ auth()->user()->email }}"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number *</label>
                            <input 
                                type="tel" 
                                name="phone"
                                placeholder="09XXXXXXXXX"
                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition-colors payment-method-option">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="gcash" 
                                    class="w-5 h-5 text-green-600"
                                    onchange="updatePaymentMethod('gcash')"
                                    required
                                >
                                <div class="ml-3 flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                        G
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">E-Wallet (GCash)</span>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition-colors payment-method-option">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="paymaya" 
                                    class="w-5 h-5 text-green-600"
                                    onchange="updatePaymentMethod('paymaya')"
                                >
                                <div class="ml-3 flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                        P
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">PayMaya</span>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition-colors payment-method-option md:col-span-2">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="bank_transfer" 
                                    class="w-5 h-5 text-green-600"
                                    onchange="updatePaymentMethod('bank_transfer')"
                                >
                                <div class="ml-3 flex items-center">
                                    <svg class="w-10 h-10 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-white">Bank Transfer</span>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Payment Instructions -->
                        <div id="paymentInstructions" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hidden">
                            <p class="text-sm text-blue-800 dark:text-blue-200" id="instructionText"></p>
                        </div>
                    </div>

                    <!-- Receipt Upload -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Payment Receipt Upload 
                            <span class="text-red-500">*</span>
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Please upload a screenshot or photo of your payment receipt. This is required to confirm your booking.
                        </p>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-500 transition-colors">
                            <input 
                                type="file" 
                                name="receipt" 
                                id="receiptInput"
                                accept="image/*,.pdf"
                                class="hidden"
                                onchange="handleFileSelect(event)"
                                required
                            >
                            
                            <div id="uploadPlaceholder">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">Drag and drop your receipt here, or</p>
                                <button 
                                    type="button"
                                    onclick="document.getElementById('receiptInput').click()"
                                    class="text-green-600 hover:text-green-700 font-medium"
                                >
                                    Browse files
                                </button>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, PDF up to 10MB</p>
                            </div>
                            
                            <div id="filePreview" class="hidden">
                                <img id="previewImage" src="" alt="Receipt preview" class="max-w-xs mx-auto rounded-lg mb-4">
                                <p id="fileName" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></p>
                                <button 
                                    type="button"
                                    onclick="clearFile()"
                                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                                >
                                    Remove file
                                </button>
                            </div>
                        </div>
                        
                        <p id="receiptError" class="text-red-500 text-sm mt-2 hidden">A payment receipt is required to proceed with your booking</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between">
                        <a 
                            href="{{ route('customer.package.details', [$package->user_id, $package->id]) }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </a>
                        
                        <button 
                            type="submit"
                            class="inline-flex items-center px-8 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors"
                        >
                            Pay Deposit
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePaymentMethod(method) {
            const instructions = document.getElementById('paymentInstructions');
            const instructionText = document.getElementById('instructionText');
            
            // Update selected radio button styling
            document.querySelectorAll('.payment-method-option').forEach(option => {
                option.classList.remove('border-green-500', 'bg-green-50');
            });
            event.target.closest('.payment-method-option').classList.add('border-green-500', 'bg-green-50');
            
            if (method === 'gcash') {
                instructionText.textContent = 'Please send your payment to GCash number: 0917-123-4567 (Juan Dela Cruz). Take a screenshot of the confirmation and upload below.';
                instructions.classList.remove('hidden');
            } else if (method === 'paymaya') {
                instructionText.textContent = 'Please send your payment to PayMaya number: 0917-123-4567 (Juan Dela Cruz). Take a screenshot of the confirmation and upload below.';
                instructions.classList.remove('hidden');
            } else if (method === 'bank_transfer') {
                instructionText.textContent = 'Bank: BDO | Account Name: Catering Services Corp | Account Number: 1234-5678-9012. Please upload your bank transfer receipt below.';
                instructions.classList.remove('hidden');
            }
        }
        
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Hide placeholder, show preview
            document.getElementById('uploadPlaceholder').classList.add('hidden');
            document.getElementById('filePreview').classList.remove('hidden');
            document.getElementById('receiptError').classList.add('hidden');
            
            // Update file name
            document.getElementById('fileName').textContent = file.name;
            
            // Show image preview if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewImage').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewImage').classList.add('hidden');
            }
        }
        
        function clearFile() {
            document.getElementById('receiptInput').value = '';
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('previewImage').src = '';
        }
        
        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const receiptInput = document.getElementById('receiptInput');
            if (!receiptInput.files.length) {
                e.preventDefault();
                document.getElementById('receiptError').classList.remove('hidden');
                receiptInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pay Balance') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Booking Summary --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Booking Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Booking #:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->booking_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Caterer:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Event Date:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->event_date->format('F d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Event Type:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->event_type }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Guests:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->guests }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Package:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->package->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Payment Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Package Price</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-green-600 dark:text-green-400">
                        <span class="text-sm">Deposit Paid (25%)</span>
                        <span class="font-medium">- ₱{{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-3 border-t-2 border-gray-300 dark:border-gray-600">
                        <span class="text-base font-semibold text-gray-900 dark:text-gray-100">Remaining Balance</span>
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">₱{{ number_format($booking->balance, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <strong>Note:</strong> This is the final payment for your booking. Once confirmed, your booking will be fully paid and ready for the event.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Form --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Submit Balance Payment</h3>

                <form action="{{ route('customer.booking.process-balance', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input type="radio" name="payment_method" value="gcash" class="sr-only peer" required>
                                <div class="flex flex-col items-center justify-center w-full peer-checked:text-indigo-600 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-400">
                                    <svg class="w-12 h-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                    </svg>
                                    <span class="font-medium">GCash</span>
                                </div>
                                <div class="absolute top-2 right-2 hidden peer-checked:block">
                                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input type="radio" name="payment_method" value="paymaya" class="sr-only peer" required>
                                <div class="flex flex-col items-center justify-center w-full peer-checked:text-indigo-600 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-400">
                                    <svg class="w-12 h-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                    </svg>
                                    <span class="font-medium">PayMaya</span>
                                </div>
                                <div class="absolute top-2 right-2 hidden peer-checked:block">
                                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input type="radio" name="payment_method" value="bank_transfer" class="sr-only peer" required>
                                <div class="flex flex-col items-center justify-center w-full peer-checked:text-indigo-600 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-400">
                                    <svg class="w-12 h-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
                                    </svg>
                                    <span class="font-medium text-center">Bank Transfer</span>
                                </div>
                                <div class="absolute top-2 right-2 hidden peer-checked:block">
                                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Instructions --}}
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-2">Payment Instructions:</h4>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-400 space-y-1 list-disc list-inside">
                            <li>Send the balance amount (₱{{ number_format($booking->balance, 2) }}) to the caterer</li>
                            <li>Take a screenshot or photo of your payment receipt</li>
                            <li>Upload it below for verification</li>
                            <li>Your booking will be marked as fully paid once confirmed</li>
                        </ul>
                    </div>

                    {{-- Receipt Upload --}}
                    <div>
                        <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Receipt <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="receipt" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="receipt" name="receipt" type="file" class="sr-only" accept="image/*,.pdf" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF or PDF up to 10MB</p>
                            </div>
                        </div>
                        @error('receipt')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition font-medium">
                            Submit Payment
                        </button>
                        <a href="{{ route('customer.booking.details', $booking->id) }}" class="flex-1 sm:flex-none text-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
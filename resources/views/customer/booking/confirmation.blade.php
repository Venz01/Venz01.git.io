<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="bg-green-50 border-2 border-green-500 rounded-2xl p-8 mb-8 text-center">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Booking Confirmed!</h2>
                <p class="text-lg text-gray-600 mb-4">
                    Your booking has been successfully submitted. The caterer will review your request and contact you shortly.
                </p>
                <p class="text-sm text-gray-500">
                    Booking Number: <span class="font-semibold text-green-600">{{ $booking->booking_number }}</span>
                </p>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                    <h3 class="text-2xl font-bold">{{ $booking->package->name }}</h3>
                    <p class="opacity-90">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</p>
                </div>

                <div class="p-8">
                    <!-- Event Information -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Event Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Event Type</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_type }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Event Date</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_date->format('F d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Time Slot</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->time_slot }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Number of Guests</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }} people</p>
                                </div>
                            </div>

                            <div class="flex items-start md:col-span-2">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Venue</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->venue_address }}</p>
                                </div>
                            </div>

                            @if($booking->special_instructions)
                            <div class="flex items-start md:col-span-2">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Special Instructions</p>
                                    <p class="text-gray-900 dark:text-white">{{ $booking->special_instructions }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Selected Menu Items -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Selected Menu Items ({{ $booking->menuItems->count() }})</h4>
                        
                        @php
                            $itemsByCategory = $booking->menuItems->groupBy('category.name');
                        @endphp

                        <div class="space-y-4">
                            @foreach($itemsByCategory as $categoryName => $items)
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">{{ $categoryName ?? 'Uncategorized' }}</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($items as $item)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $item->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>Price per head</span>
                                <span class="font-medium">₱{{ number_format($booking->price_per_head, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>Number of guests</span>
                                <span class="font-medium">{{ $booking->guests }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span class="font-medium">₱{{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>Service fee</span>
                                <span class="font-medium">₱{{ number_format($booking->service_fee, 2) }}</span>
                            </div>
                            
                            <div class="border-t border-gray-300 dark:border-gray-600 my-3"></div>
                            
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total Amount</span>
                                <span>₱{{ number_format($booking->total_price + $booking->service_fee, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-green-600 font-semibold">
                                <span>Deposit Paid (25%)</span>
                                <span>₱{{ number_format($booking->deposit_paid, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-lg font-bold text-orange-600">
                                <span>Balance Due</span>
                                <span>₱{{ number_format($booking->balance, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                The remaining balance of ₱{{ number_format($booking->balance, 2) }} must be paid on or before the event date.
                            </p>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Booking Status: Pending Confirmation
                        </span>
                        
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Payment Status: Deposit Paid
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a 
                    href="{{ route('customer.bookings') }}"
                    class="flex-1 text-center bg-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors"
                >
                    View My Bookings
                </a>
                
                <a 
                    href="{{ route('customer.caterers') }}"
                    class="flex-1 text-center border-2 border-gray-300 text-gray-700 py-4 px-6 rounded-xl font-semibold hover:bg-gray-50 transition-colors"
                >
                    Browse More Caterers
                </a>
                
                <button 
                    onclick="window.print()"
                    class="flex-1 text-center border-2 border-green-600 text-green-600 py-4 px-6 rounded-xl font-semibold hover:bg-green-50 transition-colors"
                >
                    Print Confirmation
                </button>
            </div>

            <!-- What's Next Section -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">What happens next?</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full mr-3 flex-shrink-0">1</div>
                        <p class="text-blue-800 dark:text-blue-200">The caterer will review your booking request and contact you within 24-48 hours.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full mr-3 flex-shrink-0">2</div>
                        <p class="text-blue-800 dark:text-blue-200">Once confirmed, you'll receive a booking confirmation email with all details.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full mr-3 flex-shrink-0">3</div>
                        <p class="text-blue-800 dark:text-blue-200">Pay the remaining balance on or before your event date.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full mr-3 flex-shrink-0">4</div>
                        <p class="text-blue-800 dark:text-blue-200">Enjoy your event! The caterer will handle everything on the day.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
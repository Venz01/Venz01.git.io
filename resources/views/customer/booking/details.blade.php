<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.bookings') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Booking Details - {{ $booking->booking_number }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Banner -->
            <div class="mb-6 p-6 rounded-xl
                @if($booking->booking_status == 'pending') bg-yellow-50 border-2 border-yellow-500
                @elseif($booking->booking_status == 'confirmed') bg-blue-50 border-2 border-blue-500
                @elseif($booking->booking_status == 'completed') bg-green-50 border-2 border-green-500
                @elseif($booking->booking_status == 'cancelled') bg-red-50 border-2 border-red-500
                @endif">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4
                            @if($booking->booking_status == 'pending') bg-yellow-500
                            @elseif($booking->booking_status == 'confirmed') bg-blue-500
                            @elseif($booking->booking_status == 'completed') bg-green-500
                            @elseif($booking->booking_status == 'cancelled') bg-red-500
                            @endif">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($booking->booking_status == 'pending')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @elseif($booking->booking_status == 'confirmed')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @elseif($booking->booking_status == 'completed')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                @elseif($booking->booking_status == 'cancelled')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold 
                                @if($booking->booking_status == 'pending') text-yellow-900
                                @elseif($booking->booking_status == 'confirmed') text-blue-900
                                @elseif($booking->booking_status == 'completed') text-green-900
                                @elseif($booking->booking_status == 'cancelled') text-red-900
                                @endif">
                                Booking {{ ucfirst($booking->booking_status) }}
                            </h3>
                            <p class="text-sm 
                                @if($booking->booking_status == 'pending') text-yellow-700
                                @elseif($booking->booking_status == 'confirmed') text-blue-700
                                @elseif($booking->booking_status == 'completed') text-green-700
                                @elseif($booking->booking_status == 'cancelled') text-red-700
                                @endif">
                                @if($booking->booking_status == 'pending')
                                    Waiting for caterer confirmation
                                @elseif($booking->booking_status == 'confirmed')
                                    Your booking has been confirmed by the caterer
                                @elseif($booking->booking_status == 'completed')
                                    This event has been completed
                                @elseif($booking->booking_status == 'cancelled')
                                    This booking was cancelled
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>

                        @if($booking->booking_status == 'pending')
                            <button onclick="openCancelModal()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel Booking
                            </button>
                        @elseif($booking->booking_status == 'confirmed')
                            <div class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-sm flex items-center gap-2 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Cancellation not available
                            </div>
                        @endif

                        @if($booking->payment_status == 'deposit_paid' && $booking->booking_status == 'confirmed')
                            <a href="{{ route('customer.booking.pay-balance', $booking->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Pay Balance
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Event Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Event Type</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_type }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Event Date</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Time Slot</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->time_slot }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Number of Guests</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }} people</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Venue</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->venue_address }}</p>
                            </div>
                            @if($booking->special_instructions)
                                <div class="md:col-span-2">
                                    <label class="text-sm text-gray-600 dark:text-gray-400">Special Instructions</label>
                                    <p class="text-gray-900 dark:text-white">{{ $booking->special_instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Selected Menu Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Selected Menu Items ({{ $booking->menuItems->count() }})
                        </h3>
                        
                        @php
                            $itemsByCategory = $booking->menuItems->groupBy('category.name');
                        @endphp

                        <div class="space-y-6">
                            @foreach($itemsByCategory as $categoryName => $items)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        {{ $categoryName ?? 'Uncategorized' }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($items as $item)
                                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</p>
                                                    @if($item->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Full Name</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->customer_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->customer_email }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Phone Number</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->customer_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Receipt -->
                    @if($booking->receipt_path)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Receipt</h3>
                            <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <img src="{{ $booking->receipt_path }}"
                                     alt="Payment Receipt" 
                                     class="max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal(this.src)">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Click to view full size</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Caterer Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Caterer</h3>
                        <div class="flex items-start mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-xl font-bold mr-3">
                                {{ substr($booking->caterer->business_name ?? $booking->caterer->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $booking->caterer->business_name ?? $booking->caterer->name }}
                                </h4>
                                @if($booking->caterer->contact_number)
                                    <a href="tel:{{ $booking->caterer->contact_number }}" 
                                       class="text-sm text-green-600 hover:text-green-700">
                                        {{ $booking->caterer->contact_number }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        @if($booking->caterer->business_address)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $booking->caterer->business_address }}
                            </p>
                        @endif

                        <div class="space-y-2">
                            <a href="{{ route('customer.caterer.profile', $booking->caterer_id) }}" 
                               class="block w-full text-center border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                                View Profile
                            </a>
                            @if($booking->caterer->contact_number)
                                <a href="tel:{{ $booking->caterer->contact_number }}" 
                                   class="block w-full text-center bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                                    Call Caterer
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Price per head</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($booking->price_per_head, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Guests</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Service fee</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($booking->service_fee, 2) }}</span>
                            </div>
                            
                            <div class="border-t border-gray-300 dark:border-gray-600 my-3"></div>
                            
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900 dark:text-white">Total Amount</span>
                                <span class="font-bold text-gray-900 dark:text-white text-lg">₱{{ number_format($booking->total_price + $booking->service_fee, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Deposit Paid</span>
                                <span class="font-medium text-green-600">₱{{ number_format($booking->deposit_paid, 2) }}</span>
                            </div>
                            
                            @if($booking->payment_status == 'deposit_paid')
                                <div class="flex justify-between pt-2 border-t border-gray-300 dark:border-gray-600">
                                    <span class="font-semibold text-orange-600">Balance Due</span>
                                    <span class="font-bold text-orange-600 text-lg">₱{{ number_format($booking->balance, 2) }}</span>
                                </div>
                            @else
                                <div class="pt-2 border-t border-gray-300 dark:border-gray-600">
                                    <span class="flex items-center text-green-600 font-semibold">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Fully Paid
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                            </p>
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Booking Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Booking Created</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            @if($booking->booking_status != 'pending')
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Status: {{ ucfirst($booking->booking_status) }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-start">
                                <div class="w-2 h-2 {{ $booking->event_date->isPast() ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Event Date</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->event_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Add this section to your customer/booking-details view after the booking information --}}

{{-- Review Section --}}
@if($booking->booking_status === 'completed')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Review & Rating</h3>
        
        @if($booking->hasReview())
            {{-- Show existing review --}}
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-medium text-green-800 dark:text-green-300">
                            You reviewed this booking
                        </h4>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $booking->review->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $booking->review->comment }}
                            </p>
                            
                            @if($booking->review->hasResponse())
                                <div class="mt-3 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                    <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400">
                                        Caterer's Response
                                    </p>
                                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $booking->review->caterer_response }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $booking->review->responded_at->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Show review button --}}
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-4 text-base font-medium text-gray-900 dark:text-gray-100">
                    Share Your Experience
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    How was your experience with {{ $booking->caterer->business_name ?? $booking->caterer->name }}?
                </p>
                <div class="mt-6">
                    <a href="{{ route('customer.review.create', $booking->id) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Write a Review
                    </a>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    Your honest feedback helps other customers make informed decisions
                </p>
            </div>
        @endif
    </div>
@endif
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <img id="modalImage" src="" alt="Receipt" class="max-w-full max-h-full object-contain">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function submitCancellation() {
            const reason = document.getElementById('cancelReason').value.trim();
            const errorEl = document.getElementById('cancelReasonError');
            if (reason.length < 10) {
                errorEl.classList.remove('hidden');
                document.getElementById('cancelReason').focus();
                return;
            }
            errorEl.classList.add('hidden');
            document.getElementById('cancelForm').submit();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeCancelModal();
        });
    </script>

    <style>
        @@media print {
            header, nav, .no-print, button {
                display: none !important;
            }
        }
    </style>

    {{-- ══ Cancellation info panel (shown after cancellation) ══ --}}
    @if($booking->booking_status === 'cancelled')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-6">
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5">
            <h4 class="font-semibold text-red-800 dark:text-red-300 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Booking Cancelled
                @if($booking->cancelled_by) — by {{ ucfirst($booking->cancelled_by) }} @endif
                @if($booking->cancelled_at) on {{ $booking->cancelled_at->format('M d, Y h:i A') }} @endif
            </h4>

            @if($booking->cancellation_reason)
                <p class="text-sm text-red-700 dark:text-red-400 mb-3">
                    <strong>Reason:</strong> {{ $booking->cancellation_reason }}
                </p>
            @endif

            @if($booking->deposit_paid > 0)
                @if(($booking->refund_status ?? 'none') === 'pending')
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-xl">
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-1">⏳ Refund Pending</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            Your deposit of <strong>₱{{ number_format($booking->deposit_paid, 2) }}</strong> is pending.
                            The caterer will contact you to arrange the refund.
                        </p>
                        @if($booking->refund_details)
                            <p class="text-xs text-yellow-600 dark:text-yellow-500 mt-2">
                                Refund details on record: <strong>{{ $booking->refund_details }}</strong>
                            </p>
                        @endif
                    </div>
                @elseif(($booking->refund_status ?? 'none') === 'issued')
                    <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl">
                        <p class="text-sm font-semibold text-green-800 dark:text-green-300">✓ Refund Issued</p>
                        <p class="text-sm text-green-700 dark:text-green-400 mt-1">The caterer has confirmed your refund was sent.</p>
                    </div>
                @elseif(($booking->refund_status ?? 'none') === 'waived')
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Refund waived.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
    @endif

    {{-- ══ Customer Cancel Modal ══ --}}
    <div id="cancelModal" class="hidden fixed inset-0 bg-gray-900/75 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Cancel Booking
                </h3>
                <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">

                {{-- Warning --}}
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">
                        ⚠️ This action is permanent. Once cancelled you will no longer be able to manage this booking.
                        The caterer will be notified immediately.
                    </p>
                </div>

                <form id="cancelForm"
                      method="POST"
                      action="{{ route('customer.booking.cancel-booking', $booking->id) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Reason --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Reason for cancellation <span class="text-red-500">*</span>
                        </label>
                        <textarea id="cancelReason"
                                  name="cancellation_reason"
                                  rows="3"
                                  placeholder="Please explain why you are cancelling…"
                                  class="w-full px-3 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-400 focus:border-red-400 resize-none"></textarea>
                        <p id="cancelReasonError" class="hidden mt-1 text-xs text-red-600">
                            Please provide at least 10 characters.
                        </p>
                    </div>

                    {{-- Refund details — only when a deposit was paid --}}
                    @if($booking->deposit_paid > 0)
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl space-y-3">
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">
                                    💰 You paid a deposit of ₱{{ number_format($booking->deposit_paid, 2) }}
                                </p>
                                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                                    Since payments are processed manually, the caterer will contact you to arrange
                                    your refund. Please provide your GCash number or bank details below so they can
                                    reach you.
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-1.5">
                                    GCash number / Bank account details
                                </label>
                                <input type="text"
                                       name="refund_details"
                                       placeholder="e.g. GCash 09XX-XXX-XXXX  or  BPI Savings XXXX-XXXX-XXXX"
                                       class="w-full px-3 py-2.5 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                <p class="text-xs text-yellow-600 dark:text-yellow-500 mt-1">
                                    The caterer will decide on the refund and contact you via email or phone.
                                </p>
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-2xl flex justify-end gap-3">
                <button onclick="closeCancelModal()"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border-2 border-gray-300 dark:border-gray-500 rounded-xl hover:bg-gray-50 transition-colors">
                    Keep Booking
                </button>
                <button onclick="submitCancellation()"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Yes, Cancel Booking
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
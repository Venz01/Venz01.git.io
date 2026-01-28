<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('caterer.bookings') }}" class="text-gray-600 hover:text-gray-900">
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
            <!-- Action Banner -->
            <div class="mb-6 p-6 rounded-xl border-2
                @if($booking->booking_status == 'pending') bg-yellow-50 border-yellow-500
                @elseif($booking->booking_status == 'confirmed') bg-blue-50 border-blue-500
                @elseif($booking->booking_status == 'completed') bg-green-50 border-green-500
                @elseif($booking->booking_status == 'cancelled') bg-red-50 border-red-500
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
                                    Action required: Review and confirm this booking
                                @elseif($booking->booking_status == 'confirmed')
                                    Event confirmed - Prepare for {{ $booking->event_date->format('M d, Y') }}
                                @elseif($booking->booking_status == 'completed')
                                    This event has been completed
                                @elseif($booking->booking_status == 'cancelled')
                                    This booking was cancelled
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if($booking->booking_status == 'pending')
                            <button type="button" onclick="showBookingModal('confirm'); return false;" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                Confirm Booking
                            </button>
                            <button type="button" onclick="showBookingModal('reject'); return false;" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                Reject
                            </button>
                        @endif

                        @if($booking->booking_status == 'confirmed' && $booking->event_date->isPast())
                            <button type="button" onclick="showBookingModal('complete'); return false;" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                Mark as Complete
                            </button>
                        @endif

                        @if($booking->booking_status == 'confirmed' && $booking->event_date->isFuture())
                            <div class="px-6 py-3 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                                Event in {{ $booking->event_date->diffForHumans() }}
                            </div>
                        @endif

                        <button onclick="window.print()" class="px-4 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Full Name</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->customer_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    <a href="mailto:{{ $booking->customer_email }}" class="text-green-600 hover:text-green-700">
                                        {{ $booking->customer_email }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Phone Number</label>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    <a href="tel:{{ $booking->customer_phone }}" class="text-green-600 hover:text-green-700">
                                        {{ $booking->customer_phone }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Booking Date</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="mailto:{{ $booking->customer_email }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email Customer
                            </a>
                            <a href="tel:{{ $booking->customer_phone }}" 
                               class="inline-flex items-center px-4 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Call Customer
                            </a>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Event Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Event Type</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_type }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Event Date</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->event_date->format('l, F d, Y') }}</p>
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
                                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $booking->special_instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Package & Menu Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Package: {{ $booking->package->name }}
                        </h3>

                        @php
                            $itemsByCategory = $booking->menuItems->groupBy('category.name');
                        @endphp

                        <div class="space-y-6">
                            @foreach($itemsByCategory as $categoryName => $items)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        {{ $categoryName ?? 'Uncategorized' }} ({{ $items->count() }} items)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($items as $item)
                                            <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <svg class="w-5 h-5 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</p>
                                                    @if($item->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->description }}</p>
                                                    @endif
                                                    <p class="text-sm text-green-600 mt-1">₱{{ number_format($item->price, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Receipt -->
                    @if($booking->receipt_path)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Receipt</h3>
                            <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <img src="{{ $booking->receipt_path }}" />
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
                                <span class="font-bold text-gray-900 dark:text-white text-xl">₱{{ number_format($booking->total_price + $booking->service_fee, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Deposit Received</span>
                                <span class="font-medium text-green-600">₱{{ number_format($booking->deposit_paid, 2) }}</span>
                            </div>
                            
                            @if($booking->payment_status == 'deposit_paid')
                                <div class="flex justify-between pt-2 border-t border-gray-300 dark:border-gray-600">
                                    <span class="font-semibold text-orange-600">Balance to Collect</span>
                                    <span class="font-bold text-orange-600 text-lg">₱{{ number_format($booking->balance, 2) }}</span>
                                </div>
                                <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                    <p class="text-xs text-yellow-800 dark:text-yellow-200">
                                        ⚠️ Balance payment due on or before event date
                                    </p>
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

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <button onclick="window.print()" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print Details
                            </button>
                            
                            <a href="mailto:{{ $booking->customer_email }}" 
                               class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email Customer
                            </a>
                            
                            <a href="tel:{{ $booking->customer_phone }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Call Customer
                            </a>
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

    <!-- Booking Action Modal -->
    <div id="bookingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center" style="backdrop-filter: blur(4px);">
        <div class="relative mx-auto p-0 w-full max-w-md">
            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 id="bookingModalTitle" class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Confirm Action
                        </h3>
                        <button onclick="closeBookingModal()" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <!-- Icon Container -->
                    <div class="flex justify-center mb-5">
                        <div id="bookingModalIconContainer" class="w-16 h-16 rounded-full flex items-center justify-center">
                            <svg id="bookingModalIcon" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="text-center mb-5">
                        <p id="bookingModalMessage" class="text-gray-600 dark:text-gray-400 mb-4">
                            Are you sure you want to perform this action?
                        </p>
                    </div>

                    <!-- Additional Content -->
                    <div id="bookingModalContent" class="space-y-3">
                        <!-- Dynamic content will be inserted here -->
                    </div>

                    <!-- Rejection Reason Input (Hidden by default) -->
                    <div id="rejectionReasonContainer" class="hidden mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea id="rejectionReasonInput" 
                                  rows="4" 
                                  placeholder="Please provide a reason for rejecting this booking..."
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100 transition-all resize-none"></textarea>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">This reason will be sent to the customer via email.</p>
                    </div>

                    <!-- Warning Box -->
                    <div id="bookingModalWarning" class="hidden mt-4 p-4 rounded-lg border-l-4">
                        <!-- Warning content will be inserted here -->
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 rounded-b-2xl flex items-center justify-end space-x-3">
                    <button onclick="closeBookingModal()" 
                            class="px-6 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border-2 border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-all">
                        Cancel
                    </button>
                    <button id="bookingConfirmButton" 
                            onclick="confirmBookingAction()" 
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg transition-all">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for Laravel -->
    <form id="confirmBookingForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <form id="rejectBookingForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="rejection_reason" id="rejectionReasonField">
    </form>

    <form id="completeBookingForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script>
        // Define functions globally so inline onclick handlers can access them
        let currentBookingAction = '';

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function showBookingModal(action) {
            console.log('showBookingModal called with action:', action);
            currentBookingAction = action;

            const modal = document.getElementById('bookingModal');
            const modalTitle = document.getElementById('bookingModalTitle');
            const modalMessage = document.getElementById('bookingModalMessage');
            const modalContent = document.getElementById('bookingModalContent');
            const modalIconContainer = document.getElementById('bookingModalIconContainer');
            const modalIcon = document.getElementById('bookingModalIcon');
            const modalWarning = document.getElementById('bookingModalWarning');
            const confirmButton = document.getElementById('bookingConfirmButton');
            const rejectionContainer = document.getElementById('rejectionReasonContainer');

            // Reset rejection reason
            document.getElementById('rejectionReasonInput').value = '';
            rejectionContainer.classList.add('hidden');
            modalWarning.classList.add('hidden');

            // Configure modal based on action
            switch(action) {
                case 'confirm':
                    modalTitle.textContent = 'Confirm Booking?';
                    modalMessage.textContent = 'The customer will receive an email confirmation and the booking will be marked as confirmed.';
                    
                    modalIconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-green-100 dark:bg-green-900';
                    modalIcon.className = 'w-10 h-10 text-green-600 dark:text-green-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    
                    confirmButton.className = 'px-6 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-lg shadow-green-600/30 transition-all';
                    confirmButton.textContent = 'Confirm Booking';
                    
                    modalContent.innerHTML = `
                        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Important</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">Make sure you have reviewed all booking details and are ready to fulfill this order.</p>
                                </div>
                            </div>
                        </div>
                    `;
                    break;

                case 'reject':
                    modalTitle.textContent = 'Reject Booking';
                    modalMessage.textContent = 'Please provide a reason for rejecting this booking. The customer will be notified via email.';
                    
                    modalIconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-red-100 dark:bg-red-900';
                    modalIcon.className = 'w-10 h-10 text-red-600 dark:text-red-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    
                    confirmButton.className = 'px-6 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-lg shadow-red-600/30 transition-all';
                    confirmButton.textContent = 'Reject Booking';
                    
                    rejectionContainer.classList.remove('hidden');
                    modalContent.innerHTML = '';
                    
                    modalWarning.classList.remove('hidden');
                    modalWarning.className = 'mt-4 p-4 rounded-lg border-l-4 bg-yellow-50 dark:bg-yellow-900/30 border-yellow-500';
                    modalWarning.innerHTML = `
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                This action cannot be undone. The customer will receive an email with your rejection reason.
                            </p>
                        </div>
                    `;
                    break;

                case 'complete':
                    modalTitle.textContent = 'Mark as Complete?';
                    modalMessage.textContent = 'Confirm that this event has been successfully completed and all services have been delivered.';
                    
                    modalIconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-blue-100 dark:bg-blue-900';
                    modalIcon.className = 'w-10 h-10 text-blue-600 dark:text-blue-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                    
                    confirmButton.className = 'px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-600/30 transition-all';
                    confirmButton.textContent = 'Mark as Complete';
                    
                    modalContent.innerHTML = `
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Event successfully delivered</span>
                            </div>
                            <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">All payments collected</span>
                            </div>
                            <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Customer satisfied</span>
                            </div>
                        </div>
                    `;
                    break;
            }

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            const modal = document.getElementById('bookingModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmBookingAction() {
            let form;
            
            switch(currentBookingAction) {
                case 'confirm':
                    form = document.getElementById('confirmBookingForm');
                    form.action = '{{ route("caterer.booking.confirm", $booking->id) }}';
                    break;
                    
                case 'reject':
                    const rejectionReason = document.getElementById('rejectionReasonInput').value.trim();
                    if (!rejectionReason) {
                        alert('Please provide a rejection reason');
                        return;
                    }
                    form = document.getElementById('rejectBookingForm');
                    document.getElementById('rejectionReasonField').value = rejectionReason;
                    form.action = '{{ route("caterer.booking.reject", $booking->id) }}';
                    break;
                    
                case 'complete':
                    form = document.getElementById('completeBookingForm');
                    form.action = '{{ route("caterer.booking.complete", $booking->id) }}';
                    break;
            }
            
            if (form) {
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('bookingModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBookingModal();
                closeImageModal();
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer.bookings') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Booking Details — {{ $booking->booking_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ── Status Banner ── --}}
            <div class="mb-6 p-6 rounded-xl border-2
                @if($booking->booking_status == 'pending')   bg-yellow-50 border-yellow-500
                @elseif($booking->booking_status == 'confirmed') bg-blue-50 border-blue-500
                @elseif($booking->booking_status == 'completed') bg-green-50 border-green-500
                @elseif($booking->booking_status == 'cancelled') bg-red-50 border-red-500
                @endif">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4
                            @if($booking->booking_status == 'pending')   bg-yellow-500
                            @elseif($booking->booking_status == 'confirmed') bg-blue-500
                            @elseif($booking->booking_status == 'completed') bg-green-500
                            @elseif($booking->booking_status == 'cancelled') bg-red-500
                            @endif">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($booking->booking_status == 'pending')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @elseif($booking->booking_status == 'confirmed')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @elseif($booking->booking_status == 'completed')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"/>
                                @elseif($booking->booking_status == 'cancelled')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold
                                @if($booking->booking_status == 'pending')   text-yellow-900
                                @elseif($booking->booking_status == 'confirmed') text-blue-900
                                @elseif($booking->booking_status == 'completed') text-green-900
                                @elseif($booking->booking_status == 'cancelled') text-red-900
                                @endif">
                                Booking {{ ucfirst($booking->booking_status) }}
                            </h3>
                            <p class="text-sm
                                @if($booking->booking_status == 'pending')   text-yellow-700
                                @elseif($booking->booking_status == 'confirmed') text-blue-700
                                @elseif($booking->booking_status == 'completed') text-green-700
                                @elseif($booking->booking_status == 'cancelled') text-red-700
                                @endif">
                                @if($booking->booking_status == 'pending')
                                    Waiting for the caterer to confirm your booking.
                                @elseif($booking->booking_status == 'confirmed')
                                    Your event is confirmed for {{ $booking->event_date->format('M d, Y') }}.
                                @elseif($booking->booking_status == 'completed')
                                    This event has been completed. We hope it went well!
                                @elseif($booking->booking_status == 'cancelled')
                                    This booking was cancelled.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Customer action buttons --}}
                    <div class="flex flex-wrap gap-2">
                        {{-- Pay Balance --}}
                        @if($booking->booking_status == 'confirmed' && $booking->payment_status == 'deposit_paid')
                        <a href="{{ route('customer.booking.pay-balance', $booking->id) }}"
                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                            💳 Pay Balance
                        </a>
                        @endif

                        {{-- Cancel (only pending bookings) --}}
                        @if($booking->booking_status == 'pending')
                        <button type="button" onclick="openCustomerCancelModal()"
                            class="px-5 py-2.5 bg-red-50 border-2 border-red-300 text-red-700 rounded-lg hover:bg-red-100 transition-colors font-semibold text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Booking
                        </button>
                        @endif

                        {{-- Leave a Review --}}
                        @if($booking->booking_status == 'completed' && !$booking->review)
                        <a href="{{ route('customer.review.create', $booking->id) }}"
                            class="px-5 py-2.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-semibold text-sm">
                            ⭐ Leave a Review
                        </a>
                        @endif

                        {{-- Print --}}
                        <button onclick="window.print()"
                            class="px-4 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Main Content ── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Caterer Info --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Caterer
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Business Name</label>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $booking->caterer->business_name ?? $booking->caterer->name }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Contact</label>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $booking->caterer->phone ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $booking->event_date->format('l, F d, Y') }}
                                </p>
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
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                                    {{ $booking->special_instructions }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Package & Menu Items --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Package: {{ $booking->package->name }}
                        </h3>

                        @php $itemsByCategory = $booking->menuItems->groupBy('category.name'); @endphp

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
                                        <svg class="w-5 h-5 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"/>
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

                    {{-- Payment Receipts --}}
                    @php
                        $receiptUrl = $booking->receipt_path
                            ? (str_starts_with($booking->receipt_path, 'http')
                                ? $booking->receipt_path
                                : Storage::disk('public')->url($booking->receipt_path))
                            : null;

                        $balanceReceiptUrl = $booking->balance_receipt_path
                            ? (str_starts_with($booking->balance_receipt_path, 'http')
                                ? $booking->balance_receipt_path
                                : Storage::disk('public')->url($booking->balance_receipt_path))
                            : null;
                    @endphp

                    @if($receiptUrl)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Deposit Receipt</h3>
                        <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            @if(str_ends_with(strtolower(parse_url($receiptUrl, PHP_URL_PATH)), '.pdf'))
                                <a href="{{ $receiptUrl }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    View PDF Receipt
                                </a>
                            @else
                                <img src="{{ $receiptUrl }}" alt="Deposit Receipt"
                                     class="max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal(this.src)"
                                     onerror="this.closest('.border-2').innerHTML='<p class=\'text-sm text-red-500 p-2\'>Receipt image could not be loaded.</p>'">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Click to view full size</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($balanceReceiptUrl)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Balance Payment Receipt</h3>
                        <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            @if(str_ends_with(strtolower(parse_url($balanceReceiptUrl, PHP_URL_PATH)), '.pdf'))
                                <a href="{{ $balanceReceiptUrl }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    View PDF Receipt
                                </a>
                            @else
                                <img src="{{ $balanceReceiptUrl }}" alt="Balance Payment Receipt"
                                     class="max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal(this.src)"
                                     onerror="this.closest('.border-2').innerHTML='<p class=\'text-sm text-red-500 p-2\'>Receipt image could not be loaded.</p>'">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Click to view full size</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ── Sidebar ── --}}
                <div class="space-y-6">

                    {{-- Payment Summary --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Price per head</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ₱{{ number_format($booking->price_per_head, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Guests</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ₱{{ number_format($booking->total_price, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Service fee</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ₱{{ number_format($booking->service_fee, 2) }}
                                </span>
                            </div>

                            <div class="border-t border-gray-300 dark:border-gray-600 my-3"></div>

                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900 dark:text-white">Total Amount</span>
                                <span class="font-bold text-gray-900 dark:text-white text-xl">
                                    ₱{{ number_format($booking->total_price + $booking->service_fee, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Deposit Paid</span>
                                <span class="font-medium text-green-600">
                                    ₱{{ number_format($booking->deposit_paid, 2) }}
                                </span>
                            </div>

                            @if($booking->payment_status == 'deposit_paid')
                            <div class="flex justify-between pt-2 border-t border-gray-300 dark:border-gray-600">
                                <span class="font-semibold text-orange-600">Remaining Balance</span>
                                <span class="font-bold text-orange-600 text-lg">
                                    ₱{{ number_format($booking->balance, 2) }}
                                </span>
                            </div>
                            <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                <p class="text-xs text-yellow-800 dark:text-yellow-200">
                                    ⚠️ Balance must be paid on or before the event date.
                                </p>
                            </div>
                            @if($booking->booking_status == 'confirmed')
                            <a href="{{ route('customer.booking.pay-balance', $booking->id) }}"
                                class="mt-2 w-full flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                💳 Pay Remaining Balance
                            </a>
                            @endif
                            @else
                            <div class="pt-2 border-t border-gray-300 dark:border-gray-600">
                                <span class="flex items-center text-green-600 font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Fully Paid
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Payment Method:</strong>
                                {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                            </p>
                        </div>
                    </div>

                    {{-- Your Review Section --}}
                    @if($booking->review && $booking->review->isVisibleToCustomer())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Your Review
                        </h3>
                        
                        <div class="space-y-3">
                            {{-- Rating --}}
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                                <div class="flex items-center gap-1 text-2xl">
                                    {!! $booking->review->starDisplay !!}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $booking->review->rating }}/5 Stars</p>
                            </div>
                            
                            {{-- Status --}}
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($booking->review->admin_status === 'approved') bg-green-100 text-green-800
                                    @elseif($booking->review->admin_status === 'under_review') bg-yellow-100 text-yellow-800
                                    @elseif($booking->review->admin_status === 'flagged') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $booking->review->statusText }}
                                </span>
                            </div>
                            
                            {{-- Your Comment --}}
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Comment</label>
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    {{ trim($booking->review->comment) }}
                                </p>
                            </div>
                            
                            {{-- Caterer Response --}}
                            @if($booking->review->caterer_response)
                            <div>
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                    Caterer Response 
                                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">New</span>
                                </label>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                        {{ $booking->review->caterer_response }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Responded on {{ $booking->review->responded_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Cancellation / Refund Info (read-only for customer) --}}
                    @if($booking->booking_status === 'cancelled')
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="px-5 py-4 bg-red-600 flex items-center gap-3">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <div>
                                <p class="text-white font-bold text-sm">Booking Cancelled</p>
                                @if($booking->cancelled_by)
                                <p class="text-red-100 text-xs">
                                    by {{ ucfirst($booking->cancelled_by) }}
                                    @if($booking->cancelled_at) · {{ $booking->cancelled_at->format('M d, Y') }} @endif
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            @if($booking->cancellation_reason)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Reason</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $booking->cancellation_reason }}</p>
                            </div>
                            @endif

                            @if($booking->deposit_paid > 0)
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                    Refund Status — ₱{{ number_format($booking->deposit_paid, 2) }} deposit
                                </p>
                                @php $refundStatus = $booking->refund_status ?? 'none'; @endphp
                                @if($refundStatus === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        ⏳ Refund Pending — the caterer will contact you
                                    </span>
                                @elseif($refundStatus === 'issued')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        ✓ Refund Sent
                                    </span>
                                @elseif($refundStatus === 'waived')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                        Waived
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                        No refund
                                    </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Booking Timeline --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Booking Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Booking Submitted</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $booking->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            @if($booking->booking_status !== 'pending')
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        Status: {{ ucfirst($booking->booking_status) }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $booking->updated_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-start">
                                <div class="w-2 h-2 {{ $booking->event_date->isPast() ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mt-2 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Event Date</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $booking->event_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
        onclick="closeImageModal()">
        <img id="modalImage" src="" alt="Receipt" class="max-w-full max-h-full object-contain">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ── Customer Cancel Modal ── --}}
    <div id="customerCancelModal"
        class="hidden fixed inset-0 bg-gray-900/75 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Cancel Your Booking
                </h3>
                <button onclick="closeCustomerCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">
                        ⚠️ This action cannot be undone. You can only cancel bookings that have not yet been confirmed.
                    </p>
                </div>

                @if($booking->deposit_paid > 0)
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl">
                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">
                        💰 You paid a deposit of ₱{{ number_format($booking->deposit_paid, 2) }}
                    </p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                        Please provide your GCash or bank details below so the caterer can arrange your refund.
                    </p>
                </div>
                @endif

                <form id="customerCancelForm" method="POST"
                      action="{{ route('customer.booking.cancel-booking', $booking->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Reason for cancellation <span class="text-red-500">*</span>
                            </label>
                            <textarea id="customerCancelReason" name="cancellation_reason" rows="3"
                                placeholder="Please tell us why you're cancelling…"
                                class="w-full px-3 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-400 focus:border-red-400 resize-none"></textarea>
                            <p id="customerCancelError" class="hidden mt-1 text-xs text-red-600">
                                Please provide at least 10 characters.
                            </p>
                        </div>

                        @if($booking->deposit_paid > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                GCash / Bank details for refund
                            </label>
                            <textarea name="refund_details" rows="2"
                                placeholder="e.g. GCash: 09XX-XXX-XXXX (Juan Dela Cruz)"
                                class="w-full px-3 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 resize-none"></textarea>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-2xl flex justify-end gap-3">
                <button onclick="closeCustomerCancelModal()"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Go Back
                </button>
                <button onclick="submitCustomerCancellation()"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel Booking
                </button>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
        function openCustomerCancelModal() {
            document.getElementById('customerCancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeCustomerCancelModal() {
            document.getElementById('customerCancelModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function submitCustomerCancellation() {
            const reason = document.getElementById('customerCancelReason').value.trim();
            const errorEl = document.getElementById('customerCancelError');
            if (reason.length < 10) {
                errorEl.classList.remove('hidden');
                document.getElementById('customerCancelReason').focus();
                return;
            }
            errorEl.classList.add('hidden');
            document.getElementById('customerCancelForm').submit();
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCustomerCancelModal();
                closeImageModal();
            }
        });
    </script>
</x-app-layout>
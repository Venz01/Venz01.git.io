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
                            <button onclick="cancelBooking()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Cancel Booking
                            </button>
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
                                <img src="{{ asset('storage/' . $booking->receipt_path) }}" 
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

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('customer.booking.cancel-booking', $booking->id) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        @media print {
            header, nav, .no-print, button {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
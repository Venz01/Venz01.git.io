<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $bookings->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Confirmed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Confirmed</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Completed</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('customer.bookings') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Payments</option>
                            <option value="deposit_paid" {{ request('payment_status') == 'deposit_paid' ? 'selected' : '' }}>Deposit Paid</option>
                            <option value="fully_paid" {{ request('payment_status') == 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Booking number, caterer..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ route('customer.bookings') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bookings List -->
            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="p-6">
                                <!-- Header -->
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                                    <div class="mb-4 lg:mb-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $booking->booking_number }}
                                            </h3>
                                            
                                            <!-- Status Badges -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($booking->booking_status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->booking_status == 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->booking_status == 'completed') bg-green-100 text-green-800
                                                @elseif($booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($booking->payment_status == 'deposit_paid') bg-yellow-100 text-yellow-800
                                                @elseif($booking->payment_status == 'fully_paid') bg-green-100 text-green-800
                                                @endif">
                                                {{ $booking->payment_status == 'deposit_paid' ? 'Deposit Paid' : 'Fully Paid' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Booked on {{ $booking->created_at->format('F d, Y') }}
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('customer.booking.details', $booking->id) }}" 
                                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                            View Details
                                        </a>
                                        
                                        @if($booking->booking_status == 'pending')
                                            <button onclick="cancelBooking({{ $booking->id }})"
                                                    class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium">
                                                Cancel Booking
                                            </button>
                                        @endif

                                        @if($booking->payment_status == 'deposit_paid' && $booking->booking_status == 'confirmed')
                                            <a href="{{ route('customer.booking.pay-balance', $booking->id) }}"
                                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                Pay Balance
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Event Details -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Event Details</h4>
                                        
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->event_type }}</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->event_date->format('F d, Y') }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $booking->time_slot }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Guests</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->guests }} people</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Venue</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Caterer Info -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Caterer</h4>
                                        
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                                                {{ substr($booking->caterer->business_name ?? $booking->caterer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->caterer->business_name ?? $booking->caterer->name }}
                                                </p>
                                                @if($booking->caterer->contact_number)
                                                    <a href="tel:{{ $booking->caterer->contact_number }}" 
                                                       class="text-sm text-green-600 hover:text-green-700">
                                                        {{ $booking->caterer->contact_number }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Package</p>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->package->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->menuItems->count() }} items</p>
                                        </div>
                                    </div>

                                    <!-- Payment Info -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Payment Summary</h4>
                                        
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Total Amount</span>
                                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($booking->total_price + $booking->service_fee, 2) }}</span>
                                            </div>
                                            
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Deposit Paid</span>
                                                <span class="font-medium text-green-600">₱{{ number_format($booking->deposit_paid, 2) }}</span>
                                            </div>
                                            
                                            @if($booking->payment_status == 'deposit_paid')
                                                <div class="flex justify-between text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                                                    <span class="text-gray-900 dark:text-white font-semibold">Balance Due</span>
                                                    <span class="font-bold text-orange-600">₱{{ number_format($booking->balance, 2) }}</span>
                                                </div>
                                            @else
                                                <div class="flex justify-between text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                                                    <span class="text-green-600 font-semibold">✓ Fully Paid</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($booking->booking_status == 'confirmed' && $booking->event_date->isFuture())
                                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                                <p class="text-xs text-blue-800 dark:text-blue-200">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Event in {{ $booking->event_date->diffForHumans() }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">No bookings found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        @if(request()->hasAny(['status', 'payment_status', 'search']))
                            No bookings match your filters. Try adjusting your search criteria.
                        @else
                            You haven't made any bookings yet. Start exploring caterers and create your first booking!
                        @endif
                    </p>
                    <a href="{{ route('customer.caterers') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse Caterers
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Cancel Booking</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to cancel this booking? This action cannot be undone.
            </p>
            <form id="cancelForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for cancellation (optional)
                    </label>
                    <textarea 
                        name="cancellation_reason"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Please let us know why you're cancelling..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Keep Booking
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Yes, Cancel Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function cancelBooking(bookingId) {
            const modal = document.getElementById('cancelModal');
            const form = document.getElementById('cancelForm');
            form.action = `/customer/bookings/${bookingId}/cancel`;
            modal.classList.remove('hidden');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
</x-app-layout>
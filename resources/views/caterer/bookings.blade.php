<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Pending Approval -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Approval</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Requires action</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Confirmed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Confirmed</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Upcoming events</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Completed</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">This month</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Revenue</p>
                            <p class="text-3xl font-bold text-purple-600">₱{{ number_format($stats['revenue'], 0) }}</p>
                            <p class="text-xs text-gray-500 mt-1">This month</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button 
                            onclick="switchTab('pending')" 
                            id="tab-pending"
                            class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-yellow-500 text-yellow-600">
                            Pending ({{ $stats['pending'] }})
                        </button>
                        <button 
                            onclick="switchTab('confirmed')" 
                            id="tab-confirmed"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Confirmed ({{ $stats['confirmed'] }})
                        </button>
                        <button 
                            onclick="switchTab('completed')" 
                            id="tab-completed"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Completed ({{ $stats['completed'] }})
                        </button>
                        <button 
                            onclick="switchTab('cancelled')" 
                            id="tab-cancelled"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Cancelled ({{ $stats['cancelled'] }})
                        </button>
                        <button 
                            onclick="switchTab('all')" 
                            id="tab-all"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            All Bookings
                        </button>
                    </nav>
                </div>

                <!-- Filters -->
                <div class="p-6">
                    <form method="GET" action="{{ route('caterer.bookings') }}" class="flex flex-wrap gap-4">
                        <input type="hidden" name="tab" id="filterTab" value="{{ request('tab', 'pending') }}">
                        
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Booking number, customer name..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                            <input 
                                type="date" 
                                name="date_from" 
                                value="{{ request('date_from') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Apply
                            </button>
                            <a href="{{ route('caterer.bookings') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bookings List -->
            <div id="bookings-container">
                @if($bookings->count() > 0)
                    <div class="space-y-6">
                        @foreach($bookings as $booking)
                            <div class="booking-card bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow" data-status="{{ $booking->booking_status }}">
                                <!-- Header Bar -->
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $booking->booking_number }}
                                            </h3>
                                            
                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($booking->booking_status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->booking_status == 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->booking_status == 'completed') bg-green-100 text-green-800
                                                @elseif($booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>

                                            <!-- Payment Badge -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($booking->payment_status == 'deposit_paid') bg-yellow-100 text-yellow-800
                                                @elseif($booking->payment_status == 'fully_paid') bg-green-100 text-green-800
                                                @endif">
                                                {{ $booking->payment_status == 'deposit_paid' ? 'Deposit Paid' : 'Fully Paid' }}
                                            </span>

                                            <!-- Urgency Indicator -->
                                            @if($booking->booking_status == 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Action Required
                                                </span>
                                            @endif

                                            @if($booking->booking_status == 'confirmed' && $booking->event_date->isToday())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    📅 Event Today!
                                                </span>
                                            @elseif($booking->booking_status == 'confirmed' && $booking->event_date->isTomorrow())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    📅 Event Tomorrow
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('caterer.booking.details', $booking->id) }}" 
                                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                                View Details
                                            </a>

                                            @if($booking->booking_status == 'pending')
                                                <button onclick="confirmBooking({{ $booking->id }})" 
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                    Confirm
                                                </button>
                                                <button onclick="rejectBooking({{ $booking->id }})" 
                                                        class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium">
                                                    Reject
                                                </button>
                                            @endif

                                            @if($booking->booking_status == 'confirmed' && $booking->event_date->isPast())
                                                <button onclick="markComplete({{ $booking->id }})" 
                                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                                    Mark Complete
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                        <!-- Customer Info -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Customer
                                            </h4>
                                            
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->customer_name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->customer_email }}</p>
                                                <a href="tel:{{ $booking->customer_phone }}" 
                                                   class="text-sm text-green-600 hover:text-green-700 flex items-center mt-1">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    {{ $booking->customer_phone }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Event Details -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Event Details
                                            </h4>
                                            
                                            <div class="space-y-2">
                                                <div>
                                                    <p class="text-xs text-gray-500">Type</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->event_type }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Date & Time</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $booking->event_date->format('M d, Y') }}
                                                    </p>
                                                    <p class="text-xs text-gray-600">{{ $booking->time_slot }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Guests</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->guests }} people</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Package & Menu -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                Package
                                            </h4>
                                            
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->package->name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->menuItems->count() }} items</p>
                                                <p class="text-xs text-gray-500 mt-2">
                                                    ₱{{ number_format($booking->price_per_head, 2) }} per head
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-xs text-gray-500">Venue</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</p>
                                            </div>
                                        </div>

                                        <!-- Payment Info -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Payment
                                            </h4>
                                            
                                            <div class="space-y-2">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Total</span>
                                                    <span class="font-medium">₱{{ number_format($booking->total_price + $booking->service_fee, 0) }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Deposit</span>
                                                    <span class="font-medium text-green-600">₱{{ number_format($booking->deposit_paid, 0) }}</span>
                                                </div>
                                                @if($booking->payment_status == 'deposit_paid')
                                                    <div class="flex justify-between text-sm pt-2 border-t">
                                                        <span class="font-medium text-orange-600">Balance</span>
                                                        <span class="font-bold text-orange-600">₱{{ number_format($booking->balance, 0) }}</span>
                                                    </div>
                                                @else
                                                    <div class="pt-2 border-t">
                                                        <span class="text-sm text-green-600 font-medium">✓ Fully Paid</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <a href="{{ asset('storage/' . $booking->receipt_path) }}" 
                                                   target="_blank"
                                                   class="text-sm text-blue-600 hover:text-blue-700 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Receipt
                                                </a>
                                            </div>
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
                            No bookings match your current filter. Try switching tabs or adjusting your search.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirm Booking Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Confirm Booking</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to confirm this booking? The customer will be notified.
            </p>
            <form id="confirmForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirmation Message (optional)
                    </label>
                    <textarea 
                        name="confirmation_message"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Thank you for booking with us! We're excited to cater your event..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeConfirmModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Booking Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reject Booking</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Please provide a reason for rejecting this booking. The customer will be notified.
            </p>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection *
                    </label>
                    <textarea 
                        name="rejection_reason"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Please explain why you cannot accept this booking..."
                        required
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Reject Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mark Complete Modal -->
    <div id="completeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Mark as Complete</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Confirm that this event has been successfully completed.
            </p>
            <form id="completeForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (optional)
                    </label>
                    <textarea 
                        name="completion_notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Any notes about the completed event..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCompleteModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Mark Complete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab switching
        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-yellow-500', 'text-yellow-600', 'border-blue-500', 'text-blue-600', 'border-green-500', 'text-green-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeTab = document.getElementById(`tab-${tab}`);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('active');

            // Set color based on tab
            if (tab === 'pending') {
                activeTab.classList.add('border-yellow-500', 'text-yellow-600');
            } else if (tab === 'confirmed') {
                activeTab.classList.add('border-blue-500', 'text-blue-600');
            } else if (tab === 'completed') {
                activeTab.classList.add('border-green-500', 'text-green-600');
            } else {
                activeTab.classList.add('border-green-500', 'text-green-600');
            }

            // Update hidden input for form
            document.getElementById('filterTab').value = tab;

            // Filter bookings
            const bookingCards = document.querySelectorAll('.booking-card');
            bookingCards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (tab === 'all' || status === tab) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }

        // Confirm booking modal
        function confirmBooking(bookingId) {
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('confirmForm');
            form.action = `/caterer/bookings/${bookingId}/confirm`;
            modal.classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Reject booking modal
        function rejectBooking(bookingId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = `/caterer/bookings/${bookingId}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Mark complete modal
        function markComplete(bookingId) {
            const modal = document.getElementById('completeModal');
            const form = document.getElementById('completeForm');
            form.action = `/caterer/bookings/${bookingId}/complete`;
            modal.classList.remove('hidden');
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Initialize tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'pending';
            switchTab(tab);
        });
    </script>

    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>
</x-app-layout>
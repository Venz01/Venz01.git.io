<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payments & Revenue') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                $catererId = auth()->id();
                $allBookings = \App\Models\Booking::where('caterer_id', $catererId)->get();
                $confirmedCompletedBookings = $allBookings->whereIn('booking_status', ['confirmed', 'completed']);
                $totalRevenue = $confirmedCompletedBookings->sum('total_price');
                $pendingBookings = $allBookings->where('booking_status', 'pending');
                $pendingRevenue = $pendingBookings->sum('total_price');
                $thisMonthRevenue = $allBookings->filter(function($booking) {
                    return in_array($booking->booking_status, ['confirmed', 'completed']) 
                        && $booking->event_date->month == now()->month
                        && $booking->event_date->year == now()->year;
                })->sum('total_price');
                $depositsPending = $allBookings->where('payment_status', 'deposit_paid')->count();
                $bookings = \App\Models\Booking::where('caterer_id', $catererId)
                    ->with('customer')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            {{-- Revenue Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Revenue</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">
                                        ₱{{ number_format($totalRevenue, 2) }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">This Month</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">
                                        ₱{{ number_format($thisMonthRevenue, 2) }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">
                                        ₱{{ number_format($pendingRevenue, 2) }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Balance Pending</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $depositsPending }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Transactions --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payment Transactions</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage all payment transactions</p>
                </div>
                
                @if($bookings->count() > 0)
                    {{-- Desktop Table View (hidden on mobile) --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Booking #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Event Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Payment
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $booking->booking_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->customer_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->event_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        ₱{{ number_format($booking->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->payment_status === 'fully_paid')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Fully Paid
                                            </span>
                                        @elseif($booking->payment_status === 'deposit_paid')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Deposit Paid
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->booking_status === 'confirmed')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Confirmed
                                            </span>
                                        @elseif($booking->booking_status === 'pending')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Pending
                                            </span>
                                        @elseif($booking->booking_status === 'completed')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('caterer.booking.details', $booking->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (shown on mobile/tablet) --}}
                    <div class="lg:hidden divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($bookings as $booking)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $booking->booking_number }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $booking->customer_name }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-1.5">
                                    @if($booking->payment_status === 'fully_paid')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Fully Paid
                                        </span>
                                    @elseif($booking->payment_status === 'deposit_paid')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Deposit
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Pending
                                        </span>
                                    @endif
                                    
                                    @if($booking->booking_status === 'confirmed')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Confirmed
                                        </span>
                                    @elseif($booking->booking_status === 'pending')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Pending
                                        </span>
                                    @elseif($booking->booking_status === 'completed')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Cancelled
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm mb-3">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ $booking->event_date->format('M d, Y') }}
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format($booking->total_price, 2) }}
                                </span>
                            </div>
                            
                            <a href="{{ route('caterer.booking.details', $booking->id) }}" 
                               class="block w-full text-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 font-medium text-sm transition-colors">
                                View Details
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 sm:p-12 text-center">
                        <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="mt-4 text-base sm:text-lg font-medium text-gray-900 dark:text-gray-100">No Transactions Yet</h3>
                        <p class="mt-2 text-sm sm:text-base text-gray-500 dark:text-gray-400">You haven't received any bookings yet.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
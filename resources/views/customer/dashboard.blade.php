<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Section --}}
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">
                            Welcome back, {{ auth()->user()->name }}!
                        </h3>
                        <p class="text-indigo-100 mt-2">
                            Ready to plan your next event? Browse our caterers and make a booking.
                        </p>
                    </div>
                    <a href="{{ route('customer.caterers') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                        Browse Caterers
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            @php
                $allBookings = \App\Models\Booking::where('customer_id', auth()->id())->get();
                $totalBookings = $allBookings->count();
                $pendingBookings = $allBookings->where('booking_status', 'pending')->count();
                $confirmedBookings = $allBookings->where('booking_status', 'confirmed')->count();
                $upcomingBookings = \App\Models\Booking::where('customer_id', auth()->id())
                    ->whereIn('booking_status', ['confirmed', 'pending'])
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date', 'asc')
                    ->get();
            @endphp

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-6">
                {{-- Total Bookings --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Bookings</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $totalBookings }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $pendingBookings }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Confirmed --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Confirmed</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $confirmedBookings }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Events --}}
            @if($upcomingBookings->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($upcomingBookings as $booking)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex flex-col items-center justify-center">
                                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ $booking->event_date->format('M') }}</span>
                                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $booking->event_date->format('d') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->event_type }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->caterer->business_name ?? $booking->caterer->name }} • {{ $booking->guests }} guests
                                        </p>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->booking_status === 'confirmed') bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->event_date->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('customer.booking.details', $booking->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-center">
                    <a href="{{ route('customer.bookings') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        View All Bookings →
                    </a>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No Upcoming Events</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Start planning your next event by browsing our caterers.</p>
                <div class="mt-6">
                    <a href="{{ route('customer.caterers') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Browse Caterers
                    </a>
                </div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('customer.caterers') }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3 group-hover:bg-indigo-200 transition">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Browse Caterers</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Find the perfect caterer</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.bookings') }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3 group-hover:bg-green-200 transition">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">My Bookings</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Manage your events</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3 group-hover:bg-purple-200 transition">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">My Profile</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Update your info</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.payments') }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3 group-hover:bg-yellow-200 transition">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Payments</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Payment history</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
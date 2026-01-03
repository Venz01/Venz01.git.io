<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @php
                $bookings = \App\Models\Booking::where('customer_id', auth()->id())
                    ->with('caterer')
                    ->orderBy('updated_at', 'desc')
                    ->limit(20)
                    ->get();
            @endphp

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Activity</h3>
                </div>

                @if($bookings->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($bookings as $booking)
                    <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if($booking->booking_status === 'confirmed')
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @elseif($booking->booking_status === 'pending')
                                    <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @elseif($booking->booking_status === 'cancelled')
                                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Booking #{{ $booking->booking_number }} 
                                    @if($booking->booking_status === 'confirmed')
                                        has been confirmed
                                    @elseif($booking->booking_status === 'pending')
                                        is pending approval
                                    @elseif($booking->booking_status === 'cancelled')
                                        was cancelled
                                    @elseif($booking->booking_status === 'completed')
                                        is completed
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->caterer->business_name ?? $booking->caterer->name }} • {{ $booking->event_type }} • {{ $booking->event_date->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $booking->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('customer.booking.details', $booking->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No Notifications</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
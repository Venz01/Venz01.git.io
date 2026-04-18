<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Hero --}}
            <div class="px-2 sm:px-0 mb-8">
                <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 rounded-3xl p-8 text-white overflow-hidden">
                    {{-- Decorative blobs --}}
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="absolute -bottom-16 -left-8 w-80 h-80 bg-black/10 rounded-full pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-3xl lg:text-4xl font-bold tracking-tight mb-2">
                                Welcome back, {{ auth()->user()->name }}!
                            </h3>
                            <p class="text-green-100 text-base lg:text-lg">
                                Ready to plan your next event? Browse our packages and make a booking.
                            </p>
                        </div>
                        <a href="{{ route('customer.caterers') }}"
                            class="shrink-0 bg-white text-green-700 px-6 py-3.5 rounded-xl font-semibold hover:bg-green-50 active:bg-green-100 transition-colors shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse Packages
                        </a>
                    </div>
                </div>
            </div>

            @php
                $allBookings = \App\Models\Booking::where('customer_id', auth()->id())->get();
                $totalBookings = $allBookings->count();
                $pendingBookings = $allBookings->where('booking_status', 'pending')->count();
                $confirmedBookings = $allBookings->where('booking_status', 'confirmed')->count();

                $allOrders = \App\Models\Order::where('customer_id', auth()->id())->get();
                $totalOrders = $allOrders->count();
                $pendingOrders = $allOrders->where('order_status', 'pending')->count();
                $activeOrders = $allOrders->whereIn('order_status', ['confirmed','preparing','ready'])->count();

                $upcomingBookings = \App\Models\Booking::where('customer_id', auth()->id())
                    ->whereIn('booking_status', ['confirmed', 'pending'])
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date', 'asc')
                    ->get();

                $upcomingOrders = \App\Models\Order::where('customer_id', auth()->id())
                    ->whereIn('order_status', ['confirmed', 'pending', 'preparing', 'ready'])
                    ->where('fulfillment_date', '>=', now())
                    ->orderBy('fulfillment_date', 'asc')
                    ->get();
            @endphp

            {{-- Quick Stats --}}
            <div class="px-2 sm:px-0 mb-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Total Bookings --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/40 rounded-xl p-3">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBookings }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pending Bookings --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl p-3">
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Bookings</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingBookings }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Orders --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl p-3">
                                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Active Orders --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 bg-teal-100 dark:bg-teal-900/40 rounded-xl p-3">
                                    <svg class="h-6 w-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Orders</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeOrders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Events --}}
            @if($upcomingBookings->count() > 0)
            <div class="px-2 sm:px-0 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400">
                            {{ $upcomingBookings->count() }} upcoming
                        </span>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($upcomingBookings as $booking)
                        <div class="px-6 py-4 hover:bg-green-50/40 dark:hover:bg-green-900/10 transition-colors">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex flex-col items-center justify-center shadow-sm">
                                        <span class="text-[10px] text-white/80 font-semibold uppercase tracking-wider leading-none">{{ $booking->event_date->format('M') }}</span>
                                        <span class="text-lg font-extrabold text-white leading-tight">{{ $booking->event_date->format('d') }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $booking->event_type }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $booking->caterer->business_name ?? $booking->caterer->name }} · {{ $booking->guests }} guests
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                                @elseif($booking->booking_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                                                @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $booking->event_date->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('customer.booking.details', $booking->id) }}"
                                    class="shrink-0 inline-flex items-center gap-1 text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-center border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('customer.bookings') }}" class="text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                            View All Bookings →
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="px-2 sm:px-0 mb-6"> 
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Upcoming Events</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Start planning your next event by browsing our packages.</p>
                    <a href="{{ route('customer.caterers') }}"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-6 py-3 rounded-xl font-semibold transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse Packages
                    </a>
                </div>
            </div>
            @endif

            {{-- Upcoming Orders --}}
            @if($upcomingOrders->count() > 0)
            <div class="px-2 sm:px-0 mb-8"> 
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Orders</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">
                            {{ $upcomingOrders->count() }} active
                        </span>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($upcomingOrders as $order)
                        <div class="px-6 py-4 hover:bg-green-50/40 dark:hover:bg-green-900/10 transition-colors">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex flex-col items-center justify-center shadow-sm">
                                        <span class="text-[10px] text-white/80 font-semibold uppercase tracking-wider leading-none">{{ \Carbon\Carbon::parse($order->fulfillment_date)->format('M') }}</span>
                                        <span class="text-lg font-extrabold text-white leading-tight">{{ \Carbon\Carbon::parse($order->fulfillment_date)->format('d') }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $order->order_number }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $order->caterer->business_name ?? $order->caterer->name }}
                                            · {{ ucfirst($order->fulfillment_type) }}
                                            · ₱{{ number_format($order->total_amount, 2) }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($order->order_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                                @elseif($order->order_status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                                @elseif($order->order_status === 'preparing') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                                                @elseif($order->order_status === 'ready') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                                @endif">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->fulfillment_date)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('customer.orders.show', $order->id) }}"
                                    class="shrink-0 inline-flex items-center gap-1 text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-center border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('customer.orders.index') }}" class="text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                            View All Orders →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="px-2 sm:px-0">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <a href="{{ route('customer.caterers') }}"
                        class="group bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-all hover:-translate-y-0.5 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/40 rounded-xl p-3 group-hover:bg-green-200 dark:group-hover:bg-green-900/60 transition-colors">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-green-700 dark:group-hover:text-green-400 transition-colors">Browse Caterers</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Find the perfect caterer</p>
                        </div>
                    </a>

                    <a href="{{ route('customer.bookings') }}"
                        class="group bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-all hover:-translate-y-0.5 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl p-3 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-900/60 transition-colors">
                            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">My Bookings</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Manage your events</p>
                        </div>
                    </a>

                    <a href="{{ route('customer.orders.index') }}"
                        class="group bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-all hover:-translate-y-0.5 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-teal-100 dark:bg-teal-900/40 rounded-xl p-3 group-hover:bg-teal-200 dark:group-hover:bg-teal-900/60 transition-colors">
                            <svg class="h-6 w-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-teal-700 dark:group-hover:text-teal-400 transition-colors">My Orders</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @if($pendingOrders > 0)
                                    <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $pendingOrders }} pending</span>
                                @else
                                    Track à la carte orders
                                @endif
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('customer.payments') }}"
                        class="group bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 rounded-2xl p-5 transition-all hover:-translate-y-0.5 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl p-3 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-900/50 transition-colors">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-yellow-700 dark:group-hover:text-yellow-400 transition-colors">Payments</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Full payment history</p>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
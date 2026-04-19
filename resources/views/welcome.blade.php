<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CaterEase') }} - Premium Catering Services</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .hero-orb-1 { position: absolute; top: -80px; right: -80px; width: 420px; height: 420px; background: rgba(255,255,255,0.06); border-radius: 9999px; pointer-events: none; }
        .hero-orb-2 { position: absolute; bottom: -100px; left: -60px; width: 500px; height: 500px; background: rgba(0,0,0,0.10); border-radius: 9999px; pointer-events: none; }
        .stat-card { backdrop-filter: blur(10px); }
        .package-card:hover { transform: translateY(-4px); }
        .caterer-card:hover { transform: translateY(-4px); }
        .step-line::after { content: ''; position: absolute; top: 28px; left: calc(50% + 28px); width: calc(100% - 56px); height: 2px; background: linear-gradient(90deg, #16a34a, #d1fae5); }
        @media (max-width: 767px) { .step-line::after { display: none; } }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900">

    {{-- ══════════════════════════════════════════════════════════════
         STICKY NAV
    ══════════════════════════════════════════════════════════════ --}}
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase Logo" style="height:52px;width:auto;">
                    <span class="text-xl font-bold text-gray-800 hidden sm:block">CaterEase</span>
                </a>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-600 hover:text-green-700 px-4 py-2 rounded-xl transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            Sign Up Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════════════════════ --}}
    <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 overflow-hidden">
        <div class="hero-orb-1"></div>
        <div class="hero-orb-2"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="max-w-3xl mb-10">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/15 border border-white/25 rounded-full text-white text-xs font-semibold mb-5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Trusted by thousands of happy customers
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-5">
                    Find Your Perfect<br>
                    <span class="text-yellow-300">Caterer</span> Today
                </h1>
                <p class="text-green-100 text-lg sm:text-xl max-w-2xl leading-relaxed">
                    Browse {{ $stats['total_packages'] }} catering packages from verified local caterers. Filter by price, event type, or your dietary needs — and book with confidence.
                </p>
            </div>

            {{-- Search bar — goes to public browse route for guests, customer route for logged-in --}}
            <form action="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}" method="GET">
                <div class="flex flex-col sm:flex-row gap-3 max-w-2xl">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search"
                            placeholder="Search packages, cuisines, caterers…"
                            class="w-full pl-12 pr-4 py-4 rounded-xl text-gray-900 border-0 focus:ring-2 focus:ring-white/60 text-base shadow-lg placeholder-gray-400">
                    </div>
                    <button type="submit"
                        class="bg-white text-green-700 px-7 py-4 rounded-xl font-bold hover:bg-green-50 active:bg-green-100 transition-colors flex items-center justify-center gap-2 shadow-lg shrink-0 text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </div>
            </form>

            {{-- Stats row --}}
            <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl">
                @php
                    $heroStats = [
                        ['value' => $stats['total_caterers'], 'label' => 'Active Caterers', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['value' => $stats['total_packages'], 'label' => 'Food Packages', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['value' => $stats['total_bookings'], 'label' => 'Events Served', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['value' => $stats['average_rating'] . '★', 'label' => 'Avg. Rating', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ];
                @endphp
                @foreach($heroStats as $s)
                    <div class="stat-card bg-white/10 border border-white/20 rounded-2xl px-5 py-4 flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-extrabold text-white leading-tight">{{ $s['value'] }}</div>
                            <div class="text-green-200 text-xs font-medium">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         CUISINE CHIPS
    ══════════════════════════════════════════════════════════════ --}}
    @if($cuisineTypes->count() > 0)
    <div class="bg-white border-b border-gray-100 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Browse by:</span>
                @foreach($cuisineTypes as $cuisine)
                    {{-- Cuisine chips go to public browse route for guests --}}
                    <a href="{{ auth()->check() ? route('customer.packages', ['cuisine' => $cuisine]) : route('browse.packages', ['cuisine' => $cuisine]) }}"
                        class="px-4 py-1.5 bg-gray-100 hover:bg-green-50 hover:text-green-700 hover:border-green-300 border border-gray-200 rounded-full text-sm text-gray-600 font-medium transition-all">
                        {{ $cuisine }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         FEATURED CATERERS
    ══════════════════════════════════════════════════════════════ --}}
    @if($featuredCaterers->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Top Picks</p>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Featured Caterers</h2>
                    <p class="text-gray-500 mt-1">Top-rated catering services for your events</p>
                </div>
                {{-- "View all" link — public browse for guests, customer route for logged-in --}}
                <a href="{{ auth()->check() ? route('customer.caterers') : route('browse.caterers') }}"
                    class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                    View all caterers
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($featuredCaterers as $caterer)
                <div class="caterer-card group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 flex flex-col">
                    {{-- Image --}}
                    <div class="relative h-44 overflow-hidden">
                        @if($caterer->profile_photo)
                            <img src="{{ $caterer->profile_photo_url }}"
                                alt="{{ $caterer->business_name ?? $caterer->name }}"
                                loading="lazy"
                                decoding="async"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($caterer->featuredImages->count() > 0)
                            <img src="{{ $caterer->featuredImages->first()->image_path }}"
                                alt="{{ $caterer->business_name ?? $caterer->name }}"
                                loading="lazy"
                                decoding="async"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 via-emerald-500 to-teal-500 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                <svg class="w-14 h-14 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Rating badge --}}
                        <div class="absolute top-2 right-2 flex items-center gap-1 bg-white/90 backdrop-blur-sm rounded-full px-2.5 py-1 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($caterer->average_rating, 1) }}</span>
                            <span class="text-xs text-gray-400">({{ $caterer->total_reviews }})</span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-base leading-snug mb-1 group-hover:text-green-600 transition-colors">
                            {{ $caterer->business_name ?? $caterer->name }}
                        </h3>

                        @if($caterer->business_address)
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-2">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ Str::limit($caterer->business_address, 40) }}
                        </div>
                        @endif

                        @if($caterer->cuisine_types && is_array($caterer->cuisine_types) && count($caterer->cuisine_types) > 0)
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @foreach(array_slice($caterer->cuisine_types, 0, 3) as $cuisine)
                                <span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-md text-xs font-medium">{{ $cuisine }}</span>
                            @endforeach
                        </div>
                        @endif

                        <div class="flex-1"></div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-3">
                            @if($caterer->packages->count() > 0)
                                <div>
                                    <span class="text-xs text-gray-400">Starting at</span>
                                    <div class="text-lg font-extrabold text-gray-900">₱{{ number_format($caterer->packages->min('price'), 0) }}</div>
                                </div>
                            @else
                                <div></div>
                            @endif

                            {{-- ✅ FIXED: Guests go to public browse route, NOT login --}}
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.caterer.profile', $caterer->id) }}"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            @else
                                {{-- ✅ Guest — goes directly to public caterer profile, no login required --}}
                                <a href="{{ route('browse.caterer.profile', $caterer->id) }}"
                                    class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                {{-- ✅ FIXED: Guests go to browse route --}}
                <a href="{{ auth()->check() ? route('customer.caterers') : route('browse.caterers') }}"
                    class="inline-flex items-center gap-2 border-2 border-green-600 text-green-700 hover:bg-green-600 hover:text-white px-7 py-3 rounded-xl font-semibold transition-all text-sm">
                    View All Caterers
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         POPULAR PACKAGES
    ══════════════════════════════════════════════════════════════ --}}
    @if($popularPackages->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Most Booked</p>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Popular Packages</h2>
                    <p class="text-gray-500 mt-1">Most booked catering packages</p>
                </div>
                <a href="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}"
                    class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                    Browse all packages
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($popularPackages as $package)
                @php
                    $nameLower = strtolower($package->name);
                    if (str_contains($nameLower, 'wedding'))       $eventLabel = 'Wedding';
                    elseif (str_contains($nameLower, 'corporate')) $eventLabel = 'Corporate';
                    elseif (str_contains($nameLower, 'birthday'))  $eventLabel = 'Birthday';
                    elseif (str_contains($nameLower, 'party'))     $eventLabel = 'Party';
                    elseif (str_contains($nameLower, 'buffet'))    $eventLabel = 'Buffet';
                    else                                           $eventLabel = 'Event';
                    $pricePerHead = ($package->pax > 0) ? ($package->price / $package->pax) : $package->price;
                @endphp
                <div class="package-card group relative bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 flex flex-col">
                    {{-- Booking count badge --}}
                    @if($package->bookings_count > 0)
                        <div class="absolute top-0 left-0 right-0 z-10 flex items-center gap-1.5 px-3 py-1.5 bg-yellow-400/90 backdrop-blur-sm text-yellow-900 text-xs font-semibold">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            {{ $package->bookings_count }} {{ Str::plural('booking', $package->bookings_count) }}
                        </div>
                    @endif

                    {{-- Image --}}
                    <div class="relative overflow-hidden {{ $package->bookings_count > 0 ? 'pt-7' : '' }}">
                        @if($package->image_path)
                            <img src="{{ $package->image_path }}" alt="{{ $package->name }}"
                                 loading="lazy" decoding="async"
                                 class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-44 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-500 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                <svg class="w-14 h-14 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        @endif
                        <span class="absolute bottom-2 left-2 px-2.5 py-1 text-xs font-semibold bg-white/90 text-gray-700 rounded-full backdrop-blur-sm shadow-sm">
                            {{ $eventLabel }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-base leading-snug mb-1 group-hover:text-green-600 transition-colors line-clamp-2">
                            {{ $package->name }}
                        </h3>

                        {{-- Caterer row --}}
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ substr($package->user->business_name ?? $package->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-500 truncate font-medium">{{ $package->user->business_name ?? $package->user->name }}</span>
                            @if($package->caterer_reviews > 0)
                                <div class="flex items-center gap-0.5 ml-auto shrink-0">
                                    <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-xs text-gray-500">{{ number_format($package->caterer_rating, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        @if($package->description)
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $package->description }}</p>
                        @endif

                        <div class="flex-1"></div>

                        <div class="flex items-end justify-between pt-3 border-t border-gray-100 mt-auto">
                            <div>
                                <div class="text-xl font-extrabold text-gray-900">₱{{ number_format($package->price, 0) }}</div>
                                @if($package->pax)
                                    <div class="text-xs text-gray-500">₱{{ number_format($pricePerHead, 0) }}/pax · {{ $package->pax }} pax min.</div>
                                @endif
                            </div>

                            {{-- ✅ FIXED: Guests go to public browse package route, NOT login --}}
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.package.details', [$package->user_id, $package->id]) }}"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm shrink-0">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm shrink-0">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            @else
                                {{-- ✅ Guest — goes directly to public package details, no login required --}}
                                <a href="{{ route('browse.package.details', [$package->user_id, $package->id]) }}"
                                    class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm shrink-0">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         HOW IT WORKS
    ══════════════════════════════════════════════════════════════ --}}
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Simple Process</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">How It Works</h2>
                <p class="text-gray-500">Book your perfect caterer in just a few steps</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['num' => '1', 'title' => 'Browse Packages', 'desc' => 'Explore verified caterers and their curated packages.', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                        ['num' => '2', 'title' => 'Customize Menu', 'desc' => 'Select items and tailor the menu to your event.', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                        ['num' => '3', 'title' => 'Book & Pay', 'desc' => 'Secure your date with a safe deposit payment.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['num' => '4', 'title' => 'Enjoy Your Event', 'desc' => 'Relax and let our caterers handle everything.', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                    <div class="relative {{ $i < 3 ? 'step-line' : '' }} text-center">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md shadow-green-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="w-6 h-6 bg-green-600 text-white text-xs font-bold rounded-full flex items-center justify-center mx-auto -mt-2 mb-3 border-2 border-white shadow-sm">{{ $step['num'] }}</div>
                        <h3 class="font-bold text-gray-900 mb-1.5">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.packages') }}"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-colors shadow-sm">
                            Start Browsing
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @elseif(auth()->user()->role === 'caterer')
                        <a href="{{ route('caterer.dashboard') }}"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-colors shadow-sm">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-colors shadow-sm">
                            Go to Admin Panel
                        </a>
                    @endif
                @else
                    {{-- ✅ FIXED: Guest "Get Started" goes to browse, not login --}}
                    <a href="{{ route('browse.packages') }}"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-colors shadow-sm">
                        Browse Packages Free
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         REVIEWS
    ══════════════════════════════════════════════════════════════ --}}
    @if($recentReviews->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Social Proof</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">What Our Customers Say</h2>
                <p class="text-gray-500">Real reviews from real customers</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($recentReviews as $review)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 flex flex-col">
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 fill-current {{ $i <= ($review->rating ?? 5) ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed flex-1 mb-4">"{{ Str::limit($review->comment, 150) }}"</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                            {{ substr($review->customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $review->customer->name }}</p>
                            <p class="text-xs text-gray-500">Catered by {{ $review->caterer->business_name ?? $review->caterer->name }}</p>
                        </div>
                        <span class="ml-auto text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════════════════════════════ --}}
    <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-8 w-80 h-80 bg-black/10 rounded-full pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-tight">Ready to Make Your Event Memorable?</h2>
            <p class="text-green-100 text-lg mb-8 max-w-xl mx-auto">Join thousands of satisfied customers who trusted CaterEase for their catering needs.</p>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.caterers') }}"
                        class="inline-flex items-center gap-2 bg-white text-green-700 px-8 py-4 rounded-xl font-bold text-base hover:bg-green-50 transition-colors shadow-lg">
                        Browse Caterers Now
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            @else
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    {{-- ✅ FIXED: Guest CTA goes to browse first, then register --}}
                    <a href="{{ route('browse.packages') }}"
                        class="inline-flex items-center justify-center gap-2 bg-white text-green-700 px-8 py-4 rounded-xl font-bold text-base hover:bg-green-50 transition-colors shadow-lg">
                        Browse Packages Free
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-base hover:bg-white hover:text-green-700 transition-all">
                        Create Free Account
                    </a>
                </div>
            @endauth
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold">CaterEase</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Your trusted platform for finding the perfect catering service for any event.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">For Customers</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('browse.caterers') }}" class="hover:text-white transition-colors">Browse Caterers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">How It Works</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">My Bookings</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">For Caterers</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Join as Caterer</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Resources</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Company</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} CaterEase. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
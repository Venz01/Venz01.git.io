<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CaterEase') }} - Premium Catering Services</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">CaterEase</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.dashboard') }}"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                        @elseif(auth()->user()->role === 'caterer')
                            <a href="{{ route('caterer.dashboard') }}"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                Admin Panel
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">
                            Sign up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-16 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Find Your Perfect
                    <span class="text-indigo-600">Caterer</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Connect with professional caterers for your events. Browse packages, customize menus, and book with confidence.
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('customer.caterers') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Search caterers, cuisine types..."
                            class="flex-1 px-6 py-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 font-medium">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 max-w-4xl mx-auto">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_caterers'] }}</div>
                        <div class="text-gray-600 text-sm mt-1">Active Caterers</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_packages'] }}</div>
                        <div class="text-gray-600 text-sm mt-1">Food Packages</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_bookings'] }}</div>
                        <div class="text-gray-600 text-sm mt-1">Events Served</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['average_rating'] }}</div>
                        <div class="text-gray-600 text-sm mt-1">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuisine Types -->
    @if($cuisineTypes->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">Browse by Cuisine</h2>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($cuisineTypes as $cuisine)
                <a href="{{ route('customer.caterers', ['cuisine' => $cuisine]) }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-full text-gray-700 font-medium transition">
                    {{ $cuisine }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Caterers -->
    @if($featuredCaterers->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Caterers</h2>
                <p class="text-gray-600">Top-rated catering services for your events</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredCaterers as $caterer)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition group">
                    <!-- Image -->
                    <div class="h-48 bg-gradient-to-br from-indigo-100 to-purple-100 relative overflow-hidden">
                        @if($caterer->profile_photo)
                            <img src="{{ Storage::url($caterer->profile_photo) }}" 
                                alt="{{ $caterer->business_name ?? $caterer->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @elseif($caterer->featuredImages->count() > 0)
                            <img src="{{ Storage::url($caterer->featuredImages->first()->image_path) }}" 
                                alt="{{ $caterer->business_name ?? $caterer->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $caterer->business_name ?? $caterer->name }}
                        </h3>

                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($caterer->average_rating))
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-gray-600 text-sm">
                                {{ number_format($caterer->average_rating, 1) }} ({{ $caterer->total_reviews }} reviews)
                            </span>
                        </div>

                        <!-- Location -->
                        @if($caterer->business_address)
                        <p class="text-gray-600 text-sm mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ Str::limit($caterer->business_address, 40) }}
                        </p>
                        @endif

                        <!-- Cuisine Types -->
                        @if($caterer->cuisine_types && count($caterer->cuisine_types) > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach(array_slice($caterer->cuisine_types, 0, 3) as $cuisine)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium">
                                {{ $cuisine }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Packages -->
                        @if($caterer->packages->count() > 0)
                        <p class="text-gray-600 text-sm mb-4">
                            Starting at <span class="text-lg font-bold text-indigo-600">₱{{ number_format($caterer->packages->min('price'), 2) }}</span> per head
                        </p>
                        @endif

                        <!-- View Button -->
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('customer.caterer.profile', $caterer->id) }}"
                                    class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                                    View Profile
                                </a>
                            @else
                                <button disabled
                                    class="block w-full text-center bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                                    Customer Access Only
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                                Login to View
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.caterers') }}"
                            class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg border-2 border-indigo-600 hover:bg-indigo-50 font-medium">
                            View All Caterers
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg border-2 border-indigo-600 hover:bg-indigo-50 font-medium">
                        Login to Browse Caterers
                    </a>
                @endauth
            </div>
        </div>
    </div>
    @endif

    <!-- Popular Packages -->
    @if($popularPackages->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Popular Packages</h2>
                <p class="text-gray-600">Most booked catering packages</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularPackages as $package)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <!-- Package Image -->
                    <div class="h-48 bg-gradient-to-br from-purple-100 to-pink-100 relative overflow-hidden">
                        @if($package->image_path)
                            <img src="{{ Storage::url($package->image_path) }}" 
                                alt="{{ $package->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        @endif
                        @if($package->bookings_count > 0)
                        <div class="absolute top-2 right-2 bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-700 shadow">
                            {{ $package->bookings_count }} {{ Str::plural('booking', $package->bookings_count) }}
                        </div>
                        @endif
                    </div>

                    <!-- Package Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                        
                        <!-- Caterer Info -->
                        <div class="flex items-center mb-3">
                            <p class="text-gray-600 text-sm">by {{ $package->user->business_name ?? $package->user->name }}</p>
                            @if($package->caterer_reviews > 0)
                            <div class="flex items-center ml-auto">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span class="ml-1 text-sm text-gray-600">{{ number_format($package->caterer_rating, 1) }}</span>
                            </div>
                            @endif
                        </div>

                        @if($package->description)
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($package->description, 100) }}</p>
                        @endif

                        <!-- Package Details -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-sm text-gray-500">Good for</span>
                                <p class="text-lg font-bold text-gray-900">{{ $package->pax }} pax</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">Per head</span>
                                <p class="text-2xl font-bold text-indigo-600">₱{{ number_format($package->price, 2) }}</p>
                            </div>
                        </div>

                        <!-- Items Count -->
                        @if($package->items->count() > 0)
                        <p class="text-gray-600 text-sm mb-4">
                            Includes {{ $package->items->count() }} {{ Str::plural('item', $package->items->count()) }}
                        </p>
                        @endif

                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('customer.package.details', [$package->user_id, $package->id]) }}"
                                    class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                                    View Package
                                </a>
                            @else
                                <button disabled
                                    class="block w-full text-center bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                                    Customer Access Only
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                                Login to View
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    @if($recentReviews->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                <p class="text-gray-600">Real reviews from real customers</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recentReviews as $review)
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <!-- Stars -->
                    <div class="flex text-yellow-400 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endfor
                    </div>

                    <!-- Review -->
                    <p class="text-gray-700 mb-4">"{{ Str::limit($review->comment, 150) }}"</p>

                    <!-- Customer & Caterer -->
                    <div class="border-t pt-4">
                        <p class="font-semibold text-gray-900">{{ $review->customer->name }}</p>
                        <p class="text-sm text-gray-600">Catered by {{ $review->caterer->business_name ?? $review->caterer->name }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- How It Works -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-gray-600">Book your perfect caterer in just a few steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-indigo-600">1</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Browse Caterers</h3>
                    <p class="text-gray-600 text-sm">Explore our vetted caterers and their packages</p>
                </div>

                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-indigo-600">2</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Customize Menu</h3>
                    <p class="text-gray-600 text-sm">Select items and customize to your preferences</p>
                </div>

                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-indigo-600">3</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Book & Pay</h3>
                    <p class="text-gray-600 text-sm">Secure your booking with a deposit payment</p>
                </div>

                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-indigo-600">4</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Enjoy Your Event</h3>
                    <p class="text-gray-600 text-sm">Relax and let us handle the catering</p>
                </div>
            </div>

            <div class="text-center mt-12">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.caterers') }}"
                            class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            Start Browsing
                        </a>
                    @elseif(auth()->user()->role === 'caterer')
                        <a href="{{ route('caterer.dashboard') }}"
                            class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            Go to Admin Panel
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                        class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-gradient-to-br from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Make Your Event Memorable?</h2>
            <p class="text-xl mb-8 text-indigo-100">Join thousands of satisfied customers who trusted us with their catering needs</p>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.caterers') }}"
                        class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg hover:bg-gray-100 font-bold text-lg">
                        Browse Caterers Now
                    </a>
                @endif
            @else
                <div class="flex justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg hover:bg-gray-100 font-bold text-lg">
                        Sign Up as Customer
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-indigo-600 font-bold text-lg">
                        Join as Caterer
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">CaterEase</h3>
                    <p class="text-gray-400">Your trusted platform for finding the perfect catering service.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">For Customers</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Browse Caterers</a></li>
                        <li><a href="#" class="hover:text-white">How It Works</a></li>
                        <li><a href="#" class="hover:text-white">My Bookings</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">For Caterers</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Join as Caterer</a></li>
                        <li><a href="#" class="hover:text-white">Pricing</a></li>
                        <li><a href="#" class="hover:text-white">Resources</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} CaterEase. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
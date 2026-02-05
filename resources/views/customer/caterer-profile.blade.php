<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.caterers') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $caterer->business_name ?? $caterer->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Caterer Info Header with Profile Photo -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-600 to-green-800 p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                        <!-- Profile Photo -->
                        <div class="shrink-0">
                            @if($caterer->profile_photo)
                            <img src="{{ $caterer->profile_photo }}"
                                alt="{{ $caterer->business_name ?? $caterer->name }}"
                                class="w-32 h-32 object-cover rounded-2xl border-4 border-white shadow-xl">
                            @else
                            <div
                                class="w-32 h-32 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-5xl font-bold backdrop-blur-sm border-4 border-white shadow-xl">
                                {{ substr($caterer->business_name ?? $caterer->name, 0, 1) }}
                            </div>
                            @endif
                        </div>

                        <!-- Business Info -->
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold mb-3">{{ $caterer->business_name ?? $caterer->name }}</h1>

                            <!-- Rating and Location -->
                            <div class="flex flex-wrap items-center gap-6 text-lg opacity-90 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-400 fill-current mr-2" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $caterer->averageRating() }} ({{ $caterer->totalReviews() }} reviews)
                                </div>
                                @if($caterer->business_address)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $caterer->business_address }}
                                </div>
                                @endif
                                @if($caterer->years_of_experience)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $caterer->years_of_experience }} years experience
                                </div>
                                @endif
                            </div>

                            <!-- Bio/Description -->
                            @if($caterer->bio || $caterer->services_offered)
                            <p class="text-lg opacity-90 leading-relaxed">
                                {{ $caterer->bio ?? $caterer->services_offered }}
                            </p>
                            @endif

                            <!-- Cuisine Types -->
                            @if($caterer->cuisine_types && count($caterer->cuisine_types) > 0)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($caterer->cuisine_types as $cuisine)
                                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                                    {{ $cuisine }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            <!-- Features Badges -->
                            <div class="mt-4 flex flex-wrap gap-3">
                                @if($caterer->offers_delivery)
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </svg>
                                    Delivery Available
                                </span>
                                @endif
                                @if($caterer->offers_setup)
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                    Setup Service
                                </span>
                                @endif
                                @if($caterer->maximum_capacity)
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Up to {{ number_format($caterer->maximum_capacity) }} guests
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col space-y-3 lg:self-start">
                            @if($caterer->contact_number || $caterer->phone)
                            <a href="tel:{{ $caterer->contact_number ?? $caterer->phone }}"
                                class="bg-white text-green-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                Call Now
                            </a>
                            @endif
                            @if($caterer->facebook_link)
                            <a href="{{ $caterer->facebook_link }}" target="_blank"
                                class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    @if($caterer->contact_number || $caterer->phone)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Phone</div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $caterer->contact_number ??
                                $caterer->phone }}</div>
                        </div>
                    </div>
                    @endif

                    @if($caterer->email)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                            <div class="font-semibold text-gray-900 dark:text-white break-all">{{ $caterer->email }}</div>
                        </div>
                    </div>
                    @endif

                    @if($caterer->business_hours_start && $caterer->business_hours_end)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Business Hours</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ date('g:i A', strtotime($caterer->business_hours_start)) }} -
                                {{ date('g:i A', strtotime($caterer->business_hours_end)) }}
                            </div>
                            @if($caterer->business_days && count($caterer->business_days) > 0)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ implode(', ', array_map('ucfirst', $caterer->business_days)) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Portfolio Section (if available) -->
            @if($caterer->portfolioImages && $caterer->portfolioImages->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-8 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Portfolio</h2>
                    <button onclick="openFullGallery()"
                        class="text-green-600 hover:text-green-700 font-semibold flex items-center">
                        View All
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($caterer->portfolioImages->take(8) as $index => $image)
                    <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300"
                        onclick="openLightbox({{ $index }})">
                        <img src="{{ $image->image_path }}" alt="{{ $image->title ?? 'Portfolio Image' }}"
                            class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tabs Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button id="packagesTab" onclick="switchTab('packages')"
                            class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all duration-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Packages</span>
                            </div>
                        </button>
                        <button id="menusTab" onclick="switchTab('menus')"
                            class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all duration-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <span>Display Menu</span>
                            </div>
                        </button>
                        <button id="reviewsTab" onclick="switchTab('reviews')"
                            class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all duration-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span>Reviews ({{ $caterer->totalReviews() }})</span>
                            </div>
                        </button>
                    </nav>
                </div>

                <!-- Packages Tab Content -->
                <div id="packagesContent" class="tab-content p-8">
                    @if($caterer->packages && $caterer->packages->count() > 0)
                    <!-- Category Filter -->
                    @php
                    $categories = $caterer->packages->pluck('category')->unique()->filter();
                    @endphp

                    @if($categories->count() > 0)
                    <div class="flex flex-wrap gap-3 mb-8">
                        <button onclick="filterCategory('all', this)"
                            class="category-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-green-600 text-white hover:bg-green-700 shadow-md">
                            All Packages
                        </button>
                        @foreach($categories as $category)
                        <button onclick="filterCategory('{{ $category }}', this)"
                            class="category-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                            {{ ucfirst($category) }}
                        </button>
                        @endforeach
                    </div>
                    @endif

                    <!-- Packages Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($caterer->packages as $package)
                        <div class="package-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700"
                            data-category="{{ $package->category }}">
                            <!-- Package Image -->
                            <div class="relative h-56 overflow-hidden group">
                                @if($package->image_path)
                                <img src="{{ $package->image_path }}" alt="{{ $package->name }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                @else
                                <!-- Fallback gradient background if no image -->
                                <div class="w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                @if($package->category)
                                <span class="absolute top-4 right-4 bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    {{ ucfirst($package->category) }}
                                </span>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $package->name }}
                                </h3>

                                <div class="flex items-baseline gap-2 mb-4">
                                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                                        ₱{{ number_format($package->price, 2) }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">
                                        per head
                                    </span>
                                </div>

                                @if($package->description)
                                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ $package->description }}
                                </p>
                                @endif

                                <!-- Package Features -->
                                @if($package->pax)
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Good for {{ $package->pax }} pax
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $package->items->count() }} menu items
                                    </div>
                                </div>
                                @endif

                                <!-- Single View Details Button -->
                                <a href="{{ route('customer.package.details', ['catererId' => $caterer->id, 'packageId' => $package->id]) }}"
                                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 text-center">
                                    View Details & Book
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No packages found</h3>
                        <p class="text-gray-500 dark:text-gray-400">Try selecting a different category</p>
                    </div>
                    @else
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No packages available
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">This caterer hasn't added any packages yet</p>
                    </div>
                    @endif
                </div>

                <!-- Menus Tab Content -->
                <div id="menusContent" class="tab-content hidden p-8">
                    @if($caterer->displayMenus && $caterer->displayMenus->where('status', 'active')->count() > 0)
                    @php
                    $displayMenusByCategory = $caterer->displayMenus->where('status', 'active')->groupBy('category');
                    @endphp

                    <div class="space-y-8">
                        @foreach($displayMenusByCategory as $category => $menus)
                        <!-- Category Section -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ ucfirst($category) }}
                                </h3>
                                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700 ml-4"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($menus as $menu)
                                <div
                                    class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                                    @if($menu->image_path)
                                    <div class="relative h-48 overflow-hidden group">
                                        <img src="{{ $menu->image_path }}" alt="{{ $menu->name }}"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
                                        </div>
                                    </div>
                                    @endif

                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            {{ $menu->name }}
                                        </h3>

                                        @if($menu->description)
                                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                            {{ $menu->description }}
                                        </p>
                                        @endif

                                        @if($menu->price)
                                        <div class="flex items-baseline gap-2 mb-4">
                                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                                ₱{{ number_format($menu->price, 2) }}
                                            </span>
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">
                                                per {{ $menu->unit_type ?? 'item' }}
                                            </span>
                                        </div>
                                        @endif

                                        <!-- Add to Cart Button -->
                                        @auth
                                            @if(auth()->user()->role === 'customer')
                                            <form action="{{ route('customer.orders.add-to-cart', $menu->id) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                <input type="number" name="quantity" value="1" min="1" max="100" 
                                                    class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                                <button type="submit" 
                                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Add to Cart
                                                </button>
                                            </form>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" 
                                                class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                                Login to Order
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No menu items available
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">This caterer hasn't added any menu items yet</p>
                    </div>
                    @endif
                </div>

                <!-- Reviews Tab Content -->
                <div id="reviewsContent" class="tab-content hidden p-8">
                    @php
                    $reviews = $caterer->approvedReviews()->with(['customer', 'booking'])->orderBy('created_at', 'desc')->paginate(10);
                    $ratingDistribution = $caterer->ratingDistribution();
                    $averageRating = $caterer->averageRating();
                    $totalReviews = $caterer->totalReviews();
                    @endphp

                    @if($totalReviews > 0)
                    <!-- Reviews Summary -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-8 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Overall Rating -->
                            <div class="text-center md:text-left">
                                <div class="text-6xl font-bold text-gray-900 dark:text-white mb-2">{{ $averageRating }}</div>
                                <div class="flex items-center justify-center md:justify-start mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=$averageRating) <svg class="w-8 h-8 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            @else
                                            <svg class="w-8 h-8 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            @endif
                                            @endfor
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 font-medium">Based on {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
                            </div>

                            <!-- Rating Distribution -->
                            <div class="space-y-2">
                                @foreach([5, 4, 3, 2, 1] as $rating)
                                @php
                                $count = $ratingDistribution[$rating];
                                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-12">{{ $rating }} star</span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-12 text-right">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <!-- Customer Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($review->customer->profile_photo)
                                        <img src="{{ $review->customer->profile_photo }}" alt="{{ $review->customer->name }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                        @else
                                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($review->customer->name, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review->customer->name }}</h4>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=$review->rating)
                                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                @else
                                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                @endif
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $review->comment }}</p>

                            <!-- Caterer Response -->
                            @if($review->caterer_response)
                            <div class="mt-4 ml-16 bg-white dark:bg-gray-600 rounded-lg p-4 border-l-4 border-green-500">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-900 dark:text-white">Response from Caterer</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $review->caterer_response }}</p>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-2 block">{{ $review->responded_at->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                    @endif

                    @else
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No reviews yet</h3>
                        <p class="text-gray-500 dark:text-gray-400">Be the first to book and review this caterer!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Lightbox Modal -->
    <div id="lightboxModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
        <div class="h-full flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 text-white">
                <div id="lightboxTitle" class="text-lg font-semibold"></div>
                <button onclick="closeLightbox()" class="text-white hover:text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Image Container -->
            <div class="flex-1 flex items-center justify-center px-4">
                <button onclick="previousImage()" class="absolute left-4 text-white hover:text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <img id="lightboxImage" src="" alt="" class="max-h-[80vh] max-w-[90vw] object-contain">

                <button onclick="nextImage()" class="absolute right-4 text-white hover:text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Description -->
            <div id="lightboxDescription" class="p-4 text-white text-center"></div>
        </div>
    </div>

    <!-- Full Gallery Modal -->
    <div id="fullGalleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-y-auto">
        <div class="min-h-screen p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 text-white">
                <h2 class="text-2xl font-bold">Portfolio Gallery</h2>
                <button onclick="closeFullGallery()" class="text-white hover:text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @if($caterer->portfolioImages)
                @foreach($caterer->portfolioImages as $index => $image)
                <div class="cursor-pointer group relative rounded-lg overflow-hidden shadow-lg"
                    onclick="openLightbox({{ $index }})">
                    <img src="{{ $image->image_path }}" alt="{{ $image->title ?? 'Portfolio Image' }}"
                        class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        // Initialize portfolio images data
        const portfolioImages = @json($caterer->portfolioImages ?? []);
        let currentImageIndex = 0;

        // DOM element cache
        const elements = {
            lightboxModal: document.getElementById('lightboxModal'),
            lightboxImage: document.getElementById('lightboxImage'),
            lightboxTitle: document.getElementById('lightboxTitle'),
            lightboxDescription: document.getElementById('lightboxDescription'),
            fullGalleryModal: document.getElementById('fullGalleryModal'),
            packagesTab: document.getElementById('packagesTab'),
            menusTab: document.getElementById('menusTab'),
            reviewsTab: document.getElementById('reviewsTab'),
            packagesContent: document.getElementById('packagesContent'),
            menusContent: document.getElementById('menusContent'),
            reviewsContent: document.getElementById('reviewsContent'),
            emptyState: document.getElementById('emptyState'),
        };

        // Lightbox functions
        function openLightbox(index) {
            if (!portfolioImages.length || !elements.lightboxModal) return;

            currentImageIndex = index;
            updateLightboxImage();
            elements.lightboxModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            if (!elements.lightboxModal) return;
            elements.lightboxModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function nextImage() {
            if (!portfolioImages.length) return;
            currentImageIndex = (currentImageIndex + 1) % portfolioImages.length;
            updateLightboxImage();
        }

        function previousImage() {
            if (!portfolioImages.length) return;
            currentImageIndex = (currentImageIndex - 1 + portfolioImages.length) % portfolioImages.length;
            updateLightboxImage();
        }

        function updateLightboxImage() {
            const image = portfolioImages[currentImageIndex];
            if (!image) return;

            if (elements.lightboxImage) elements.lightboxImage.src = image.image_path;
            if (elements.lightboxTitle) elements.lightboxTitle.textContent = image.title || 'Portfolio Image';
            if (elements.lightboxDescription) elements.lightboxDescription.textContent = image.description || '';
        }

        function openFullGallery() {
            if (!elements.fullGalleryModal) return;
            elements.fullGalleryModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFullGallery() {
            if (!elements.fullGalleryModal) return;
            elements.fullGalleryModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (elements.lightboxModal && !elements.lightboxModal.classList.contains('hidden')) {
                if (e.key === 'ArrowRight') nextImage();
                else if (e.key === 'ArrowLeft') previousImage();
                else if (e.key === 'Escape') closeLightbox();
            }
        });

        // Modal click outside to close
        [elements.lightboxModal, elements.fullGalleryModal].forEach(modal => {
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });

        // Category filter functionality
        const categoryBtnClasses = {
            active: ['bg-green-600', 'text-white', 'hover:bg-green-700', 'shadow-md'],
            inactive: ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300']
        };

        function filterCategory(category, clickedButton) {
            // Update button states
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove(...categoryBtnClasses.active, ...categoryBtnClasses.inactive);
                btn.classList.add(...categoryBtnClasses.inactive);
            });

            clickedButton.classList.remove(...categoryBtnClasses.inactive);
            clickedButton.classList.add(...categoryBtnClasses.active);

            // Filter packages
            let visibleCount = 0;
            document.querySelectorAll('.package-card').forEach(pkg => {
                const pkgCategory = pkg.getAttribute('data-category');
                const shouldShow = category === 'all' || pkgCategory === category;
                pkg.style.display = shouldShow ? 'block' : 'none';
                if (shouldShow) visibleCount++;
            });

            // Toggle empty state
            if (elements.emptyState) {
                elements.emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // Tab switching functionality
        const tabClasses = {
            packages: {
                active: ['border-green-600', 'text-green-600', 'dark:text-green-400'],
                inactive: ['border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300']
            },
            menus: {
                active: ['border-purple-600', 'text-purple-600', 'dark:text-purple-400'],
                inactive: ['border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300']
            },
            reviews: {
                active: ['border-yellow-600', 'text-yellow-600', 'dark:text-yellow-400'],
                inactive: ['border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300']
            }
        };

        window.switchTab = function (tabName) {
            // Hide all content
            [elements.packagesContent, elements.menusContent, elements.reviewsContent].forEach(el => el?.classList.add('hidden'));

            // Reset all tabs
            [elements.packagesTab, elements.menusTab, elements.reviewsTab].forEach(tab => {
                if (!tab) return;
                tab.classList.remove(...tabClasses.packages.active, ...tabClasses.menus.active, ...tabClasses.reviews.active);
                tab.classList.add(...tabClasses.packages.inactive);
            });

            // Show selected tab
            if (tabName === 'packages' && elements.packagesContent && elements.packagesTab) {
                elements.packagesContent.classList.remove('hidden');
                elements.packagesTab.classList.remove(...tabClasses.packages.inactive);
                elements.packagesTab.classList.add(...tabClasses.packages.active);
            } else if (tabName === 'menus' && elements.menusContent && elements.menusTab) {
                elements.menusContent.classList.remove('hidden');
                elements.menusTab.classList.remove(...tabClasses.menus.inactive);
                elements.menusTab.classList.add(...tabClasses.menus.active);
            } else if (tabName === 'reviews' && elements.reviewsContent && elements.reviewsTab) {
                elements.reviewsContent.classList.remove('hidden');
                elements.reviewsTab.classList.remove(...tabClasses.reviews.inactive);
                elements.reviewsTab.classList.add(...tabClasses.reviews.active);
            }
        };

        // Initialize on DOM ready
        function initializePage() {
            switchTab('packages');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializePage);
        } else {
            initializePage();
        }
    </script>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
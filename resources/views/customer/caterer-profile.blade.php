<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.caterers') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
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
                                <img src="{{ asset('storage/' . $caterer->profile_photo) }}" 
                                     alt="{{ $caterer->business_name ?? $caterer->name }}" 
                                     class="w-32 h-32 object-cover rounded-2xl border-4 border-white shadow-xl">
                            @else
                                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-5xl font-bold backdrop-blur-sm border-4 border-white shadow-xl">
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
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $caterer->averageRating() }} ({{ $caterer->totalReviews() }} reviews)
                                </div>
                                @if($caterer->business_address)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $caterer->business_address }}
                                    </div>
                                @endif
                                @if($caterer->years_of_experience)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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
                                    <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                        Delivery Available
                                    </span>
                                @endif
                                @if($caterer->offers_setup)
                                    <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                        Setup Service
                                    </span>
                                @endif
                                @if($caterer->maximum_capacity)
                                    <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Call Now
                                </a>
                            @endif
                            @if($caterer->facebook_link || $caterer->instagram_link || $caterer->website_link)
                                <div class="flex space-x-2">
                                    @if($caterer->facebook_link)
                                        <a href="{{ $caterer->facebook_link }}" target="_blank" 
                                           class="bg-white bg-opacity-20 p-3 rounded-lg hover:bg-opacity-30 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($caterer->instagram_link)
                                        <a href="{{ $caterer->instagram_link }}" target="_blank" 
                                           class="bg-white bg-opacity-20 p-3 rounded-lg hover:bg-opacity-30 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($caterer->website_link)
                                        <a href="{{ $caterer->website_link }}" target="_blank" 
                                           class="bg-white bg-opacity-20 p-3 rounded-lg hover:bg-opacity-30 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Areas and Additional Info -->
            @if($caterer->service_areas || $caterer->special_features || $caterer->business_hours_start)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Service Areas -->
                        @if($caterer->service_areas && count($caterer->service_areas) > 0)
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    Service Areas
                                </h3>
                                <div class="space-y-1">
                                    @foreach($caterer->service_areas as $area)
                                        <div class="text-sm text-gray-600 dark:text-gray-400">• {{ $area }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Business Hours -->
                        @if($caterer->business_hours_start && $caterer->business_hours_end)
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Business Hours
                                </h3>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ date('g:i A', strtotime($caterer->business_hours_start)) }} - 
                                    {{ date('g:i A', strtotime($caterer->business_hours_end)) }}
                                </div>
                                @if($caterer->business_days && count($caterer->business_days) > 0)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ implode(', ', array_map('ucfirst', $caterer->business_days)) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Special Features -->
                        @if($caterer->special_features)
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Special Features
                                </h3>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $caterer->special_features }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Package Categories Navigation with INLINE JAVASCRIPT -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browse by Category</h3>
                    <div class="flex flex-wrap gap-3">
                        <!-- All Packages Button -->
                        <button 
                            type="button"
                            onclick="filterCategory('all', this)"
                            class="category-btn px-6 py-3 rounded-lg font-semibold transition-all duration-200 bg-green-600 text-white hover:bg-green-700 shadow-md"
                            data-category="all">
                            All Packages ({{ $caterer->packages->count() }})
                        </button>

                        @php
                            $categories = [];
                            foreach($caterer->packages as $package) {
                                $category = 'Other';
                                if(str_contains(strtolower($package->name), 'wedding')) $category = 'Wedding';
                                elseif(str_contains(strtolower($package->name), 'corporate')) $category = 'Corporate';
                                elseif(str_contains(strtolower($package->name), 'party')) $category = 'Party';
                                elseif(str_contains(strtolower($package->name), 'birthday')) $category = 'Birthday';
                                
                                if(!isset($categories[$category])) $categories[$category] = 0;
                                $categories[$category]++;
                            }
                        @endphp

                        @foreach($categories as $category => $count)
                            <button 
                                type="button"
                                onclick="filterCategory('{{ strtolower($category) }}', this)"
                                class="category-btn px-6 py-3 rounded-lg font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                data-category="{{ strtolower($category) }}">
                                {{ $category }} ({{ $count }})
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="packagesGrid">
                @foreach($caterer->packages as $package)
                    @php
                        $packageCategory = 'other';
                        if(str_contains(strtolower($package->name), 'wedding')) $packageCategory = 'wedding';
                        elseif(str_contains(strtolower($package->name), 'corporate')) $packageCategory = 'corporate';
                        elseif(str_contains(strtolower($package->name), 'party')) $packageCategory = 'party';
                        elseif(str_contains(strtolower($package->name), 'birthday')) $packageCategory = 'birthday';
                    @endphp
                    <div class="package-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300" 
                         data-category="{{ $packageCategory }}">
                        <!-- Package Image -->
                        <div class="relative h-64 bg-gradient-to-r from-gray-300 to-gray-400">
                            @if($package->image_path)
                                <img src="{{ asset('storage/' . $package->image_path) }}" 
                                     alt="{{ $package->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="inline-block px-3 py-1 text-sm font-medium bg-white bg-opacity-90 text-gray-800 rounded-full">
                                    @if($packageCategory == 'wedding')
                                        Wedding
                                    @elseif($packageCategory == 'corporate')
                                        Corporate
                                    @elseif($packageCategory == 'party')
                                        Party
                                    @elseif($packageCategory == 'birthday')
                                        Birthday
                                    @else
                                        Event Package
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 transition-colors">
                                {{ $package->name }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $package->description }}
                            </p>

                            <!-- Package Items Preview -->
                            @if($package->items->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Includes:</h4>
                                    <div class="space-y-1">
                                        @foreach($package->items->take(3) as $item)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $item->name }}
                                            </div>
                                        @endforeach
                                        @if($package->items->count() > 3)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                + {{ $package->items->count() - 3 }} more items
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <span class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</span>
                                    @if($package->pax)
                                        <span class="text-gray-500 text-sm">/ {{ $package->pax }} pax</span>
                                    @endif
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button 
                                        onclick="openAddToCartModal({{ $package->id }}, '{{ $package->name }}', {{ $package->price }}, {{ $caterer->id }})"
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium text-center whitespace-nowrap flex items-center justify-center"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Add to Cart
                                    </button>
                                    <a 
                                        href="{{ route('customer.package.details', [$caterer->id, $package->id]) }}" 
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium text-center whitespace-nowrap"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="emptyState" style="display: none;" class="text-center py-12">
                <svg class="w-20 h-20 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No packages in this category</p>
                <p class="text-gray-500 dark:text-gray-400">Try browsing all packages or select a different category</p>
            </div>

            <!-- Featured Portfolio Gallery Section - MOVED BELOW PACKAGES -->
            @if($caterer->portfolioImages->where('is_featured', true)->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mt-8 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-7 h-7 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Featured Portfolio
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                See our best work and signature creations
                            </p>
                        </div>
                        @if($caterer->portfolioImages->count() > $caterer->portfolioImages->where('is_featured', true)->count())
                            <button 
                                onclick="toggleFullGallery()"
                                class="text-green-600 hover:text-green-700 font-semibold text-sm flex items-center transition-colors">
                                View All Photos ({{ $caterer->portfolioImages->count() }})
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Featured Images Grid -->
                <div class="p-6">
                    <div class="flex flex-wrap justify-center gap-4">
                        @foreach($caterer->portfolioImages->where('is_featured', true) as $image)
                            <div class="relative group cursor-pointer w-full sm:w-[calc(50%-0.5rem)] md:w-[calc(33.333%-0.667rem)] lg:w-[calc(25%-0.75rem)] max-w-sm" onclick="openLightbox({{ $loop->index }}, 'featured')">
                                <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $image->title ?? 'Portfolio image' }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                </div>
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center px-3">
                                        @if($image->title)
                                            <p class="text-white font-semibold text-sm mb-1">{{ $image->title }}</p>
                                        @endif
                                        <svg class="w-8 h-8 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Featured Star Badge -->
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white p-1.5 rounded-full shadow-lg">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>


    <!-- Image Lightbox Modal -->
    <div id="lightboxModal" class="fixed inset-0 z-[60] hidden">
        <div id="lightboxBackdrop" class="fixed inset-0 bg-black bg-opacity-95 transition-opacity duration-300 opacity-0"></div>
        
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <!-- Close Button -->
            <button onclick="closeLightbox()" 
                    class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Previous Button -->
            <button onclick="previousImage()" 
                    class="absolute left-4 text-white hover:text-gray-300 transition-colors z-20">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Next Button -->
            <button onclick="nextImage()" 
                    class="absolute right-4 text-white hover:text-gray-300 transition-colors z-20">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Image Container -->
            <div id="lightboxContent" class="relative max-w-6xl max-h-[90vh] w-full transform transition-all duration-300 scale-95 opacity-0">
                <img id="lightboxImage" src="" alt="" class="w-full h-full object-contain rounded-lg">
                
                <!-- Image Info -->
                <div id="lightboxInfo" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6 rounded-b-lg">
                    <h4 id="lightboxTitle" class="text-white text-xl font-bold mb-2"></h4>
                    <p id="lightboxDescription" class="text-gray-300 text-sm"></p>
                    <p id="lightboxCounter" class="text-gray-400 text-xs mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Cart Modal -->
    <div id="addToCartModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-black opacity-50 transition-opacity" onclick="closeAddToCartModal()"></div>
            
            <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6 shadow-xl transform transition-all">
                <div class="absolute top-4 right-4">
                    <button onclick="closeAddToCartModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add to Cart</h3>
                    <p id="modalPackageName" class="text-sm text-gray-600 dark:text-gray-400 mt-1"></p>
                    <p id="modalPackagePrice" class="text-xl font-bold text-green-600 mt-2"></p>
                </div>
                
                <form action="{{ route('customer.cart.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="package_id" id="modal_package_id">
                    <input type="hidden" name="caterer_id" id="modal_caterer_id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Event Date <span class="text-gray-500">(Optional)</span>
                        </label>
                        <input type="date" 
                               name="event_date" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Number of Guests <span class="text-gray-500">(Optional)</span>
                        </label>
                        <input type="number" 
                               name="guest_count" 
                               min="1"
                               placeholder="Enter expected number of guests"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Special Requests <span class="text-gray-500">(Optional)</span>
                        </label>
                        <textarea name="special_requests" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Any special dietary requirements, preferences, or requests..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" 
                                onclick="closeAddToCartModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        // Portfolio data
        const featuredImages = [
            @foreach($caterer->portfolioImages->where('is_featured', true) as $image)
            {
                src: "{{ asset('storage/' . $image->image_path) }}",
                title: "{{ $image->title ?? '' }}",
                description: "{{ $image->description ?? '' }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ];

        const allImages = [
            @foreach($caterer->portfolioImages as $image)
            {
                src: "{{ asset('storage/' . $image->image_path) }}",
                title: "{{ $image->title ?? '' }}",
                description: "{{ $image->description ?? '' }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ];

        let currentImageIndex = 0;
        let currentGalleryType = 'featured';

        // Add to Cart Modal Functions
        function openAddToCartModal(packageId, packageName, packagePrice, catererId) {
            document.getElementById('modal_package_id').value = packageId;
            document.getElementById('modal_caterer_id').value = catererId;
            document.getElementById('modalPackageName').textContent = packageName;
            document.getElementById('modalPackagePrice').textContent = '₱' + packagePrice.toLocaleString('en-PH');
            document.getElementById('addToCartModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddToCartModal() {
            document.getElementById('addToCartModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Toggle full gallery modal
        function toggleFullGallery() {
            const modal = document.getElementById('fullGalleryModal');
            const backdrop = document.getElementById('galleryBackdrop');
            const panel = document.getElementById('galleryPanel');
            
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                backdrop.classList.remove('opacity-100');
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        }

        // Open lightbox
        function openLightbox(index, galleryType) {
            currentImageIndex = index;
            currentGalleryType = galleryType;
            
            const modal = document.getElementById('lightboxModal');
            const backdrop = document.getElementById('lightboxBackdrop');
            const content = document.getElementById('lightboxContent');
            
            updateLightboxImage();
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                backdrop.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Close lightbox
        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            const backdrop = document.getElementById('lightboxBackdrop');
            const content = document.getElementById('lightboxContent');
            
            backdrop.classList.remove('opacity-100');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        // Navigate images
        function previousImage() {
            const images = currentGalleryType === 'featured' ? featuredImages : allImages;
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateLightboxImage();
        }

        function nextImage() {
            const images = currentGalleryType === 'featured' ? featuredImages : allImages;
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateLightboxImage();
        }

        // Update lightbox image
        function updateLightboxImage() {
            const images = currentGalleryType === 'featured' ? featuredImages : allImages;
            const image = images[currentImageIndex];
            
            document.getElementById('lightboxImage').src = image.src;
            document.getElementById('lightboxTitle').textContent = image.title || '';
            document.getElementById('lightboxDescription').textContent = image.description || '';
            document.getElementById('lightboxCounter').textContent = `${currentImageIndex + 1} / ${images.length}`;
            
            // Hide info if no title or description
            const info = document.getElementById('lightboxInfo');
            if (!image.title && !image.description) {
                info.style.display = 'none';
            } else {
                info.style.display = 'block';
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightboxModal');
            if (!lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') previousImage();
                if (e.key === 'ArrowRight') nextImage();
            }
            
            const gallery = document.getElementById('fullGalleryModal');
            if (!gallery.classList.contains('hidden') && e.key === 'Escape') {
                toggleFullGallery();
            }

            const addToCartModal = document.getElementById('addToCartModal');
            if (!addToCartModal.classList.contains('hidden') && e.key === 'Escape') {
                closeAddToCartModal();
            }
        });

        // Click outside to close
        document.getElementById('lightboxModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });

        document.getElementById('fullGalleryModal')?.addEventListener('click', function(e) {
            if (e.target === this) toggleFullGallery();
        });

        // Category filter function
        function filterCategory(category, clickedButton) {
            console.log('Filtering category:', category);
            
            // Remove active state from all buttons
            var allButtons = document.querySelectorAll('.category-btn');
            allButtons.forEach(function(btn) {
                btn.classList.remove('bg-green-600', 'text-white', 'hover:bg-green-700', 'shadow-md');
                btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                btn.classList.add('dark:bg-gray-700', 'dark:text-gray-300');
            });
            
            // Add active state to clicked button
            clickedButton.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            clickedButton.classList.remove('dark:bg-gray-700', 'dark:text-gray-300');
            clickedButton.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700', 'shadow-md');
            
            // Filter packages
            var packages = document.querySelectorAll('.package-card');
            var visibleCount = 0;
            
            packages.forEach(function(pkg) {
                var pkgCategory = pkg.getAttribute('data-category');
                if (category === 'all' || pkgCategory === category) {
                    pkg.style.display = 'block';
                    visibleCount++;
                } else {
                    pkg.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            var emptyState = document.getElementById('emptyState');
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
            
            console.log('Visible packages:', visibleCount);
        }
    </script>
</x-app-layout>
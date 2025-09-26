<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Caterers') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <p>This is where customers browse caterers and their packages.</p>
            {{-- Add caterer list here --}}
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Caterers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold mb-4">Find the Perfect Caterer</h1>
                    <p class="text-xl mb-6 opacity-90">Browse and compare catering packages for your next event. Customize menus, get instant quotes, and book with confidence.</p>
                    
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('customer.caterers') }}" class="flex flex-col sm:flex-row gap-4 max-w-4xl">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search caterers, packages, or cuisines..." 
                                class="w-full pl-12 pr-4 py-4 rounded-xl text-gray-900 border-0 focus:ring-2 focus:ring-white focus:ring-opacity-50 text-lg"
                            >
                        </div>
                        <div class="flex gap-2">
                            <button 
                                type="submit"
                                class="bg-white text-green-700 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                            <button 
                                type="button"
                                id="filterToggle"
                                class="bg-green-700 text-white px-6 py-4 rounded-xl font-semibold hover:bg-green-800 transition-colors flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filters
                                <svg class="w-4 h-4 transition-transform" id="filterChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Advanced Filters -->
                    <div id="advancedFilters" class="hidden mt-6 p-6 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Location</label>
                                <input 
                                    type="text" 
                                    name="location" 
                                    value="{{ request('location') }}"
                                    placeholder="Enter city or area" 
                                    class="w-full px-4 py-3 rounded-lg text-gray-900 border-0"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Cuisine Type</label>
                                <select name="cuisine" class="w-full px-4 py-3 rounded-lg text-gray-900 border-0">
                                    <option value="">All Cuisines</option>
                                    <option value="Filipino" {{ request('cuisine') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                    <option value="International" {{ request('cuisine') == 'International' ? 'selected' : '' }}>International</option>
                                    <option value="Asian" {{ request('cuisine') == 'Asian' ? 'selected' : '' }}>Asian</option>
                                    <option value="Western" {{ request('cuisine') == 'Western' ? 'selected' : '' }}>Western</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Event Type</label>
                                <select name="event_type" class="w-full px-4 py-3 rounded-lg text-gray-900 border-0">
                                    <option value="">All Events</option>
                                    <option value="Wedding" {{ request('event_type') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                    <option value="Corporate" {{ request('event_type') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                    <option value="Birthday" {{ request('event_type') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                    <option value="Party" {{ request('event_type') == 'Party' ? 'selected' : '' }}>Party</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="space-y-8">
                @if($caterers->count() > 0)
                    @foreach($caterers as $caterer)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                            <div class="p-6">
                                <!-- Caterer Header -->
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                                    <div class="flex items-start space-x-4 mb-4 lg:mb-0">
                                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                                            {{ substr($caterer->name ?? $caterer->business_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ $caterer->business_name ?? $caterer->name }}
                                            </h3>
                                            <div class="flex items-center space-x-4 mt-2">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="ml-1 text-gray-600 dark:text-gray-400">
                                                        {{ $caterer->average_rating }} • {{ $caterer->review_count }} reviews
                                                    </span>
                                                </div>
                                                @if($caterer->city)
                                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $caterer->city }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a 
                                            href="{{ route('customer.caterer.profile', $caterer->id) }}" 
                                            class="text-green-600 hover:text-green-700 font-medium flex items-center"
                                        >
                                            View all packages
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Packages Grid -->
                                @if($caterer->packages->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($caterer->packages->take(3) as $package)
                                            <div class="group relative bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                                                <!-- Package Image -->
                                                <div class="aspect-w-16 aspect-h-9 bg-gradient-to-r from-gray-300 to-gray-400">
                                                    @if($package->image_path)
                                                        <img src="{{ asset('storage/' . $package->image_path) }}" 
                                                             alt="{{ $package->name }}" 
                                                             class="w-full h-48 object-cover">
                                                    @else
                                                        <div class="w-full h-48 bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="p-4">
                                                    <!-- Package Category Badge -->
                                                    <div class="mb-3">
                                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                            @if(str_contains(strtolower($package->name), 'wedding'))
                                                                Wedding
                                                            @elseif(str_contains(strtolower($package->name), 'corporate'))
                                                                Corporate
                                                            @elseif(str_contains(strtolower($package->name), 'party'))
                                                                Party
                                                            @else
                                                                Event Package
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 transition-colors">
                                                        {{ $package->name }}
                                                    </h4>
                                                    
                                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                                        {{ $package->description }}
                                                    </p>

                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <span class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</span>
                                                            @if($package->pax)
                                                                <span class="text-gray-500 text-sm">/ {{ $package->pax }} pax</span>
                                                            @endif
                                                        </div>
                                                        <a 
                                                            href="{{ route('customer.package.details', [$caterer->id, $package->id]) }}" 
                                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium"
                                                        >
                                                            View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p>No packages available yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $caterers->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- No Results -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                        <svg class="w-20 h-20 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">No caterers found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search criteria or browse all available caterers.</p>
                        <a 
                            href="{{ route('customer.caterers') }}" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            View All Caterers
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for interactive elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterToggle = document.getElementById('filterToggle');
            const advancedFilters = document.getElementById('advancedFilters');
            const filterChevron = document.getElementById('filterChevron');

            filterToggle.addEventListener('click', function() {
                advancedFilters.classList.toggle('hidden');
                filterChevron.classList.toggle('rotate-180');
            });
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</x-app-layout>
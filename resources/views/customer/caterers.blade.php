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
                                <!-- Caterer Header with Profile Photo -->
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                                    <div class="flex items-start space-x-4 mb-4 lg:mb-0">
                                        <!-- Profile Photo -->
                                        <div class="shrink-0">
                                            @if($caterer->profile_photo)
                                                <img src="{{ asset('storage/' . $caterer->profile_photo) }}" 
                                                     alt="{{ $caterer->business_name ?? $caterer->name }}" 
                                                     class="w-20 h-20 object-cover rounded-xl ring-2 ring-green-500">
                                            @else
                                                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                                                    {{ substr($caterer->business_name ?? $caterer->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Business Info -->
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ $caterer->business_name ?? $caterer->name }}
                                            </h3>
                                            
                                            <div class="flex flex-wrap items-center gap-4 mt-2">
                                                <!-- Rating -->
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="ml-1 text-gray-600 dark:text-gray-400 font-medium">
                                                        {{ $caterer->averageRating() }} • {{ $caterer->totalReviews() }} reviews
                                                    </span>
                                                </div>

                                                <!-- Location -->
                                                @if($caterer->business_address || $caterer->city)
                                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $caterer->city ?? explode(',', $caterer->business_address)[0] }}
                                                    </div>
                                                @endif

                                                <!-- Experience -->
                                                @if($caterer->years_of_experience)
                                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $caterer->years_of_experience }} yrs exp
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Cuisine Types -->
                                            @if($caterer->cuisine_types && count($caterer->cuisine_types) > 0)
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach(array_slice($caterer->cuisine_types, 0, 3) as $cuisine)
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                            {{ $cuisine }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Features -->
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @if($caterer->offers_delivery)
                                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                        </svg>
                                                        Delivery
                                                    </span>
                                                @endif
                                                @if($caterer->offers_setup)
                                                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                        </svg>
                                                        Setup
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- View All Button -->
                                    <div class="flex items-center space-x-2">
                                        <a 
                                            href="{{ route('customer.caterer.profile', $caterer->id) }}" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center"
                                        >
                                            View Profile
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Packages Preview Grid -->
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
                                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
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
                                                            @elseif(str_contains(strtolower($package->name), 'birthday'))
                                                                Birthday
                                                            @else
                                                                Event Package
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 transition-colors">
                                                        {{ $package->name }}
                                                    </h4>

                                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                                        {{ Str::limit($package->description, 80) }}
                                                    </p>

                                                    <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                                                        <div>
                                                            <span class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</span>
                                                            @if($package->pax)
                                                                <span class="text-gray-500 text-xs">/ {{ $package->pax }} pax</span>
                                                            @endif
                                                        </div>
                                                        <a 
                                                            href="{{ route('customer.package.details', [$caterer->id, $package->id]) }}" 
                                                            class="text-green-600 hover:text-green-700 font-medium text-sm"
                                                        >
                                                            View →
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($caterer->packages->count() > 3)
                                        <div class="mt-4 text-center">
                                            <a 
                                                href="{{ route('customer.caterer.profile', $caterer->id) }}" 
                                                class="text-green-600 hover:text-green-700 font-medium text-sm inline-flex items-center"
                                            >
                                                + {{ $caterer->packages->count() - 3 }} more packages
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 dark:text-gray-400">No packages available yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if($caterers->hasPages())
                        <div class="mt-8">
                            {{ $caterers->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                        <svg class="w-20 h-20 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No caterers found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters or browse all caterers</p>
                        <a href="{{ route('customer.caterers') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Filter toggle
        document.getElementById('filterToggle').addEventListener('click', function() {
            const filters = document.getElementById('advancedFilters');
            const chevron = document.getElementById('filterChevron');
            
            filters.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });
    </script>
    @endpush
</x-app-layout>
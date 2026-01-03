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
            <!-- Caterer Info Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-600 to-green-800 p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-4xl font-bold backdrop-blur-sm">
                            {{ substr($caterer->business_name ?? $caterer->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold mb-3">{{ $caterer->business_name ?? $caterer->name }}</h1>
                            <div class="flex flex-wrap items-center gap-6 text-lg opacity-90">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-400 fill-current mr-2" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $caterer->average_rating }} ({{ $caterer->review_count }} reviews)
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
                            </div>
                            @if($caterer->bio || $caterer->description)
                                <p class="mt-4 text-lg opacity-90 leading-relaxed">
                                    {{ $caterer->bio ?? $caterer->description }}
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-col space-y-3">
                            <button class="bg-white text-green-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Contact
                            </button>
                            <button class="bg-green-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-800 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Categories Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-8">
                <div class="p-6">
                    <div class="flex flex-wrap gap-4" id="categoryTabs">
                        <button class="category-tab active px-6 py-3 rounded-lg font-medium transition-colors bg-green-100 text-green-700" data-category="all">
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
                            <button class="category-tab px-6 py-3 rounded-lg font-medium transition-colors text-gray-600 hover:bg-gray-100" data-category="{{ strtolower($category) }}">
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
                    <div class="package-card group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300" data-category="{{ $packageCategory }}">
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

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <span class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</span>
                                    @if($package->pax)
                                        <span class="text-gray-500 text-sm">/ {{ $package->pax }} pax</span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Add to favorites">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                    <a 
                                        href="{{ route('customer.package.details', [$caterer->id, $package->id]) }}" 
                                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium"
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
            <div id="emptyState" class="hidden text-center py-12">
                <svg class="w-20 h-20 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No packages in this category</h3>
                <p class="text-gray-600 dark:text-gray-400">Try selecting a different category to see more packages.</p>
            </div>

            {{-- Reviews & Ratings Section --}}
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Customer Reviews</h3>
        @php
            $averageRating = $caterer->averageRating();
            $totalReviews = $caterer->totalReviews();
        @endphp
        <div class="flex items-center">
            <div class="flex text-yellow-400 mr-2">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} text-xl">★</span>
                @endfor
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ number_format($averageRating, 1) }} ({{ $totalReviews }} {{ Str::plural('review', $totalReviews) }})
            </span>
        </div>
    </div>

    @if($totalReviews > 0)
        {{-- Rating Distribution --}}
        <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Rating Distribution</h4>
            @php $distribution = $caterer->ratingDistribution(); @endphp
            <div class="space-y-2">
                @foreach([5, 4, 3, 2, 1] as $rating)
                    @php
                        $count = $distribution[$rating];
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center text-sm">
                        <span class="w-8 text-gray-600 dark:text-gray-400">{{ $rating }}★</span>
                        <div class="flex-1 mx-3">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-yellow-400 h-full transition-all" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <span class="w-12 text-right text-gray-600 dark:text-gray-400">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Reviews --}}
        @php
            $recentReviews = $caterer->approvedReviews()
                ->with(['customer', 'booking'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        @endphp

        <div class="space-y-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Recent Reviews</h4>
            @foreach($recentReviews as $review)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                <span class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                                    {{ substr($review->customer->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $review->customer->name }}
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $review->comment }}
                            </p>
                            
                            @if($review->hasResponse())
                                <div class="mt-3 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                    <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400">
                                        Response from {{ $caterer->business_name ?? $caterer->name }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $review->caterer_response }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($totalReviews > 5)
            <div class="mt-6 text-center">
                <a href="{{ route('customer.caterer.reviews', $caterer->id) }}" 
                   class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    View all {{ $totalReviews }} reviews →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                No reviews yet. Be the first to review this caterer!
            </p>
        </div>
    @endif
</div>
        </div>
    </div>
    

    <!-- JavaScript for category filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryTabs = document.querySelectorAll('.category-tab');
            const packageCards = document.querySelectorAll('.package-card');
            const emptyState = document.getElementById('emptyState');

            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Update active tab
                    categoryTabs.forEach(t => {
                        t.classList.remove('active', 'bg-green-100', 'text-green-700');
                        t.classList.add('text-gray-600', 'hover:bg-gray-100');
                    });
                    this.classList.remove('text-gray-600', 'hover:bg-gray-100');
                    this.classList.add('active', 'bg-green-100', 'text-green-700');
                    
                    // Filter packages
                    let visibleCount = 0;
                    packageCards.forEach(card => {
                        const cardCategory = card.getAttribute('data-category');
                        if (category === 'all' || cardCategory === category) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Show/hide empty state
                    if (visibleCount === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.caterer.profile', $package->user->id) }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $package->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Package Hero -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="relative h-96 bg-gradient-to-r from-gray-300 to-gray-400">
                            @if($package->image_path)
                                <img src="{{ asset('storage/' . $package->image_path) }}" 
                                     alt="{{ $package->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                                    <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-6 left-6">
                                <span class="inline-block px-4 py-2 text-sm font-medium bg-white bg-opacity-90 text-gray-800 rounded-full">
                                    @if(str_contains(strtolower($package->name), 'wedding'))
                                        Wedding Package
                                    @elseif(str_contains(strtolower($package->name), 'corporate'))
                                        Corporate Package
                                    @elseif(str_contains(strtolower($package->name), 'party'))
                                        Party Package
                                    @else
                                        Event Package
                                    @endif
                                </span>
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h1 class="text-4xl font-bold text-white mb-2">{{ $package->name }}</h1>
                                <div class="flex items-center text-white text-lg">
                                    <span class="text-3xl font-bold">₱{{ number_format($package->price, 0) }}</span>
                                    @if($package->pax)
                                        <span class="ml-2 opacity-90">/ {{ $package->pax }} pax</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Package Description</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                            {{ $package->description }}
                        </p>
                    </div>

                    <!-- Menu Items -->
                    @if($package->items->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">What's Included</h2>
                            
                            @php
                                $itemsByCategory = $package->items->groupBy('category.name');
                            @endphp

                            <div class="space-y-8">
                                @foreach($itemsByCategory as $categoryName => $items)
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                            {{ $categoryName ?? 'Uncategorized' }}
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($items as $item)
                                                <div class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                                    <svg class="w-6 h-6 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</h4>
                                                        @if($item->description)
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item->description }}</p>
                                                        @endif
                                                        @if($item->price)
                                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">₱{{ number_format($item->price, 2) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Reviews Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Reviews</h2>
                            <div class="flex items-center">
                                <div class="flex items-center mr-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-gray-600 dark:text-gray-400">4.2 out of 5 (127 reviews)</span>
                            </div>
                        </div>

                        <!-- Sample Reviews -->
                        <div class="space-y-6">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ ['M', 'S', 'J'][$i-1] }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-gray-900 dark:text-white">
                                                    {{ ['Maria Santos', 'Sarah Johnson', 'John Dela Cruz'][$i-1] }}
                                                </h4>
                                                <div class="flex items-center">
                                                    @for($j = 1; $j <= 5; $j++)
                                                        <svg class="w-4 h-4 {{ $j <= [5, 4, 5][$i-1] ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                                @if($i == 1)
                                                    Excellent service and delicious food! The team was very professional and everything was perfectly organized. Highly recommended!
                                                @elseif($i == 2)
                                                    Good food quality and reasonable pricing. The staff was helpful throughout the event. Would book again.
                                                @else
                                                    Amazing experience! The food was outstanding and the presentation was beautiful. Perfect for our wedding celebration.
                                                @endif
                                            </p>
                                            <span class="text-sm text-gray-500 dark:text-gray-500">
                                                {{ ['2 weeks ago', '1 month ago', '3 months ago'][$i-1] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-6 text-center">
                            <button class="text-green-600 hover:text-green-700 font-medium">View all reviews</button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Booking Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                ₱{{ number_format($package->price, 0) }}
                            </div>
                            @if($package->pax)
                                <div class="text-gray-600 dark:text-gray-400">
                                    For {{ $package->pax }} people
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Date</label>
                                <input type="date" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Guests</label>
                                <input type="number" placeholder="{{ $package->pax ?? '50' }}" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type</label>
                                <select class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option>Wedding</option>
                                    <option>Corporate Event</option>
                                    <option>Birthday Party</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button class="w-full bg-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 1v10m-6-6h12"></path>
                                </svg>
                                Book Now
                            </button>
                            <button class="w-full border border-green-600 text-green-600 py-3 px-6 rounded-xl font-semibold hover:bg-green-50 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 3H4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                Add to Cart
                            </button>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <div class="flex justify-between">
                                    <span>Service charge:</span>
                                    <span>Included</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Setup & cleanup:</span>
                                    <span>Included</span>
                                </div>
                                <div class="flex justify-between font-medium text-gray-900 dark:text-white">
                                    <span>Total:</span>
                                    <span>₱{{ number_format($package->price, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Caterer Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About the Caterer</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($package->user->business_name ?? $package->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $package->user->business_name ?? $package->user->name }}
                                </h4>
                                <div class="flex items-center mt-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600 dark:text-gray-400">4.8 • 156 reviews</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($package->user->business_address)
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $package->user->business_address }}
                            </div>
                        @endif

                        <div class="space-y-3">
                            <a 
                                href="{{ route('customer.caterer.profile', $package->user->id) }}" 
                                class="block w-full text-center border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                            >
                                View Profile
                            </a>
                            <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                Contact Caterer
                            </button>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Package Details</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Serves</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $package->pax ?? 'Customizable' }} people</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Service Duration</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">4-6 hours</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Included</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Setup & cleanup</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Payment</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">50% deposit required</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share & Save -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Save</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span>Share</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactive elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any interactive functionality here
            console.log('Package details loaded');
        });
    </script>
</x-app-layout>
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
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

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
                                    @elseif(str_contains(strtolower($package->name), 'birthday'))
                                        Birthday Package
                                    @else
                                        Event Package
                                    @endif
                                </span>
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h1 class="text-4xl font-bold text-white mb-2">{{ $package->name }}</h1>
                                <div class="flex items-center text-white text-lg">
                                    <span class="text-3xl font-bold" id="displayPrice">₱{{ number_format($package->price, 0) }}</span>
                                    <span class="ml-2 opacity-90">/ <span id="displayPax">{{ $package->pax }}</span> pax</span>
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

                    <!-- Customizable Menu Items -->
                    @if($package->items->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Customize Your Menu</h2>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        <span id="selectedCount">{{ $package->items->count() }}</span> items selected
                                    </span>
                                    <button 
                                        onclick="selectAll()" 
                                        class="text-green-600 hover:text-green-700 text-sm font-medium"
                                    >
                                        Select All
                                    </button>
                                    <button 
                                        onclick="deselectAll()" 
                                        class="text-red-600 hover:text-red-700 text-sm font-medium"
                                    >
                                        Deselect All
                                    </button>
                                </div>
                            </div>

                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Check or uncheck items to customize your package. The price will update automatically based on your selections.
                                </p>
                            </div>
                            
                            @php
                                $itemsByCategory = $package->items->groupBy('category.name');
                            @endphp

                            <div class="space-y-8">
                                @foreach($itemsByCategory as $categoryName => $items)
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                                {{ $categoryName ?? 'Uncategorized' }}
                                            </h3>
                                            <button 
                                                onclick="toggleCategory('{{ $categoryName }}')" 
                                                class="text-sm text-green-600 hover:text-green-700 font-medium"
                                            >
                                                Toggle All
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($items as $item)
                                                <div class="menu-item relative p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border-2 border-transparent transition-all hover:border-green-300"
                                                     data-category="{{ $categoryName }}"
                                                     data-item-id="{{ $item->id }}"
                                                     data-item-price="{{ $item->price }}">
                                                    <label class="flex items-start space-x-3 cursor-pointer">
                                                        <input 
                                                            type="checkbox" 
                                                            name="menu_items[]" 
                                                            value="{{ $item->id }}"
                                                            class="menu-item-checkbox w-5 h-5 text-green-600 rounded focus:ring-green-500 mt-1"
                                                            checked
                                                            onchange="updatePrice()"
                                                        >
                                                        <div class="flex-1">
                                                            <div class="flex items-start justify-between">
                                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</h4>
                                                                @if($item->image_path)
                                                                    <img src="{{ asset('storage/' . $item->image_path) }}" 
                                                                         alt="{{ $item->name }}"
                                                                         class="w-16 h-16 object-cover rounded-lg ml-2">
                                                                @endif
                                                            </div>
                                                            @if($item->description)
                                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item->description }}</p>
                                                            @endif
                                                            <p class="text-sm font-medium text-green-600 mt-2">₱{{ number_format($item->price, 2) }}</p>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Price Breakdown -->
                            <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Price Breakdown</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Food Cost:</span>
                                        <span class="font-medium">₱<span id="foodCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Labor & Utilities (20%):</span>
                                        <span class="font-medium">₱<span id="laborCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Equipment & Transport (10%):</span>
                                        <span class="font-medium">₱<span id="equipmentCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Profit Margin (25%):</span>
                                        <span class="font-medium">₱<span id="profitMargin">0</span></span>
                                    </div>
                                    <div class="border-t-2 border-gray-300 dark:border-gray-600 my-3"></div>
                                    <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                        <span>Price per Head:</span>
                                        <span>₱<span id="pricePerHead">{{ number_format($package->price, 0) }}</span></span>
                                    </div>
                                </div>
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
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                ₱<span id="sidebarPrice">{{ number_format($package->price, 0) }}</span>
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                For <span id="sidebarPax">{{ $package->pax }}</span> people
                            </div>
                        </div>

                        <form id="bookingForm" class="space-y-4 mb-6">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="caterer_id" value="{{ $package->user_id }}">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Date *</label>
                                <input 
                                    type="date" 
                                    name="event_date"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Guests *</label>
                                <input 
                                    type="number" 
                                    name="guests"
                                    id="guestCount"
                                    placeholder="{{ $package->pax ?? '50' }}" 
                                    value="{{ $package->pax }}"
                                    min="1"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    onchange="updateTotalPrice()"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type *</label>
                                <select 
                                    name="event_type"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                                    <option value="">Select event type</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Corporate Event">Corporate Event</option>
                                    <option value="Birthday Party">Birthday Party</option>
                                    <option value="Anniversary">Anniversary</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Requests (Optional)</label>
                                <textarea 
                                    name="special_requests"
                                    rows="3"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Any dietary restrictions or special requirements..."
                                ></textarea>
                            </div>
                        </form>

                        <div class="space-y-3">
                            <button 
                                onclick="submitBooking()"
                                class="w-full bg-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Now
                            </button>
                            <button 
                                onclick="addToCart()"
                                class="w-full border border-green-600 text-green-600 py-3 px-6 rounded-xl font-semibold hover:bg-green-50 transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 3H4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                Add to Cart
                            </button>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <div class="flex justify-between">
                                    <span>Price per Head:</span>
                                    <span class="font-medium">₱<span id="perHeadPrice">{{ number_format($package->price, 0) }}</span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Number of Guests:</span>
                                    <span class="font-medium" id="guestDisplay">{{ $package->pax }}</span>
                                </div>
                                <div class="flex justify-between font-medium text-gray-900 dark:text-white text-base">
                                    <span>Total Price:</span>
                                    <span>₱<span id="totalPrice">{{ number_format($package->price * $package->pax, 0) }}</span></span>
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
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            @if($package->user->contact_number)
                                <a 
                                    href="tel:{{ $package->user->contact_number }}"
                                    class="block w-full text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium"
                                >
                                    Contact Caterer
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Share & Save -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Save</span>
                            </button>
                            <button onclick="sharePackage()" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for customization and price calculation -->
    <script>
        // Store original package data
        const originalPrice = {{ $package->price }};
        const originalPax = {{ $package->pax }};
        
        // Calculate price based on selected items
        function updatePrice() {
            const checkboxes = document.querySelectorAll('.menu-item-checkbox:checked');
            let foodCost = 0;
            
            checkboxes.forEach(checkbox => {
                const price = parseFloat(checkbox.closest('.menu-item').dataset.itemPrice);
                foodCost += price;
            });
            
            // Calculate markups
            const laborAndUtilities = foodCost * 0.20;
            const equipmentTransport = foodCost * 0.10;
            const profitMargin = foodCost * 0.25;
            
            // Calculate total price per head
            let pricePerHead = foodCost + laborAndUtilities + equipmentTransport + profitMargin;
            pricePerHead = Math.round(pricePerHead / 5) * 5; // Round to nearest 5
            
            // Update all price displays
            document.getElementById('foodCost').textContent = foodCost.toFixed(2);
            document.getElementById('laborCost').textContent = laborAndUtilities.toFixed(2);
            document.getElementById('equipmentCost').textContent = equipmentTransport.toFixed(2);
            document.getElementById('profitMargin').textContent = profitMargin.toFixed(2);
            document.getElementById('pricePerHead').textContent = pricePerHead.toLocaleString();
            document.getElementById('displayPrice').textContent = '₱' + pricePerHead.toLocaleString();
            document.getElementById('sidebarPrice').textContent = pricePerHead.toLocaleString();
            document.getElementById('perHeadPrice').textContent = pricePerHead.toLocaleString();
            
            // Update selected count
            document.getElementById('selectedCount').textContent = checkboxes.length;
            
            // Update total price
            updateTotalPrice();
            
            // Visual feedback for selected items
            document.querySelectorAll('.menu-item').forEach(item => {
                const checkbox = item.querySelector('.menu-item-checkbox');
                if (checkbox.checked) {
                    item.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900');
                } else {
                    item.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900');
                }
            });
        }
        
        // Update total price based on guest count
        function updateTotalPrice() {
            const guests = parseInt(document.getElementById('guestCount').value) || originalPax;
            const pricePerHead = parseFloat(document.getElementById('pricePerHead').textContent.replace(/,/g, ''));
            const totalPrice = pricePerHead * guests;
            
            document.getElementById('totalPrice').textContent = totalPrice.toLocaleString();
            document.getElementById('guestDisplay').textContent = guests;
            document.getElementById('displayPax').textContent = guests;
            document.getElementById('sidebarPax').textContent = guests;
        }
        
        // Select all items
        function selectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updatePrice();
        }
        
        // Deselect all items
        function deselectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updatePrice();
        }
        
        // Toggle category items
        function toggleCategory(categoryName) {
            const categoryItems = document.querySelectorAll(`.menu-item[data-category="${categoryName}"] .menu-item-checkbox`);
            const allChecked = Array.from(categoryItems).every(cb => cb.checked);
            
            categoryItems.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            updatePrice();
        }
        
        // Submit booking
        function submitBooking() {
            const form = document.getElementById('bookingForm');
            
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get selected items
            const selectedItems = Array.from(document.querySelectorAll('.menu-item-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedItems.length === 0) {
                alert('Please select at least one menu item.');
                return;
            }
            
            // Create form data
            const formData = new FormData(form);
            selectedItems.forEach(item => {
                formData.append('selected_items[]', item);
            });
            
            const pricePerHead = parseFloat(document.getElementById('pricePerHead').textContent.replace(/,/g, ''));
            const guests = parseInt(document.getElementById('guestCount').value);
            
            formData.append('price_per_head', pricePerHead);
            formData.append('total_price', pricePerHead * guests);
            
            // For now, show success message
            // In production, you would send this to your backend
            alert('Booking feature coming soon! Your customized package:\n' +
                  'Items: ' + selectedItems.length + '\n' +
                  'Price per head: ₱' + pricePerHead.toLocaleString() + '\n' +
                  'Guests: ' + guests + '\n' +
                  'Total: ₱' + (pricePerHead * guests).toLocaleString());
            
            console.log('Booking data:', Object.fromEntries(formData));
        }
        
        // Add to cart
        function addToCart() {
            const selectedItems = Array.from(document.querySelectorAll('.menu-item-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedItems.length === 0) {
                alert('Please select at least one menu item.');
                return;
            }
            
            const pricePerHead = parseFloat(document.getElementById('pricePerHead').textContent.replace(/,/g, ''));
            const guests = parseInt(document.getElementById('guestCount').value) || originalPax;
            
            // Store in localStorage (temporary solution)
            const cartItem = {
                package_id: {{ $package->id }},
                caterer_id: {{ $package->user_id }},
                package_name: '{{ $package->name }}',
                caterer_name: '{{ $package->user->business_name ?? $package->user->name }}',
                selected_items: selectedItems,
                price_per_head: pricePerHead,
                guests: guests,
                total_price: pricePerHead * guests,
                added_at: new Date().toISOString()
            };
            
            // Get existing cart
            let cart = JSON.parse(localStorage.getItem('catering_cart') || '[]');
            cart.push(cartItem);
            localStorage.setItem('catering_cart', JSON.stringify(cart));
            
            alert('Package added to cart!');
            console.log('Cart:', cart);
        }
        
        // Share package
        function sharePackage() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $package->name }}',
                    text: 'Check out this catering package from {{ $package->user->business_name ?? $package->user->name }}',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }
        
        // Initialize price calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePrice();
        });
    </script>

    <style>
        .menu-item {
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .menu-item-checkbox:checked + div {
            opacity: 1;
        }
        
        .menu-item-checkbox:not(:checked) + div {
            opacity: 0.7;
        }
    </style>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                {{-- Back link — goes back to packages list --}}
                @auth
                    <a href="{{ route('customer.packages') }}"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Back to packages">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <nav class="flex items-center gap-1 text-sm text-gray-400 dark:text-gray-500">
                        <a href="{{ route('customer.packages') }}" class="hover:text-green-600 dark:hover:text-green-400 transition-colors">Packages</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('customer.caterer.profile', $package->user->id) }}"
                            class="hover:text-green-600 dark:hover:text-green-400 transition-colors truncate max-w-[120px]">
                            {{ $package->user->business_name ?? $package->user->name }}
                        </a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-300 font-medium truncate max-w-[160px]">{{ $package->name }}</span>
                    </nav>
                @else
                    <a href="{{ route('browse.packages') }}"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Back to packages">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <nav class="flex items-center gap-1 text-sm text-gray-400 dark:text-gray-500">
                        <a href="{{ route('browse.packages') }}" class="hover:text-green-600 dark:hover:text-green-400 transition-colors">Packages</a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('browse.caterer.profile', $package->user->id) }}"
                            class="hover:text-green-600 dark:hover:text-green-400 transition-colors truncate max-w-[120px]">
                            {{ $package->user->business_name ?? $package->user->name }}
                        </a>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-300 font-medium truncate max-w-[160px]">{{ $package->name }}</span>
                    </nav>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- ═══════════════════════════════════════════════
                     MAIN CONTENT
                ═══════════════════════════════════════════════ -->
                <div class="lg:col-span-2 space-y-8">

                        <!-- Package Hero -->
                    <div class="px-2 sm:px-0">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                            <div class="relative h-96 bg-gradient-to-r from-gray-300 to-gray-400">
                                @if($package->image_path)
                                    <img src="{{ $package->image_path }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
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
                            @php
                                $defaultItemIds = $package->items->filter(fn($i) => $i->pivot->is_default)->pluck('id')->toArray();
                                $defaultCount   = count($defaultItemIds);
                            @endphp

                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Customize Your Menu</h2>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            <span id="selectedCount">{{ $defaultCount }}</span> items selected
                                        </span>
                                        <button type="button" onclick="selectAll()" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">Select All</button>
                                        <button type="button" onclick="deselectAll()" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">Deselect All</button>
                                    </div>
                                </div>

                                <!-- Legend -->
                                <div class="mb-4 flex flex-wrap gap-3 text-xs">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-full text-green-700 dark:text-green-400 font-medium">
                                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                                        Default — included in base price
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-full text-blue-700 dark:text-blue-400 font-medium">
                                        <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                                        Optional — add or remove as you like
                                    </span>
                                </div>

                                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Green items are included by default in the base price.
                                        You can remove them or add optional blue items to customize your menu.
                                    </p>
                                </div>

                                <div class="space-y-4">
                                    @foreach($package->items->groupBy('category.name') as $categoryName => $items)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 px-5 py-3 flex items-center justify-between cursor-pointer hover:from-green-100 hover:to-emerald-100 dark:hover:from-green-800 dark:hover:to-emerald-800 transition-colors"
                                                onclick="toggleCategory('{{ $categoryName }}')">
                                                <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                                    @if(str_contains(strtolower($categoryName), 'appetizer'))
                                                        <span class="text-xl">🥗</span>
                                                    @elseif(str_contains(strtolower($categoryName), 'main') || str_contains(strtolower($categoryName), 'entree'))
                                                        <span class="text-xl">🍽️</span>
                                                    @elseif(str_contains(strtolower($categoryName), 'dessert'))
                                                        <span class="text-xl">🍰</span>
                                                    @elseif(str_contains(strtolower($categoryName), 'beverage') || str_contains(strtolower($categoryName), 'drink'))
                                                        <span class="text-xl">🥤</span>
                                                    @else
                                                        <span class="text-xl">🍴</span>
                                                    @endif
                                                    {{ $categoryName }}
                                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400">({{ $items->count() }} items)</span>
                                                </h3>
                                                <span class="text-green-600 dark:text-green-400 font-medium text-sm">Click to toggle all</span>
                                            </div>
                                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($items as $item)
                                                    @php
                                                        $isDefault = $item->pivot->is_default ?? false;
                                                    @endphp
                                                    <label class="menu-item relative block p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $isDefault ? 'bg-green-50 dark:bg-green-900/10 border-green-400 dark:border-green-600' : 'bg-gray-50 dark:bg-gray-700 border-transparent' }} hover:shadow-md"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-price="{{ $item->price }}"
                                                        data-is-default="{{ $isDefault ? '1' : '0' }}"
                                                        data-category="{{ $categoryName }}">
                                                        <div class="flex items-start gap-3">
                                                            <input type="checkbox"
                                                                class="menu-item-checkbox mt-1 w-5 h-5 text-green-600 rounded focus:ring-2 focus:ring-green-500"
                                                                {{ $isDefault ? 'checked' : '' }}
                                                                onchange="updatePrice()">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-start justify-between gap-2">
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $item->name }}</p>
                                                                        @if($item->description)
                                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $item->description }}</p>
                                                                        @endif
                                                                    </div>
                                                                    @if($isDefault)
                                                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full border border-green-200 dark:border-green-700 flex-shrink-0">
                                                                            Default
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="mt-2 flex items-center justify-between">
                                                                    <span class="text-sm font-medium text-green-600 dark:text-green-400">₱{{ number_format($item->price, 2) }}</span>
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500">per serving</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Price Breakdown — now uses real costing data from caterer -->
                                <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        💰 Price Breakdown
                                        @if($hasCosting)
                                            <span class="text-xs font-normal text-green-600 dark:text-green-400">(Based on caterer's actual costs)</span>
                                        @else
                                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">(Estimated)</span>
                                        @endif
                                    </h3>
                                    <div class="space-y-2 text-sm" id="costBreakdownContainer">
                                        @if($hasCosting && $costing->cost_breakdown)
                                            {{-- Display actual cost components from caterer's costing --}}
                                            @foreach($costing->cost_breakdown as $component)
                                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                                    <span>{{ $component['label'] }}:</span>
                                                    <span class="font-medium" data-cost-type="{{ strtolower(str_replace(' ', '_', $component['label'])) }}">
                                                        ₱<span class="cost-value">{{ number_format($component['amount'], 2) }}</span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format($component['percent'], 1) }}%)</span>
                                                    </span>
                                                </div>
                                            @endforeach
                                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                                <span>Profit Margin ({{ number_format($costing->profit_margin_percent, 0) }}%):</span>
                                                <span class="font-medium">₱<span id="profitMargin">{{ number_format($costing->profit_amount, 2) }}</span></span>
                                            </div>
                                        @else
                                            {{-- Fallback: Show simplified breakdown when no costing exists --}}
                                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                                <span>Food Cost:</span>
                                                <span class="font-medium">₱<span id="foodCost">0.00</span></span>
                                            </div>
                                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                                <span>Labor & Other Costs:</span>
                                                <span class="font-medium">₱<span id="otherCosts">0.00</span></span>
                                            </div>
                                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                                <span>Service Fee:</span>
                                                <span class="font-medium">₱<span id="profitMargin">0.00</span></span>
                                            </div>
                                        @endif
                                        <div class="border-t-2 border-gray-300 dark:border-gray-600 my-3"></div>
                                        <div class="flex justify-between text-base font-semibold text-gray-900 dark:text-white">
                                            <span>Price per Head:</span>
                                            <span>₱<span id="pricePerHead">{{ number_format($package->price, 0) }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════
                     SIDEBAR
                ═══════════════════════════════════════════════ -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">

                        <!-- Total Price Hero -->
                        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white mb-6">
                            <div class="text-center">
                                <p class="text-sm font-medium opacity-90 mb-2">Total Package Price</p>
                                <div class="text-5xl font-bold mb-2">
                                    ₱<span id="totalPriceMain">{{ number_format($package->price * $package->pax, 0) }}</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    <span id="perHeadPriceDisplay">₱{{ number_format($package->price, 0) }}</span> per head ×
                                    <span id="guestCountDisplay">{{ $package->pax }}</span> guests
                                </div>
                            </div>
                        </div>

                        {{-- Booking form — only rendered for logged-in customers --}}
                        @auth
                        <form id="bookingForm" action="{{ route('customer.booking.store-event') }}" method="POST" class="space-y-4 mb-6">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="caterer_id" value="{{ $package->user_id }}">
                            <input type="hidden" name="price_per_head" id="hiddenPricePerHead" value="{{ $package->price }}">
                            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="{{ $package->price * $package->pax }}">
                            <input type="hidden" name="selected_items_json" id="selectedItemsJson">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Guests *</label>
                                <input type="number" name="guests" id="guestCount"
                                    placeholder="{{ $package->pax }}" value="{{ $package->pax }}"
                                    min="1" max="1000"
                                    class="w-full px-4 py-3 text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum package serves {{ $package->pax }} guests</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type *</label>
                                <select name="event_type" class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Select event type</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Corporate Event">Corporate Event</option>
                                    <option value="Birthday Party">Birthday Party</option>
                                    <option value="Anniversary">Anniversary</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Date *</label>
                                <input type="date" name="event_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Slot *</label>
                                <select name="time_slot" class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Select time slot</option>
                                    <option value="Morning (6:00 AM - 12:00 PM)">Morning (6:00 AM - 12:00 PM)</option>
                                    <option value="Afternoon (12:00 PM - 6:00 PM)">Afternoon (12:00 PM - 6:00 PM)</option>
                                    <option value="Evening (6:00 PM - 12:00 AM)">Evening (6:00 PM - 12:00 AM)</option>
                                    <option value="Whole Day (6:00 AM - 12:00 AM)">Whole Day (6:00 AM - 12:00 AM)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue Name *</label>
                                <input type="text" name="venue_name" placeholder="e.g., Grand Ballroom, Home Address, etc."
                                    class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue Address *</label>
                                <textarea name="venue_address" rows="2" placeholder="Full address of the venue"
                                    class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Instructions (Optional)</label>
                                <textarea name="special_instructions" rows="3"
                                    class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Any dietary restrictions or special requirements..."></textarea>
                            </div>
                        </form>
                        @endauth

                        {{-- Guest count display (read-only, no form) --}}
                        @guest
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Guests</label>
                                <input type="number" id="guestCount"
                                    placeholder="{{ $package->pax }}" value="{{ $package->pax }}"
                                    min="1" max="1000"
                                    class="w-full px-4 py-3 text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Adjust to estimate total price</p>
                            </div>
                            <input type="hidden" id="hiddenPricePerHead" value="{{ $package->price }}">
                            <input type="hidden" id="hiddenTotalPrice" value="{{ $package->price * $package->pax }}">
                            <input type="hidden" id="selectedItemsJson">
                        </div>
                        @endguest

                        <div class="space-y-3">
                            <button type="button" id="bookNowBtn"
                                class="w-full bg-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center text-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Now
                            </button>

                            @auth
                            <a href="{{ route('customer.packages') }}"
                                class="block w-full text-center border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Back to Packages
                            </a>
                            @else
                            <a href="{{ route('browse.packages') }}"
                                class="block w-full text-center border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Back to Packages
                            </a>
                            @endauth
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <div class="flex justify-between">
                                    <span>Deposit Required (25%):</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">₱<span id="depositAmount">{{ number_format(($package->price * $package->pax) * 0.25, 0) }}</span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Service Fee:</span>
                                    <span class="font-medium">₱500</span>
                                </div>
                                <div class="flex justify-between font-semibold text-base text-gray-900 dark:text-white">
                                    <span>Due Now:</span>
                                    <span class="text-green-600 dark:text-green-400">₱<span id="dueNow">{{ number_format((($package->price * $package->pax) * 0.25) + 500, 0) }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Caterer Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-5 py-4">
                            <p class="text-green-100 text-xs font-semibold uppercase tracking-wider mb-2">Package offered by</p>
                            <div class="flex items-center gap-3">
                                @if($package->user->profile_photo)
                                    <img src="{{ $package->user->profile_photo }}"
                                        alt="{{ $package->user->business_name ?? $package->user->name }}"
                                        class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/40 shrink-0">
                                @else
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-lg font-bold shrink-0">
                                        {{ substr($package->user->business_name ?? $package->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h4 class="font-bold text-white text-base leading-tight truncate">
                                        {{ $package->user->business_name ?? $package->user->name }}
                                    </h4>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-yellow-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-green-100 text-xs">{{ $package->user->averageRating() }} · {{ $package->user->totalReviews() }} reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 space-y-3">
                            @if($package->user->business_address)
                                <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $package->user->business_address }}
                                </div>
                            @endif

                            @if($package->user->years_of_experience)
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $package->user->years_of_experience }} years of experience
                                </div>
                            @endif

                            <div class="flex flex-col gap-2 pt-1">
                                @auth
                                <a href="{{ route('customer.caterer.profile', $package->user->id) }}"
                                    class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-xl font-semibold text-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    View Full Profile
                                </a>
                                @else
                                <a href="{{ route('browse.caterer.profile', $package->user->id) }}"
                                    class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-xl font-semibold text-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    View Full Profile
                                </a>
                                @endauth

                                @if($package->user->contact_number ?? $package->user->phone ?? null)
                                    <a href="tel:{{ $package->user->contact_number ?? $package->user->phone }}"
                                        class="flex items-center justify-center gap-2 w-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 px-4 rounded-xl font-medium text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        Call Caterer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- END SIDEBAR --}}

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         LOGIN PROMPT MODAL — shown to guests when they click Book Now
    ══════════════════════════════════════════════════════════════ --}}
    @guest
    <div id="loginPromptModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sign In to Book</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                You need an account to reserve this package.<br>It's free and takes less than a minute!
            </p>
            <div class="space-y-3">
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-colors">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                    class="block w-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Create Free Account
                </a>
                <button onclick="document.getElementById('loginPromptModal').classList.add('hidden')"
                    class="block w-full text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 py-2 transition-colors">
                    Continue Browsing
                </button>
            </div>
        </div>
    </div>
    @endguest

    @php
        $itemCount  = $package->items->count();
        $hasCosting = $costing && $costing->total_cost > 0;
        // Pass default item IDs to JS so price recalculation uses default items on first load
        $defaultItemIds = $package->items->filter(fn($i) => $i->pivot->is_default)->pluck('id')->toArray();
        
        // Prepare costing data for JavaScript
        $costingData = $hasCosting ? [
            'has_costing' => true,
            'ingredient_cost' => $costing->ingredient_cost ?? 0,
            'labor_cost' => $costing->labor_cost ?? 0,
            'equipment_cost' => $costing->equipment_cost ?? 0,
            'consumables_cost' => $costing->consumables_cost ?? 0,
            'overhead_cost' => $costing->overhead_cost ?? 0,
            'transport_cost' => $costing->transport_cost ?? 0,
            'profit_margin_percent' => $costing->profit_margin_percent ?? 25,
            'total_cost' => $costing->total_cost,
            'breakdown' => $costing->cost_breakdown,
        ] : [
            'has_costing' => false,
        ];
    @endphp

    <script>
        const originalPrice   = {{ $package->price }};
        const originalPax     = {{ $package->pax }};
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const defaultItemIds  = @json($defaultItemIds);
        const costingData     = @json($costingData);

        const elements = {
            selectedCount:       document.getElementById('selectedCount'),
            pricePerHead:        document.getElementById('pricePerHead'),
            perHeadPriceDisplay: document.getElementById('perHeadPriceDisplay'),
            totalPriceMain:      document.getElementById('totalPriceMain'),
            guestCountDisplay:   document.getElementById('guestCountDisplay'),
            depositAmount:       document.getElementById('depositAmount'),
            dueNow:              document.getElementById('dueNow'),
            guestCount:          document.getElementById('guestCount'),
            hiddenPricePerHead:  document.getElementById('hiddenPricePerHead'),
            hiddenTotalPrice:    document.getElementById('hiddenTotalPrice'),
            selectedItemsJson:   document.getElementById('selectedItemsJson'),
            bookingForm:         document.getElementById('bookingForm'),
        };

        function updatePrice() {
            const checkboxes  = Array.from(document.querySelectorAll('.menu-item-checkbox:checked'));
            const menuItems   = checkboxes.map(cb => cb.closest('.menu-item'));

            if (elements.selectedCount) elements.selectedCount.textContent = checkboxes.length;

            let foodCost = 0;
            menuItems.forEach(function (menuItem) {
                if (menuItem && menuItem.dataset.itemPrice) {
                    foodCost += parseFloat(menuItem.dataset.itemPrice || 0);
                }
            });

            let pricePerHead = 0;

            if (costingData.has_costing) {
                // Use actual caterer's costing structure
                const totalCost = costingData.total_cost;
                const profitMarginPercent = costingData.profit_margin_percent;
                
                // Calculate proportional costs based on food cost ratio
                const baseFoodCost = costingData.ingredient_cost || 0;
                const ratio = baseFoodCost > 0 ? foodCost / baseFoodCost : 1;
                
                // Scale all costs proportionally
                const ingredientCost = (costingData.ingredient_cost || 0) * ratio;
                const laborCost = (costingData.labor_cost || 0) * ratio;
                const equipmentCost = (costingData.equipment_cost || 0) * ratio;
                const consumablesCost = (costingData.consumables_cost || 0) * ratio;
                const overheadCost = (costingData.overhead_cost || 0) * ratio;
                const transportCost = (costingData.transport_cost || 0) * ratio;
                
                const scaledTotalCost = ingredientCost + laborCost + equipmentCost + consumablesCost + overheadCost + transportCost;
                const profitAmount = scaledTotalCost * (profitMarginPercent / 100);
                
                pricePerHead = scaledTotalCost + profitAmount;
                
                // Update breakdown display values
                document.querySelectorAll('.cost-value').forEach(function(el) {
                    const parent = el.closest('[data-cost-type]');
                    if (parent) {
                        const costType = parent.dataset.costType;
                        let value = 0;
                        
                        // Map cost types to their values
                        if (costType.includes('ingredient') || costType.includes('raw_food')) {
                            value = ingredientCost;
                        } else if (costType.includes('labor') || costType.includes('staffing')) {
                            value = laborCost;
                        } else if (costType.includes('equipment') || costType.includes('rental')) {
                            value = equipmentCost;
                        } else if (costType.includes('consumable') || costType.includes('packaging')) {
                            value = consumablesCost;
                        } else if (costType.includes('overhead') || costType.includes('utilities')) {
                            value = overheadCost;
                        } else if (costType.includes('transport') || costType.includes('logistics')) {
                            value = transportCost;
                        }
                        
                        el.textContent = value.toFixed(2);
                    }
                });
                
                // Update profit margin
                const profitMarginEl = document.getElementById('profitMargin');
                if (profitMarginEl) {
                    profitMarginEl.textContent = profitAmount.toFixed(2);
                }
                
            } else {
                // Fallback: Simple calculation when no costing exists
                const otherCosts = foodCost * 0.30; // 30% for labor and other costs
                const profitMargin = foodCost * 0.25; // 25% profit
                
                pricePerHead = foodCost + otherCosts + profitMargin;
                
                const foodCostEl = document.getElementById('foodCost');
                const otherCostsEl = document.getElementById('otherCosts');
                const profitMarginEl = document.getElementById('profitMargin');
                
                if (foodCostEl) foodCostEl.textContent = foodCost.toFixed(2);
                if (otherCostsEl) otherCostsEl.textContent = otherCosts.toFixed(2);
                if (profitMarginEl) profitMarginEl.textContent = profitMargin.toFixed(2);
            }

            pricePerHead = Math.ceil(pricePerHead / 5) * 5; // Round to nearest 5

            if (elements.pricePerHead) elements.pricePerHead.textContent = pricePerHead.toFixed(0);
            if (elements.hiddenPricePerHead) elements.hiddenPricePerHead.value = pricePerHead;

            updateTotalPrice();

            // Update card border/background to reflect checked state
            document.querySelectorAll('.menu-item').forEach(function (item) {
                const checkbox  = item.querySelector('.menu-item-checkbox');
                const isDefault = item.dataset.isDefault === '1';
                if (checkbox && checkbox.checked) {
                    if (isDefault) {
                        item.classList.add('bg-green-50', 'dark:bg-green-900/10', 'border-green-400', 'dark:border-green-600');
                        item.classList.remove('border-transparent', 'bg-gray-50', 'dark:bg-gray-700');
                    } else {
                        item.classList.add('bg-blue-50', 'dark:bg-blue-900/10', 'border-blue-400', 'dark:border-blue-600');
                        item.classList.remove('border-transparent', 'bg-gray-50', 'dark:bg-gray-700');
                    }
                } else {
                    item.classList.remove(
                        'bg-green-50', 'dark:bg-green-900/10', 'border-green-400', 'dark:border-green-600',
                        'bg-blue-50',  'dark:bg-blue-900/10',  'border-blue-400',  'dark:border-blue-600'
                    );
                    item.classList.add('bg-gray-50', 'dark:bg-gray-700', 'border-transparent');
                }
            });
        }

        function updateTotalPrice() {
            const guests       = parseInt(elements.guestCount && elements.guestCount.value) || originalPax;
            const pricePerHead = parseFloat(elements.hiddenPricePerHead && elements.hiddenPricePerHead.value) || originalPrice;
            const totalPrice   = pricePerHead * guests;

            if (elements.totalPriceMain)    elements.totalPriceMain.textContent    = totalPrice.toLocaleString();
            if (elements.guestCountDisplay) elements.guestCountDisplay.textContent = guests;
            if (elements.hiddenTotalPrice)  elements.hiddenTotalPrice.value        = totalPrice;
            if (elements.perHeadPriceDisplay) elements.perHeadPriceDisplay.textContent = '₱' + pricePerHead.toLocaleString();

            const deposit = totalPrice * 0.25;
            const dueNow  = deposit + 500;
            if (elements.depositAmount) elements.depositAmount.textContent = deposit.toLocaleString();
            if (elements.dueNow)        elements.dueNow.textContent        = dueNow.toLocaleString();
        }

        function selectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(function (cb) { cb.checked = true; });
            updatePrice();
        }

        function deselectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(function (cb) { cb.checked = false; });
            updatePrice();
        }

        function toggleCategory(categoryName) {
            const categoryItems = document.querySelectorAll(
                '.menu-item[data-category="' + categoryName + '"] .menu-item-checkbox'
            );
            const allChecked = Array.from(categoryItems).every(function (cb) { return cb.checked; });
            categoryItems.forEach(function (cb) { cb.checked = !allChecked; });
            updatePrice();
        }

        function submitBooking() {
            if (!elements.bookingForm) return;

            if (!elements.bookingForm.checkValidity()) {
                elements.bookingForm.reportValidity();
                return;
            }

            const selectedItems = Array.from(document.querySelectorAll('.menu-item-checkbox:checked'))
                .map(function (cb) { return cb.closest('.menu-item').dataset.itemId; });

            if (selectedItems.length === 0) {
                alert('Please select at least one menu item.');
                return;
            }

            if (elements.selectedItemsJson) {
                elements.selectedItemsJson.value = JSON.stringify(selectedItems);
            }

            // Remove previously appended hidden inputs to avoid duplicates
            elements.bookingForm.querySelectorAll('input[name="selected_items[]"]').forEach(function (el) { el.remove(); });

            selectedItems.forEach(function (itemId) {
                const input   = document.createElement('input');
                input.type    = 'hidden';
                input.name    = 'selected_items[]';
                input.value   = itemId;
                elements.bookingForm.appendChild(input);
            });

            elements.bookingForm.submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Run once to set correct initial state based on which checkboxes are pre-checked
            updatePrice();

            if (elements.guestCount) {
                elements.guestCount.addEventListener('input', updateTotalPrice);
            }

            // Book Now button
            const bookNowBtn = document.getElementById('bookNowBtn');
            if (bookNowBtn) {
                bookNowBtn.addEventListener('click', function () {
                    if (isAuthenticated) {
                        submitBooking();
                    } else {
                        document.getElementById('loginPromptModal').classList.remove('hidden');
                    }
                });
            }

            // Close login modal when clicking the backdrop
            const modal = document.getElementById('loginPromptModal');
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            }
        });
    </script>

    <style>
        .menu-item { transition: all 0.3s ease; }
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        #totalPriceMain, #perHeadPriceDisplay, #guestCountDisplay, #depositAmount, #dueNow {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
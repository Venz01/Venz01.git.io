<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            @guest
            <a href="{{ route('welcome') }}"
                class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            @endguest
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Browse Packages') }}
            </h2>
        </div>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════════════
         DIETARY PREFERENCES SLIDE-OUT PANEL
    ══════════════════════════════════════════════════════════════ --}}
    <div id="dietaryPanel"
        class="fixed inset-y-0 right-0 z-50 w-full sm:w-[420px] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div id="dietaryBackdrop"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-none"
            onclick="closeDietaryPanel()"></div>

        <div class="relative h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col border-l border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-600 to-green-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg leading-tight">Dietary Preferences</h2>
                        <p class="text-green-100 text-xs">Personalize your package recommendations</p>
                    </div>
                </div>
                <button onclick="closeDietaryPanel()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-white/70 hover:text-white hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" />
                    </svg>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Select your dietary needs. Compatible packages will be highlighted with a
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">✓ green badge</span>
                        and conflicts with a
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">⚠ red badge</span>.
                    </p>
                </div>

                <form id="dietaryPanelForm" method="POST" action="{{ route('profile.dietary.update') }}">
                    @csrf
                    @method('PATCH')
                    <div id="orderedPrefsContainer"></div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Dietary Preferences</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Select all that apply — packages will be flagged accordingly.</p>

                        @php
                            $savedPreferences = [];
                            if (auth()->check() && auth()->user()->dietary_preferences) {
                                $savedPreferences = is_array(auth()->user()->dietary_preferences) ? auth()->user()->dietary_preferences : [];
                            }
                            $allTags = \App\Models\DietaryTag::orderBy('name')->get();
                            if (!empty($savedPreferences)) {
                                $allTags = $allTags->sortBy(function ($tag) use ($savedPreferences) {
                                    $pos = array_search($tag->slug, $savedPreferences);
                                    return $pos !== false ? $pos : 10000 + strlen($tag->name . $tag->slug);
                                })->values();
                            }
                            $matchedTags   = $allTags->filter(fn($t) => in_array($t->slug, $savedPreferences));
                            $unmatchedTags = $allTags->filter(fn($t) => !in_array($t->slug, $savedPreferences));
                        @endphp

                        @if($allTags->count() > 0)
                            @if($matchedTags->count() > 0)
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <span class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">✓ Your selections</span>
                                        <div class="flex-1 h-px bg-green-200 dark:bg-green-800"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2.5">
                                        @foreach($matchedTags as $tag)
                                            <label class="dietary-panel-option relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none border-green-500 bg-green-50 dark:bg-green-900/30 shadow-sm ring-1 ring-green-400/40"
                                                id="panel-label-{{ $tag->slug }}">
                                                <input type="checkbox" name="dietary_preferences[]" value="{{ $tag->slug }}"
                                                    checked class="panel-dietary-checkbox sr-only" onchange="togglePanelDietaryCard(this)">
                                                <span class="text-2xl leading-none">{{ $tag->icon }}</span>
                                                <span class="text-xs font-medium text-center text-gray-700 dark:text-gray-300 leading-tight">{{ $tag->name }}</span>
                                                <span class="panel-dietary-check absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-green-500 flex items-center justify-center opacity-100 scale-100 transition-all duration-200">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($unmatchedTags->count() > 0)
                                @if($matchedTags->count() > 0)
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Other options</span>
                                        <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                                    </div>
                                @endif
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach($unmatchedTags as $tag)
                                        <label class="dietary-panel-option relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-green-300 hover:bg-green-50/50"
                                            id="panel-label-{{ $tag->slug }}">
                                            <input type="checkbox" name="dietary_preferences[]" value="{{ $tag->slug }}"
                                                class="panel-dietary-checkbox sr-only" onchange="togglePanelDietaryCard(this)">
                                            <span class="text-2xl leading-none">{{ $tag->icon }}</span>
                                            <span class="text-xs font-medium text-center text-gray-700 dark:text-gray-300 leading-tight">{{ $tag->name }}</span>
                                            <span class="panel-dietary-check absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-green-500 flex items-center justify-center opacity-0 scale-0 transition-all duration-200">
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 italic">No dietary tags are currently available.</p>
                        @endif
                    </div>
                </form>

                @if(!empty($savedPreferences))
                    <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                        <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wider mb-2">Active Filters</p>
                        <div class="flex flex-wrap gap-1.5" id="activePrefsSummary">
                            @php $labels = \App\Models\User::dietaryLabels(); @endphp
                            @foreach($savedPreferences as $pref)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 rounded-full text-xs font-medium border border-green-200 dark:border-green-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $labels[$pref] ?? ucfirst(str_replace('_', ' ', $pref)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 space-y-3">
                <div id="dietarySaveSuccess"
                    class="hidden flex items-center gap-2 text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Preferences saved! Refreshing…
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="clearDietaryPreferences()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Clear All
                    </button>
                    <button type="button" onclick="saveDietaryPreferences()" id="dietarySaveBtn"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 active:bg-green-800 rounded-xl transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Preferences
                    </button>
                </div>
                <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                    Manage in your <a href="{{ route('profile.edit') }}" class="text-green-600 hover:underline">profile settings</a>.
                </p>
            </div>
        </div>
    </div>
    {{-- ── END Dietary Panel ── --}}

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    

            {{-- ══════════════════════════════════════════════════════════════
                HERO + SEARCH BAR
            ════════════════════════════════════════════════════════════════ --}}
            <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 rounded-3xl p-4 sm:p-8 mb-6 sm:mb-8 text-white overflow-hidden">
                <div class="absolute -top-4 sm:-top-10 -right-4 sm:-right-10 w-32 sm:w-64 h-32 sm:h-64 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-8 sm:-bottom-16 -left-2 sm:-left-8 w-48 sm:w-80 h-48 sm:h-80 bg-black/10 rounded-full"></div>

                <div class="relative z-10">
                    <div class="max-w-2xl mb-4 sm:mb-6">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 tracking-tight">Find Your Perfect Package</h1>
                        <p class="text-green-100 text-sm sm:text-base lg:text-lg">Browse {{ $packages->total() }} catering packages from verified local caterers. Filter by price, event type, or your dietary needs.</p>
                    </div>

                    <form method="GET" action="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}" id="searchForm">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <div class="flex-1 relative">
                                <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 sm:w-5 h-4 sm:h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search packages, cuisines, caterers…"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3.5 rounded-xl text-gray-900 dark:text-white dark:bg-gray-800 border-0 focus:ring-2 focus:ring-white/60 text-base placeholder-gray-400 shadow-lg">
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-2 sm:pt-0">
                                <button type="submit"
                                    class="bg-white text-green-700 px-4 sm:px-6 py-2.5 sm:py-3.5 rounded-xl font-semibold hover:bg-green-50 active:bg-green-100 transition-colors flex items-center justify-center gap-2 shadow-lg shrink-0 w-full sm:w-auto">
                                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Search
                                </button>

                                <button type="button" id="filterToggle"
                                    class="bg-green-800/60 hover:bg-green-800/80 border border-white/20 text-white px-4 sm:px-5 py-2.5 sm:py-3.5 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2 shrink-0 w-full sm:w-auto">
                                    <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filters
                                    @php
                                        $activeFilterCount = collect(['search','location','cuisine','event_type','min_price','max_price','sort'])
                                            ->filter(fn($k) => request($k) && request($k) !== 'default')->count();
                                    @endphp
                                    @if($activeFilterCount > 0)
                                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $activeFilterCount }}</span>
                                    @else
                                        <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 transition-transform" id="filterChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </button>

                                @auth
                                    @if(auth()->user()->isCustomer())
                                        <button type="button" onclick="openDietaryPanel()" id="dietaryToggleBtn"
                                            class="relative bg-white/10 hover:bg-white/20 border border-white/30 hover:border-white/50 text-white px-4 sm:px-5 py-2.5 sm:py-3.5 rounded-xl font-semibold transition-all flex items-center justify-center gap-2 shrink-0 w-full sm:w-auto">
                                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span class="hidden sm:inline">Dietary</span>
                                            @if(auth()->user()->hasDietaryPreferences())
                                                <span class="absolute -top-0.5 sm:-top-1.5 -right-0.5 sm:-right-1.5 w-3.5 sm:w-4 h-3.5 sm:h-4 bg-yellow-400 rounded-full border-2 border-green-700 flex items-center justify-center text-green-900 font-bold" style="font-size:7px">
                                                    {{ count(auth()->user()->dietary_preferences) }}
                                                </span>
                                            @endif
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- Collapsible Filters Panel --}}
                        <div id="advancedFilters" class="hidden mt-4 p-4 sm:p-5 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-green-100 mb-1.5 uppercase tracking-wider">Location</label>
                                    <input type="text" name="location" value="{{ request('location') }}"
                                        placeholder="City or area"
                                        class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0 focus:ring-2 focus:ring-white/60">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-green-100 mb-1.5 uppercase tracking-wider">Cuisine</label>
                                    <select name="cuisine" class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0">
                                        <option value="">All Cuisines</option>
                                        <option value="Filipino" {{ request('cuisine') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                        <option value="International" {{ request('cuisine') == 'International' ? 'selected' : '' }}>International</option>
                                        <option value="Asian" {{ request('cuisine') == 'Asian' ? 'selected' : '' }}>Asian</option>
                                        <option value="Western" {{ request('cuisine') == 'Western' ? 'selected' : '' }}>Western</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-green-100 mb-1.5 uppercase tracking-wider">Event Type</label>
                                    <select name="event_type" class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0">
                                        <option value="">All Events</option>
                                        <option value="Wedding" {{ request('event_type') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                        <option value="Corporate" {{ request('event_type') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                        <option value="Birthday" {{ request('event_type') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                        <option value="Party" {{ request('event_type') == 'Party' ? 'selected' : '' }}>Party</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-green-100 mb-1.5 uppercase tracking-wider">Price Range (₱/pax)</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                            class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0" min="0">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                            class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0" min="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-green-100 mb-1.5 uppercase tracking-wider">Sort By</label>
                                    <select name="sort" class="w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm border-0">
                                        <option value="default" {{ request('sort', 'default') == 'default' ? 'selected' : '' }}>Recommended</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/20">
                                <a href="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}" class="text-sm text-green-200 hover:text-white underline underline-offset-2 transition-colors">
                                    Clear all filters
                                </a>
                                <button type="submit" class="bg-white text-green-700 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-50 transition-colors">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Active dietary chips --}}
                    @auth
                        @if(auth()->user()->isCustomer() && auth()->user()->hasDietaryPreferences())
                            @php $dietaryLabels = \App\Models\User::dietaryLabels(); @endphp
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-green-200 text-xs font-semibold">Dietary filters:</span>
                                @foreach(auth()->user()->dietary_preferences as $pref)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white/15 border border-white/25 rounded-full text-xs font-medium text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $dietaryLabels[$pref] ?? ucfirst(str_replace('_', ' ', $pref)) }}
                                    </span>
                                @endforeach
                                <button onclick="openDietaryPanel()" class="text-green-200 hover:text-white text-xs underline underline-offset-2 transition-colors">Edit</button>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 RESULTS TOOLBAR
            ══════════════════════════════════════════════════════════════ --}}
            <div class="flex items-center justify-between mb-5 px-1">
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $packages->total() }}</span>
                        {{ Str::plural('package', $packages->total()) }}
                        @if(request('search'))
                            for <span class="font-semibold text-green-700 dark:text-green-400">"{{ request('search') }}"</span>
                        @endif
                    </p>
                    @if($activeFilterCount > 0)
                        <a href="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}" class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-full border border-red-200 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear filters
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button id="viewGrid" onclick="setView('grid')"
                        class="view-btn p-1.5 rounded-md text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-600 shadow-sm" title="Grid view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button id="viewList" onclick="setView('list')"
                        class="view-btn p-1.5 rounded-md text-gray-500 dark:text-gray-400" title="List view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 PACKAGES GRID / LIST
            ══════════════════════════════════════════════════════════════ --}}
            @if($packages->count() > 0)
                <div id="packagesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 px-4 sm:px-0">
                    @foreach($packages as $package)
                        @php
                            $caterer = $package->user;

                            $pkgTags    = is_array($package->dietary_tags) ? $package->dietary_tags : [];
                            $matchScore = 0;
                            if (!empty($savedPreferences)) {
                                $matchScore = count(array_intersect($savedPreferences, $pkgTags));
                            }

                            $nameLower = strtolower($package->name);
                            if (str_contains($nameLower, 'wedding'))        $eventLabel = 'Wedding';
                            elseif (str_contains($nameLower, 'corporate'))  $eventLabel = 'Corporate';
                            elseif (str_contains($nameLower, 'birthday'))   $eventLabel = 'Birthday';
                            elseif (str_contains($nameLower, 'party'))      $eventLabel = 'Party';
                            elseif (str_contains($nameLower, 'buffet'))     $eventLabel = 'Buffet';
                            else                                            $eventLabel = 'Event';

                            $pricePerHead = ($package->pax > 0) ? ($package->price / $package->pax) : $package->price;
                        @endphp

                        <div class="package-card group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col"
                             data-match-score="{{ $matchScore }}"
                             data-pkg-tags="{{ json_encode($pkgTags) }}">

                            {{-- Dietary match banner --}}
                            @if(!empty($savedPreferences) && $matchScore > 0)
                                <div class="absolute top-0 left-0 right-0 z-10 flex items-center gap-1.5 px-3 py-1.5 bg-green-500/90 backdrop-blur-sm text-white text-xs font-semibold">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $matchScore }} dietary {{ Str::plural('match', $matchScore) }}
                                </div>
                            @endif

                            {{-- Package Image --}}
                            <div class="relative overflow-hidden {{ (!empty($savedPreferences) && $matchScore > 0) ? 'pt-7' : '' }}">
                                @if($package->image_path)
                                    <img src="{{ $package->image_path }}" alt="{{ $package->name }}"
                                         class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-44 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-500 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                        <svg class="w-14 h-14 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                @endif

                                <span class="absolute bottom-2 left-2 px-2.5 py-1 text-xs font-semibold bg-white/90 dark:bg-gray-900/80 text-gray-700 dark:text-gray-200 rounded-full backdrop-blur-sm shadow-sm">
                                    {{ $eventLabel }}
                                </span>

                                @if($package->status !== 'active')
                                    <span class="absolute top-2 right-2 px-2 py-1 text-xs font-bold bg-gray-800/80 text-gray-200 rounded-full">Unavailable</span>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug mb-1 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                                    {{ $package->name }}
                                </h3>

                                {{-- Caterer info row --}}
                                <div class="flex items-center gap-2 mb-2">
                                    @if($caterer->profile_photo)
                                        <img src="{{ $caterer->profile_photo }}" alt="{{ $caterer->business_name ?? $caterer->name }}"
                                             class="w-5 h-5 rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-600 shrink-0">
                                    @else
                                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ substr($caterer->business_name ?? $caterer->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <a href="{{ auth()->check() ? route('customer.caterer.profile', $caterer->id) : route('browse.caterer.profile', $caterer->id) }}"
                                       class="text-xs text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors truncate font-medium"
                                       onclick="event.stopPropagation()">
                                        {{ $caterer->business_name ?? $caterer->name }}
                                    </a>
                                    <div class="flex items-center gap-0.5 ml-auto shrink-0">
                                        <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $caterer->averageRating() }}</span>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">
                                    {{ $package->description ?: 'No description available.' }}
                                </p>

                                @if(!empty($pkgTags))
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach(array_slice($pkgTags, 0, 3) as $tag)
                                            <span class="px-1.5 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-md border border-green-200 dark:border-green-700">
                                                {{ ucfirst(str_replace('_', ' ', $tag)) }}
                                            </span>
                                        @endforeach
                                        @if(count($pkgTags) > 3)
                                            <span class="text-xs text-gray-400">+{{ count($pkgTags) - 3 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex-1"></div>

                                <div class="flex items-end justify-between pt-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
                                    <div>
                                        <div class="text-xl font-extrabold text-gray-900 dark:text-white">
                                            ₱{{ number_format($package->price, 0) }}
                                        </div>
                                        @if($package->pax)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                ₱{{ number_format($pricePerHead, 0) }}/pax · {{ $package->pax }} pax min.
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ auth()->check() ? route('customer.package.details', [$caterer->id, $package->id]) : route('browse.package.details', [$caterer->id, $package->id]) }}"
                                       class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm shrink-0">
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- LIST VIEW --}}
                <div id="packagesListView" class="hidden space-y-3 px-4 sm:px-0">
                    @foreach($packages as $package)
                        @php
                            $caterer  = $package->user;
                            $pkgTags  = is_array($package->dietary_tags) ? $package->dietary_tags : [];
                            $matchScore = empty($savedPreferences) ? 0 : count(array_intersect($savedPreferences, $pkgTags));

                            $nameLower = strtolower($package->name);
                            if (str_contains($nameLower, 'wedding'))        $eventLabel = 'Wedding';
                            elseif (str_contains($nameLower, 'corporate'))  $eventLabel = 'Corporate';
                            elseif (str_contains($nameLower, 'birthday'))   $eventLabel = 'Birthday';
                            elseif (str_contains($nameLower, 'party'))      $eventLabel = 'Party';
                            elseif (str_contains($nameLower, 'buffet'))     $eventLabel = 'Buffet';
                            else                                            $eventLabel = 'Event';
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="flex items-center gap-4 p-4">
                                <div class="shrink-0 w-20 h-20 rounded-xl overflow-hidden">
                                    @if($package->image_path)
                                        <img src="{{ $package->image_path }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">{{ $eventLabel }}</span>
                                                @if($matchScore > 0)
                                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        {{ $matchScore }} match
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $package->name }}</h3>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">by {{ $caterer->business_name ?? $caterer->name }}</span>
                                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                                <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $caterer->averageRating() }}</span>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <div class="text-lg font-extrabold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</div>
                                            @if($package->pax)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $package->pax }} pax</div>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ Str::limit($package->description, 100) }}</p>
                                </div>

                                <a href="{{ auth()->check() ? route('customer.package.details', [$caterer->id, $package->id]) : route('browse.package.details', [$caterer->id, $package->id]) }}"
                                   class="shrink-0 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($packages->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $packages->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No packages found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Try adjusting your filters or search query to discover more packages.</p>
                    <a href="{{ auth()->check() ? route('customer.packages') : route('browse.packages') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Clear All Filters
                    </a>
                </div>
            @endif

        </div>
    </div>

    <script>
    (function () {

        var SAVED_PREFS = @json($savedPreferences ?? []);
        var CHECK_ORDER = SAVED_PREFS.slice();

        // ── VIEW TOGGLE ──────────────────────────────────────────────────────
        function setView(mode) {
            var grid    = document.getElementById('packagesContainer');
            var list    = document.getElementById('packagesListView');
            var btnGrid = document.getElementById('viewGrid');
            var btnList = document.getElementById('viewList');

            if (mode === 'list') {
                grid.classList.add('hidden');
                list.classList.remove('hidden');
                btnList.classList.add('bg-white', 'shadow-sm');
                btnList.classList.remove('text-gray-500');
                btnGrid.classList.remove('bg-white', 'shadow-sm');
                btnGrid.classList.add('text-gray-500');
            } else {
                list.classList.add('hidden');
                grid.classList.remove('hidden');
                btnGrid.classList.add('bg-white', 'shadow-sm');
                btnGrid.classList.remove('text-gray-500');
                btnList.classList.remove('bg-white', 'shadow-sm');
                btnList.classList.add('text-gray-500');
            }
            localStorage.setItem('pkgView', mode);
        }

        // ── DIETARY SORT ─────────────────────────────────────────────────────
        function sortPackageCards(prefs) {
            var container = document.getElementById('packagesContainer');
            if (!container || !prefs || !prefs.length) return;

            var cards = Array.prototype.slice.call(container.querySelectorAll('.package-card'));
            if (!cards.length) return;

            cards.forEach(function (card) {
                var tags  = JSON.parse(card.getAttribute('data-pkg-tags') || '[]');
                var score = prefs.filter(function (p) { return tags.indexOf(p) !== -1; }).length;
                card.setAttribute('data-match-score', score);
            });

            cards.sort(function (a, b) {
                return parseInt(b.getAttribute('data-match-score'), 10) -
                       parseInt(a.getAttribute('data-match-score'), 10);
            });

            cards.forEach(function (card) { container.appendChild(card); });
        }

        // ── DIETARY PANEL ────────────────────────────────────────────────────
        function openDietaryPanel() {
            CHECK_ORDER = SAVED_PREFS.slice();
            var panel    = document.getElementById('dietaryPanel');
            var backdrop = document.getElementById('dietaryBackdrop');
            panel.classList.remove('translate-x-full');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeDietaryPanel() {
            var panel    = document.getElementById('dietaryPanel');
            var backdrop = document.getElementById('dietaryBackdrop');
            panel.classList.add('translate-x-full');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }

        function togglePanelDietaryCard(checkbox) {
            var label = checkbox.closest('label');
            var check = label.querySelector('.panel-dietary-check');
            if (checkbox.checked) {
                label.classList.add('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                label.classList.remove('border-gray-200');
                if (check) { check.classList.replace('opacity-0', 'opacity-100'); check.classList.replace('scale-0', 'scale-100'); }
                if (CHECK_ORDER.indexOf(checkbox.value) === -1) CHECK_ORDER.push(checkbox.value);
            } else {
                label.classList.remove('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                label.classList.add('border-gray-200');
                if (check) { check.classList.replace('opacity-100', 'opacity-0'); check.classList.replace('scale-100', 'scale-0'); }
                CHECK_ORDER = CHECK_ORDER.filter(function (s) { return s !== checkbox.value; });
            }
        }

        function clearDietaryPreferences() {
            document.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) {
                cb.checked = false;
                togglePanelDietaryCard(cb);
            });
            CHECK_ORDER = [];
        }

        function saveDietaryPreferences() {
            var btn  = document.getElementById('dietarySaveBtn');
            var msg  = document.getElementById('dietarySaveSuccess');
            var form = document.getElementById('dietaryPanelForm');

            btn.disabled  = true;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving…';

            var container = document.getElementById('orderedPrefsContainer');
            container.innerHTML = '';
            CHECK_ORDER.forEach(function (slug) {
                var inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = 'dietary_preferences[]'; inp.value = slug;
                container.appendChild(inp);
            });
            form.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) { cb.disabled = true; });

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (response) {
                if (response.ok || response.redirected || response.status === 302) {
                    SAVED_PREFS = CHECK_ORDER.slice();
                    sortPackageCards(SAVED_PREFS);
                    msg.classList.remove('hidden');
                    msg.classList.add('flex');
                    setTimeout(function () { window.location.reload(); }, 1500);
                } else {
                    alert('Failed to save preferences. Please try again.');
                }
            })
            .catch(function () { form.submit(); })
            .finally(function () {
                form.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) { cb.disabled = false; });
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save Preferences';
            });
        }

        // ── DOM READY ────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {

            var ft = document.getElementById('filterToggle');
            if (ft) {
                ft.addEventListener('click', function () {
                    var af      = document.getElementById('advancedFilters');
                    var chevron = document.getElementById('filterChevron');
                    af.classList.toggle('hidden');
                    if (chevron) chevron.classList.toggle('rotate-180');
                });
            }

            @if($activeFilterCount > 0)
                var af = document.getElementById('advancedFilters');
                if (af) af.classList.remove('hidden');
            @endif

            var savedView = localStorage.getItem('pkgView');
            if (savedView === 'list') setView('list');

            if (SAVED_PREFS.length) sortPackageCards(SAVED_PREFS);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDietaryPanel();
            });
        });

        window.openDietaryPanel        = openDietaryPanel;
        window.closeDietaryPanel       = closeDietaryPanel;
        window.togglePanelDietaryCard  = togglePanelDietaryCard;
        window.clearDietaryPreferences = clearDietaryPreferences;
        window.saveDietaryPreferences  = saveDietaryPreferences;
        window.setView                 = setView;

    }());
    </script>
</x-app-layout>
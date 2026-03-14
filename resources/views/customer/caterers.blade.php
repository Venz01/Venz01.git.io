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

    {{-- ── Dietary Preferences Slide-Out Panel ── --}}
    <div id="dietaryPanel"
        class="fixed inset-y-0 right-0 z-50 w-full sm:w-[420px] transform translate-x-full transition-transform duration-300 ease-in-out">
        {{-- Backdrop (mobile) --}}
        <div id="dietaryBackdrop"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-none"
            onclick="closeDietaryPanel()"></div>

        {{-- Panel Content --}}
        <div class="relative h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col border-l border-gray-200 dark:border-gray-700">

            {{-- Panel Header --}}
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
                        <p class="text-green-100 text-xs">Personalize your caterer recommendations</p>
                    </div>
                </div>
                <button onclick="closeDietaryPanel()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-white/70 hover:text-white hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Scrollable Body --}}
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">

                {{-- Info Banner --}}
                <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" />
                    </svg>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Select your dietary needs below. Compatible packages will be highlighted with a
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">✓ green badge</span>
                        and potential conflicts with a
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">⚠ red badge</span>.
                    </p>
                </div>

                {{-- Dietary Preference Tags --}}
                <form id="dietaryPanelForm" method="POST" action="{{ route('profile.dietary.update') }}">
                    @csrf
                    @method('PATCH')
                    {{-- JS populates these hidden inputs in exact check-order before submitting --}}
                    <div id="orderedPrefsContainer"></div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">
                            Dietary Preferences
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                            Select all that apply — caterers and packages will be flagged accordingly.
                        </p>

                        @php
                            // ── Compute saved preferences ──────────────────────────────────────
                            $savedPreferences = [];
                            if (auth()->check() && auth()->user()->dietary_preferences) {
                                $savedPreferences = is_array(auth()->user()->dietary_preferences)
                                    ? auth()->user()->dietary_preferences
                                    : [];
                            }

                            // ── Load all tags, sort: matched first (by selection order), then alpha ──
                            $allTags = \App\Models\DietaryTag::orderBy('name')->get();
                            if (!empty($savedPreferences)) {
                                $allTags = $allTags->sortBy(function ($tag) use ($savedPreferences) {
                                    $pos = array_search($tag->slug, $savedPreferences);
                                    // matched → position index (0, 1, 2…)
                                    // unmatched → 10000 + already alpha-sorted name keeps alpha order
                                    return $pos !== false ? $pos : 10000 + strlen($tag->name . $tag->slug);
                                })->values();
                            }

                            $matchedTags   = $allTags->filter(fn($t) => in_array($t->slug, $savedPreferences));
                            $unmatchedTags = $allTags->filter(fn($t) => !in_array($t->slug, $savedPreferences));
                        @endphp

                        @if($allTags->count() > 0)

                            {{-- ── GROUP 1: Customer's selected tags (pinned, green) ── --}}
                            @if($matchedTags->count() > 0)
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <span class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">✓ Your selections</span>
                                        <div class="flex-1 h-px bg-green-200 dark:bg-green-800"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2.5">
                                        @foreach($matchedTags as $tag)
                                            <label
                                                class="dietary-panel-option relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none border-green-500 bg-green-50 dark:bg-green-900/30 shadow-sm ring-1 ring-green-400/40"
                                                id="panel-label-{{ $tag->slug }}"
                                            >
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

                            {{-- ── GROUP 2: Unselected tags (alphabetical, muted) ── --}}
                            @if($unmatchedTags->count() > 0)
                                @if($matchedTags->count() > 0)
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Other options</span>
                                        <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                                    </div>
                                @endif
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach($unmatchedTags as $tag)
                                        <label
                                            class="dietary-panel-option relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-green-300 hover:bg-green-50/50"
                                            id="panel-label-{{ $tag->slug }}"
                                        >
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

                    {{-- Hidden submit for AJAX --}}
                </form>

                {{-- Currently Active Preferences Summary --}}
                @if(!empty($savedPreferences))
                    <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                        <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wider mb-2">
                            Active Filters
                        </p>
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

            {{-- Panel Footer --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 space-y-3">

                {{-- Success message (hidden by default) --}}
                <div id="dietarySaveSuccess"
                    class="hidden flex items-center gap-2 text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Preferences saved! Refreshing page…
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="clearDietaryPreferences()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Clear All
                    </button>
                    <button type="button" onclick="saveDietaryPreferences()"
                        id="dietarySaveBtn"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 active:bg-green-800 rounded-xl transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Preferences
                    </button>
                </div>

                <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                    You can also manage these in your
                    <a href="{{ route('profile.edit') }}" class="text-green-600 hover:underline">profile settings</a>.
                </p>
            </div>

        </div>
    </div>
    {{-- ── END Dietary Panel ── --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold mb-4">Find the Perfect Caterer</h1>
                    <p class="text-xl mb-6 opacity-90">Browse and compare catering packages for your next event. Customize menus, get instant quotes, and book with confidence.</p>
                    
                    <!-- Search Bar -->
                    <form method="GET" action="{{ auth()->check() ? route('customer.caterers') : route('browse.caterers') }}" class="flex flex-col sm:flex-row gap-4 max-w-4xl">
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

                            {{-- ── Filters Button (existing) ── --}}
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

                            {{-- ── NEW: Dietary Preferences Button ── --}}
                            @auth
                                @if(auth()->user()->isCustomer())
                                    <button
                                        type="button"
                                        onclick="openDietaryPanel()"
                                        id="dietaryToggleBtn"
                                        class="relative bg-white/20 hover:bg-white/30 text-white px-5 py-4 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 border border-white/30 hover:border-white/60 group"
                                        title="Manage Dietary Preferences"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Dietary</span>
                                        {{-- Active indicator dot --}}
                                        @if(auth()->user()->hasDietaryPreferences())
                                            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-yellow-400 rounded-full border-2 border-green-700 flex items-center justify-center text-green-900 font-bold"
                                                style="font-size: 8px;">
                                                {{ count(auth()->user()->dietary_preferences) }}
                                            </span>
                                        @endif
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </form>

                    {{-- Active dietary preferences chips (shown below search when prefs are set) --}}
                    @auth
                        @if(auth()->user()->isCustomer() && auth()->user()->hasDietaryPreferences())
                            @php $labels = \App\Models\User::dietaryLabels(); @endphp
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-green-100 text-sm font-medium">Active dietary filters:</span>
                                @foreach(auth()->user()->dietary_preferences as $pref)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-xs font-medium text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $labels[$pref] ?? ucfirst(str_replace('_', ' ', $pref)) }}
                                    </span>
                                @endforeach
                                <button onclick="openDietaryPanel()" class="text-green-200 hover:text-white text-xs underline underline-offset-2 transition-colors">
                                    Edit
                                </button>
                            </div>
                        @endif
                    @endauth

                    <!-- Advanced Filters (existing) -->
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
            <div id="catererList" class="space-y-8">
                @if($caterers->count() > 0)
                    @foreach($caterers as $caterer)
                        @php
                            // Gather all dietary tag slugs from this caterer's packages
                            $catererTagSlugs = $caterer->packages
                                ->flatMap(fn($p) => is_array($p->dietary_tags) ? $p->dietary_tags : [])
                                ->unique()->values()->toArray();

                            // Score = how many of the customer's prefs appear in this caterer's package tags
                            $matchScore = empty($savedPreferences) ? 0
                                : count(array_intersect($savedPreferences, $catererTagSlugs));
                        @endphp
                        <div class="caterer-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                             data-match-score="{{ $matchScore }}"
                             data-caterer-tags="{{ json_encode($catererTagSlugs) }}">
                            <div class="p-6">
                                <!-- Caterer Header with Profile Photo -->
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                                    <div class="flex items-start space-x-4 mb-4 lg:mb-0">
                                        <!-- Profile Photo -->
                                        <div class="shrink-0">
                                            @if($caterer->profile_photo)
                                                <img src="{{ $caterer->profile_photo }}"
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
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                                    {{ $caterer->business_name ?? $caterer->name }}
                                                </h3>
                                                @if($matchScore > 0)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 text-xs font-semibold rounded-full border border-green-300 dark:border-green-700">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        {{ $matchScore }} dietary {{ Str::plural('match', $matchScore) }}
                                                    </span>
                                                @endif
                                            </div>
                                            
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
                                        </div>{{-- end flex-1 --}}
                                    </div>

                                    <!-- View All Button -->
                                    <div class="flex items-center space-x-2">
                                        <a 
                                            href="{{ auth()->check() ? route('customer.caterer.profile', $caterer->id) : route('browse.caterer.profile', $caterer->id) }}" 
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
                                                        <img src="{{ $package->image_path }}"
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

                                                    {{-- ── Dietary Badges for this package ── --}}
                                                    @auth
                                                        @if(auth()->user()->isCustomer() && auth()->user()->hasDietaryPreferences())
                                                            @include('admin.partials.package-dietary-badges', [
                                                                'package' => $package,
                                                            ])
                                                        @endif
                                                    @endauth

                                                    <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600 mt-3">
                                                        <div>
                                                            <span class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 0) }}</span>
                                                            @if($package->pax)
                                                                <span class="text-gray-500 text-xs">/ {{ $package->pax }} pax</span>
                                                            @endif
                                                        </div>
                                                        <a 
                                                            href="{{ auth()->check() ? route('customer.package.details', [$caterer->id, $package->id]) : route('browse.package.details', [$caterer->id, $package->id]) }}" 
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
                                                href="{{ auth()->check() ? route('customer.caterer.profile', $caterer->id) : route('browse.caterer.profile', $caterer->id) }}" 
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
                        <a href="{{ auth()->check() ? route('customer.caterers') : route('browse.caterers') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    (function () {

        // ── Customer saved prefs injected from PHP (slug array, in selection order) ──
        var SAVED_PREFS = @json($savedPreferences ?? []);

        // ── CHECK_ORDER tracks the order the user ticked boxes this session ──
        // Seeded from saved prefs so existing selections maintain their order.
        var CHECK_ORDER = SAVED_PREFS.slice();

        // ══════════════════════════════════════════════════════════════
        //  CATERER LIST SORTING
        // ══════════════════════════════════════════════════════════════

        /**
         * Score and re-sort caterer cards in the DOM.
         * Cards with score > 0 are pinned to the top (desc by score).
         * A visual divider separates matched from unmatched groups.
         * Runs on page load and again instantly after Save.
         */
        function sortCatererCards(prefs) {
            var list = document.getElementById('catererList');
            if (!list) return;

            // Only operate on actual caterer cards (ignore pagination / injected dividers)
            var cards = Array.prototype.slice.call(list.querySelectorAll('.caterer-card'));
            if (!cards.length) return;

            // Remove previously injected dividers & match banners
            list.querySelectorAll('.js-dietary-divider').forEach(function (el) { el.remove(); });
            list.querySelectorAll('.js-match-banner').forEach(function (el) { el.remove(); });

            if (!prefs || !prefs.length) return; // no prefs — leave order alone

            // Re-score each card from the live prefs array
            cards.forEach(function (card) {
                var tags  = JSON.parse(card.getAttribute('data-caterer-tags') || '[]');
                var score = 0;
                prefs.forEach(function (p) { if (tags.indexOf(p) !== -1) score++; });
                card.setAttribute('data-match-score', score);
            });

            // Stable sort: highest score first (equal scores keep original DOM order)
            cards.sort(function (a, b) {
                return parseInt(b.getAttribute('data-match-score'), 10)
                     - parseInt(a.getAttribute('data-match-score'), 10);
            });

            // Re-append cards in new order
            cards.forEach(function (card) { list.appendChild(card); });

            // ── Inject match banner at the very top of each matched card ──────
            cards.forEach(function (card) {
                var score = parseInt(card.getAttribute('data-match-score'), 10);
                if (score > 0) {
                    var banner = document.createElement('div');
                    banner.className = 'js-match-banner flex items-center gap-2 px-5 py-2 bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800 text-xs font-semibold text-green-700 dark:text-green-400';
                    banner.innerHTML =
                        '<svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' +
                        '</svg>' +
                        score + ' of your dietary ' + (score === 1 ? 'preference matches' : 'preferences match') + ' this caterer\'s packages';
                    card.insertBefore(banner, card.firstChild);
                }
            });

            // ── Inject divider between matched and unmatched groups ───────────
            var matchedCount = cards.filter(function (c) {
                return parseInt(c.getAttribute('data-match-score'), 10) > 0;
            }).length;

            if (matchedCount > 0 && matchedCount < cards.length) {
                var firstUnmatched = cards[matchedCount]; // sorted, so this is correct
                var divider = document.createElement('div');
                divider.className = 'js-dietary-divider flex items-center gap-4 py-1';
                divider.innerHTML =
                    '<div class="flex-1 h-px bg-gray-300 dark:bg-gray-600"></div>' +
                    '<span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest whitespace-nowrap">' +
                        'Other caterers' +
                    '</span>' +
                    '<div class="flex-1 h-px bg-gray-300 dark:bg-gray-600"></div>';
                list.insertBefore(divider, firstUnmatched);
            }
        }

        // ══════════════════════════════════════════════════════════════
        //  PANEL OPEN / CLOSE
        // ══════════════════════════════════════════════════════════════

        function openDietaryPanel() {
            // Re-seed CHECK_ORDER from current SAVED_PREFS each time panel opens
            // so unchecking & re-opening always starts from the last saved state
            CHECK_ORDER = SAVED_PREFS.slice();
            document.getElementById('dietaryPanel').classList.remove('translate-x-full');
            var bd = document.getElementById('dietaryBackdrop');
            bd.classList.remove('opacity-0', 'pointer-events-none');
            bd.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeDietaryPanel() {
            document.getElementById('dietaryPanel').classList.add('translate-x-full');
            var bd = document.getElementById('dietaryBackdrop');
            bd.classList.add('opacity-0', 'pointer-events-none');
            bd.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }

        // ══════════════════════════════════════════════════════════════
        //  DIETARY CARD TOGGLE (checkbox visual state)
        // ══════════════════════════════════════════════════════════════

        function togglePanelDietaryCard(checkbox) {
            var label  = checkbox.closest('label');
            var badge  = label.querySelector('.panel-dietary-check');
            var slug   = checkbox.value;

            if (checkbox.checked) {
                // Track check order — append only if not already present
                if (CHECK_ORDER.indexOf(slug) === -1) CHECK_ORDER.push(slug);
                label.classList.add('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                label.classList.remove('border-gray-200');
                badge.classList.replace('opacity-0', 'opacity-100');
                badge.classList.replace('scale-0',   'scale-100');
            } else {
                // Remove from order tracking
                var idx = CHECK_ORDER.indexOf(slug);
                if (idx !== -1) CHECK_ORDER.splice(idx, 1);
                label.classList.remove('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                label.classList.add('border-gray-200');
                badge.classList.replace('opacity-100', 'opacity-0');
                badge.classList.replace('scale-100',   'scale-0');
            }
        }

        function clearDietaryPreferences() {
            CHECK_ORDER = []; // reset order tracking
            document.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) {
                if (cb.checked) { cb.checked = false; togglePanelDietaryCard(cb); }
            });
        }

        // ══════════════════════════════════════════════════════════════
        //  RE-SORT PANEL TAG CARDS after save
        //  Moves checked labels into "Your selections" group and
        //  unchecked labels back to "Other options" — no page reload.
        // ══════════════════════════════════════════════════════════════

        function resortPanelTags(newPrefs) {
            var form = document.getElementById('dietaryPanelForm');
            var tagsWrapper = form.querySelector('div'); // the outer <div> wrapping both groups

            // Collect ALL label elements (regardless of which group they are in now)
            var allLabels = Array.prototype.slice.call(
                form.querySelectorAll('label.dietary-panel-option')
            );

            if (!allLabels.length) return;

            // Separate into matched (checked) and unmatched (unchecked)
            var matched   = allLabels.filter(function (l) { return l.querySelector('.panel-dietary-checkbox').checked; });
            var unmatched = allLabels.filter(function (l) { return !l.querySelector('.panel-dietary-checkbox').checked; });

            // Sort matched by the order they appear in newPrefs (selection order)
            matched.sort(function (a, b) {
                var aSlug = a.querySelector('.panel-dietary-checkbox').value;
                var bSlug = b.querySelector('.panel-dietary-checkbox').value;
                return newPrefs.indexOf(aSlug) - newPrefs.indexOf(bSlug);
            });

            // Sort unmatched alphabetically by their visible label text
            unmatched.sort(function (a, b) {
                var aName = (a.querySelector('span:not(.panel-dietary-check)') || {}).textContent || '';
                var bName = (b.querySelector('span:not(.panel-dietary-check)') || {}).textContent || '';
                return aName.trim().localeCompare(bName.trim());
            });

            // Rebuild the entire tags section HTML skeleton, then re-insert labels
            tagsWrapper.innerHTML = '';

            if (matched.length > 0) {
                // "Your selections" header
                var selHeader = document.createElement('div');
                selHeader.className = 'mb-4';
                selHeader.innerHTML =
                    '<div class="flex items-center gap-2 mb-2.5">' +
                        '<span class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">✓ Your selections</span>' +
                        '<div class="flex-1 h-px bg-green-200 dark:bg-green-800"></div>' +
                    '</div>';
                var selGrid = document.createElement('div');
                selGrid.className = 'grid grid-cols-2 gap-2.5';
                matched.forEach(function (lbl) {
                    // Ensure it has the checked visual state
                    lbl.classList.add('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                    lbl.classList.remove('border-gray-200');
                    var badge = lbl.querySelector('.panel-dietary-check');
                    if (badge) { badge.classList.replace('opacity-0','opacity-100'); badge.classList.replace('scale-0','scale-100'); }
                    selGrid.appendChild(lbl);
                });
                selHeader.appendChild(selGrid);
                tagsWrapper.appendChild(selHeader);
            }

            if (unmatched.length > 0) {
                if (matched.length > 0) {
                    // "Other options" divider
                    var otherHeader = document.createElement('div');
                    otherHeader.className = 'flex items-center gap-2 mb-2.5';
                    otherHeader.innerHTML =
                        '<span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Other options</span>' +
                        '<div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>';
                    tagsWrapper.appendChild(otherHeader);
                }
                var otherGrid = document.createElement('div');
                otherGrid.className = 'grid grid-cols-2 gap-2.5';
                unmatched.forEach(function (lbl) {
                    // Ensure it has the unchecked visual state
                    lbl.classList.remove('border-green-500', 'bg-green-50', 'shadow-sm', 'ring-1', 'ring-green-400/40');
                    lbl.classList.add('border-gray-200');
                    var badge = lbl.querySelector('.panel-dietary-check');
                    if (badge) { badge.classList.replace('opacity-100','opacity-0'); badge.classList.replace('scale-100','scale-0'); }
                    otherGrid.appendChild(lbl);
                });
                tagsWrapper.appendChild(otherGrid);
            }
        }

        // ══════════════════════════════════════════════════════════════
        //  SAVE VIA AJAX → instant resort panel tags + caterer list
        // ══════════════════════════════════════════════════════════════

        function saveDietaryPreferences() {
            var btn     = document.getElementById('dietarySaveBtn');
            var msg     = document.getElementById('dietarySaveSuccess');
            var form    = document.getElementById('dietaryPanelForm');

            btn.disabled  = true;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving…';

            // ── Inject hidden inputs in CHECK_ORDER before submitting ──────────────
            var container = document.getElementById('orderedPrefsContainer');
            container.innerHTML = '';
            CHECK_ORDER.forEach(function (slug) {
                var inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'dietary_preferences[]';
                inp.value = slug;
                container.appendChild(inp);
            });
            // Disable all checkboxes so they don't double-submit alongside hidden inputs
            form.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) { cb.disabled = true; });

            fetch(form.action, {
                method:  'POST',
                body:    new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (response) {
                if (response.ok || response.redirected || response.status === 302) {

                    // CHECK_ORDER IS the authoritative ordered list
                    SAVED_PREFS = CHECK_ORDER.slice();

                    // 1. Re-group tag cards inside the panel immediately
                    resortPanelTags(SAVED_PREFS);

                    // 2. Re-sort caterer cards in the browse list
                    sortCatererCards(SAVED_PREFS);

                    // 3. Show success, then reload the page so caterer list re-renders fresh
                    msg.classList.remove('hidden');
                    msg.classList.add('flex');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);

                } else {
                    response.text().then(function (t) {
                        console.error('Save failed:', t);
                        alert('Failed to save preferences. Please try again.');
                    });
                }
            })
            .catch(function (err) {
                console.error('Network error — falling back to form submit:', err);
                form.submit();
            })
            .finally(function () {
                // Re-enable checkboxes in case of error (page reloads on success anyway)
                form.querySelectorAll('.panel-dietary-checkbox').forEach(function (cb) { cb.disabled = false; });
                btn.disabled  = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save Preferences';
            });
        }

        // ══════════════════════════════════════════════════════════════
        //  DOM READY
        // ══════════════════════════════════════════════════════════════

        document.addEventListener('DOMContentLoaded', function () {

            // Filter toggle
            var ft = document.getElementById('filterToggle');
            if (ft) {
                ft.addEventListener('click', function () {
                    document.getElementById('advancedFilters').classList.toggle('hidden');
                    document.getElementById('filterChevron').classList.toggle('rotate-180');
                });
            }

            // Escape closes panel
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDietaryPanel();
            });

            // Sort caterer list on initial page load if prefs already exist
            if (SAVED_PREFS.length) sortCatererCards(SAVED_PREFS);
        });

        // Expose to inline onclick attributes
        window.openDietaryPanel        = openDietaryPanel;
        window.closeDietaryPanel       = closeDietaryPanel;
        window.togglePanelDietaryCard  = togglePanelDietaryCard;
        window.clearDietaryPreferences = clearDietaryPreferences;
        window.saveDietaryPreferences  = saveDietaryPreferences;
        window.resortPanelTags         = resortPanelTags;

    }());
    </script>
</x-app-layout>
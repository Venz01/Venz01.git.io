<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('caterer.costing.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Costing: {{ $package->name }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                {{-- Default Template Badge --}}
                @if($costing->exists && $costing->is_default_template)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full border border-amber-200 dark:border-amber-700">
                        ⭐ Default Template
                    </span>
                @endif
                <span class="text-sm text-gray-500 dark:text-gray-400">Current price:</span>
                <span class="font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 2) }}/head</span>
            </div>
        </div>
    </x-slot>

    <div x-data="costingTool()" class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-800 dark:text-red-300 text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- ── Default Template Banner (when another template exists but not this one) ─ --}}
        @if($defaultTemplate && $defaultTemplate->package_id !== $package->id)
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                            You have a default template: <strong>{{ $defaultTemplate->package->name ?? 'Unknown' }}</strong>
                            @if($defaultTemplate->template_name)
                                ({{ $defaultTemplate->template_name }})
                            @endif
                        </p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                            This template is auto-applied whenever you create a new package.
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('caterer.costing.set-default', $costing->id ?? 0) }}">
                    @csrf
                    <button type="submit"
                            @if(!$costing->exists) disabled @endif
                            class="px-3 py-1.5 text-xs font-medium bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                        Make This Default Instead
                    </button>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- ── LEFT: Input Form ──────────────────────────────────────── --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Package Summary --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        @if($package->image_path)
                            <img src="{{ $package->image_path }}" alt=""
                                 class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">📦</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $package->name }}</h3>
                            <div class="flex flex-wrap gap-4 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $package->pax }} guests</span>
                                <span>{{ $package->items->count() }} menu items</span>
                                <span class="{{ $package->status === 'active' ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 2) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">per head</div>
                        </div>
                    </div>

                    @if($package->items->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Included Menu Items</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($package->items->groupBy('category.name') as $catName => $items)
                                @foreach($items as $item)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">
                                        {{ $item->name }}
                                        <span class="ml-1 text-gray-400">₱{{ number_format($item->price, 2) }}</span>
                                    </span>
                                @endforeach
                            @endforeach
                        </div>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                            Menu items total: <strong>₱{{ number_format($package->items->sum('price'), 2) }}/head</strong>
                            <span class="text-gray-400 ml-1">— auto-filled into Ingredients / Raw Food below</span>
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Cost Components Form --}}
                <form id="costingForm"
                      action="{{ route('caterer.costing.store', $package->id) }}"
                      method="POST"
                      class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    @csrf

                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Cost Components</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Fill in only the components relevant to your operation — leave others blank.
                        </p>
                    </div>

                    @php
                        $menuItemsTotal    = $package->items->sum('price');
                        $ingredientDefault = old('ingredient_cost',
                            $costing->ingredient_cost ?? ($menuItemsTotal > 0 ? $menuItemsTotal : '')
                        );

                        $components = [
                            ['key' => 'ingredient_cost',  'label' => 'Ingredients / Raw Food',  'icon' => '🥩', 'hint' => 'Cost of all raw ingredients per head'],
                            ['key' => 'labor_cost',       'label' => 'Labor & Staffing',         'icon' => '👨‍🍳', 'hint' => 'Cook, servers, crew per head'],
                            ['key' => 'equipment_cost',   'label' => 'Equipment & Rentals',      'icon' => '🍽️', 'hint' => 'Tables, linens, chafing dishes per head'],
                            ['key' => 'consumables_cost', 'label' => 'Consumables & Packaging',  'icon' => '🥄', 'hint' => 'Utensils, softdrinks, napkins per head'],
                            ['key' => 'overhead_cost',    'label' => 'Overhead & Utilities',     'icon' => '⚡', 'hint' => 'Kitchen overhead, electricity per head'],
                            ['key' => 'transport_cost',   'label' => 'Transport & Logistics',    'icon' => '🚐', 'hint' => 'Delivery to venue per head'],
                        ];
                    @endphp

                    <div class="p-6 space-y-4">
                        @foreach($components as $comp)
                        <div class="flex items-center gap-4 p-4 rounded-lg border transition-colors group
                            {{ $comp['key'] === 'ingredient_cost'
                                ? 'border-orange-200 dark:border-orange-800 bg-orange-50/50 dark:bg-orange-900/10'
                                : 'border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-700' }}">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl flex-shrink-0 transition-colors
                                {{ $comp['key'] === 'ingredient_cost'
                                    ? 'bg-orange-100 dark:bg-orange-900/30'
                                    : 'bg-gray-50 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20' }}">
                                {{ $comp['icon'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <label for="{{ $comp['key'] }}" class="block text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $comp['label'] }}
                                    </label>
                                    @if($comp['key'] === 'ingredient_cost' && $menuItemsTotal > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium
                                            bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300
                                            rounded-full border border-orange-200 dark:border-orange-700">
                                            ✦ From menu items
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $comp['hint'] }}
                                    @if($comp['key'] === 'ingredient_cost' && $menuItemsTotal > 0)
                                        &nbsp;·&nbsp;
                                        <button type="button"
                                                onclick="document.getElementById('ingredient_cost').value='{{ number_format($menuItemsTotal, 2, '.', '') }}'; document.getElementById('ingredient_cost').dispatchEvent(new Event('input',{bubbles:true}))"
                                                class="text-orange-500 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-200 underline underline-offset-2">
                                            Reset to ₱{{ number_format($menuItemsTotal, 2) }}
                                        </button>
                                    @endif
                                </p>
                            </div>
                            <div class="flex-shrink-0 w-36">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">₱</span>
                                    <input type="number"
                                           id="{{ $comp['key'] }}"
                                           name="{{ $comp['key'] }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           value="{{ $comp['key'] === 'ingredient_cost'
                                               ? $ingredientDefault
                                               : old($comp['key'], $costing->{$comp['key']} ?? '') }}"
                                           @input="recalculate()"
                                           class="w-full pl-7 pr-3 py-2.5 text-sm border rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right font-mono
                                               {{ $comp['key'] === 'ingredient_cost'
                                                   ? 'border-orange-300 dark:border-orange-700'
                                                   : 'border-gray-300 dark:border-gray-600' }}">
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Profit Margin --}}
                        <div class="mt-2 p-4 rounded-lg bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/40 rounded-lg flex items-center justify-center text-xl flex-shrink-0">📈</div>
                                <div class="flex-1 min-w-0">
                                    <label for="profit_margin_percent" class="block text-sm font-medium text-purple-900 dark:text-purple-200">
                                        Target Profit Margin
                                    </label>
                                    <p class="text-xs text-purple-600 dark:text-purple-400 mt-0.5">
                                        Applied on top of total cost. Typical: 20–30%
                                    </p>
                                </div>
                                <div class="flex-shrink-0 w-36">
                                    <div class="relative">
                                        <input type="number"
                                               id="profit_margin_percent"
                                               name="profit_margin_percent"
                                               step="0.5"
                                               min="0"
                                               max="100"
                                               value="{{ old('profit_margin_percent', $costing->profit_margin_percent ?? 25) }}"
                                               @input="recalculate()"
                                               class="w-full pl-3 pr-7 py-2.5 text-sm border border-purple-300 dark:border-purple-600 rounded-lg bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 text-right font-mono font-bold">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-purple-500 text-sm font-bold">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Internal Notes
                                <span class="text-gray-400 font-normal">(not visible to customers)</span>
                            </label>
                            <textarea name="notes" rows="2"
                                      placeholder="e.g. pricing valid for events > 100 guests..."
                                      class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $costing->notes ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- ── Default Template Section ──────────────────────── --}}
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-amber-50/50 dark:bg-amber-900/10">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start sm:items-center gap-3">
                                <span class="text-xl mt-0.5 sm:mt-0">⭐</span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        Set as Default Template
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        When checked, this cost structure will be auto-applied to every new package you create.
                                        You can always override it from the Costing Tool.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                {{-- Template name --}}
                                <input type="text"
                                       name="template_name"
                                       value="{{ old('template_name', $costing->template_name ?? '') }}"
                                       placeholder="e.g. Standard Wedding"
                                       class="w-44 px-3 py-2 text-sm border border-amber-300 dark:border-amber-700 rounded-lg bg-white dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-amber-500">
                                {{-- Toggle --}}
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="set_as_default"
                                           value="1"
                                           id="setAsDefaultToggle"
                                           {{ old('set_as_default', $costing->is_default_template ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500 rounded-full peer
                                                dark:bg-gray-700 peer-checked:bg-amber-500 transition-colors after:content-[''] after:absolute after:top-[2px]
                                                after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                                peer-checked:after:translate-x-5"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Final Price & Submit --}}
                    <div class="px-6 pb-6 pt-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4 items-end">
                            <div class="flex-1">
                                <label for="final_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Final Price per Head
                                    <span class="text-gray-400 font-normal">(you decide — suggested is in the panel →)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                    <input type="number"
                                           id="final_price"
                                           name="final_price"
                                           step="0.01"
                                           min="0"
                                           placeholder="Enter your final price"
                                           value="{{ old('final_price', $costing->final_price ?? $package->price) }}"
                                           x-ref="finalPrice"
                                           class="w-full pl-8 pr-4 py-3 text-lg font-bold border-2 border-blue-400 dark:border-blue-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="sm:text-right flex-shrink-0">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="apply_to_package" value="1"
                                           {{ ($costing->exists ?? false) ? '' : 'checked' }}
                                           class="rounded text-blue-600">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Update package price automatically
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                    class="flex-1 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                Save Costing
                            </button>
                            <a href="{{ route('caterer.package.quotation', $package->id) }}?guest_count={{ $package->pax }}"
                               target="_blank"
                               class="px-4 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium flex items-center gap-2 whitespace-nowrap">
                                🖨️ Preview Quotation
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Historical Context --}}
                @if($bookingHistory && $bookingHistory->total_bookings > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">📊 Historical Booking Data</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bookingHistory->total_bookings }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Bookings</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($bookingHistory->avg_price_per_head, 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Avg Price/Head</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($bookingHistory->total_revenue, 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Revenue</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($bookingHistory->avg_guests, 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Avg Guests</div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ── RIGHT: Live Results Panel ─────────────────────────────── --}}
            <div class="space-y-6 xl:sticky xl:top-6 xl:self-start">

                {{-- Live Calculator Result --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-center">
                        <div class="text-sm font-medium opacity-80 mb-1">Suggested Price per Head</div>
                        <div class="text-5xl font-black tracking-tight mb-1">
                            ₱<span x-text="formatNumber(result.suggested_price)">{{ number_format($costing->suggested_price ?? 0, 0) }}</span>
                        </div>
                        <div class="text-sm opacity-70">based on costs + margin</div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/20 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="opacity-75">Total Cost:</span>
                            <span class="font-semibold">₱<span x-text="formatNumber(result.total_cost)">{{ number_format($costing->total_cost ?? 0, 2) }}</span></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-75">Profit (<span x-text="marginPercent">{{ $costing->profit_margin_percent ?? 25 }}</span>%):</span>
                            <span class="font-semibold">₱<span x-text="formatNumber(result.profit_amount)">{{ number_format($costing->profit_amount ?? 0, 2) }}</span></span>
                        </div>
                    </div>
                </div>

                {{-- Cost Breakdown --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 text-sm">Cost Breakdown</h3>

                    <div x-show="result.total_cost > 0" class="space-y-2.5">
                        <template x-for="(item, key) in result.breakdown" :key="key">
                            <div x-show="item.amount > 0">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600 dark:text-gray-400" x-text="componentLabels[key]"></span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        ₱<span x-text="formatNumber(item.amount)"></span>
                                        <span class="text-gray-400">(<span x-text="item.percent"></span>%)</span>
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :class="componentColors[key]"
                                         :style="`width: ${item.percent}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="result.total_cost == 0" class="text-center py-6 text-gray-400 dark:text-gray-600 text-sm">
                        Enter cost components to see breakdown
                    </div>
                </div>

                {{-- Price Comparison --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 text-sm">Price Comparison</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Suggested Price</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">
                                ₱<span x-text="formatNumber(result.suggested_price)">0</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Package Price</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($package->price, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Difference</span>
                            <span class="font-bold"
                                  :class="priceDiff >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                <span x-text="(priceDiff >= 0 ? '+' : '') + '₱' + formatNumber(Math.abs(priceDiff))">—</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total @ {{ $package->pax }} guests</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                ₱<span x-text="formatNumber(result.suggested_price * {{ $package->pax }})">0</span>
                            </span>
                        </div>
                    </div>

                    <button type="button"
                            @click="applyToFinalPrice()"
                            class="mt-4 w-full py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-500 font-semibold text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                        Use Suggested → Final Price
                    </button>
                </div>

                {{-- All Templates Panel --}}
                @if($availableTemplates->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1 text-sm">Your Costing Templates</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">
                        Click any template to load its values into the form above.
                    </p>
                    <div class="space-y-2">
                        @foreach($availableTemplates as $tpl)
                        <div class="flex items-center justify-between p-3 rounded-lg border cursor-pointer transition-all
                            {{ $tpl->is_default_template
                                ? 'border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20'
                                : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50/50 dark:hover:bg-blue-900/10' }}"
                             onclick="loadTemplate({{ json_encode([
                                 'ingredient_cost'       => (float)($tpl->ingredient_cost ?? 0),
                                 'labor_cost'            => (float)($tpl->labor_cost ?? 0),
                                 'equipment_cost'        => (float)($tpl->equipment_cost ?? 0),
                                 'consumables_cost'      => (float)($tpl->consumables_cost ?? 0),
                                 'overhead_cost'         => (float)($tpl->overhead_cost ?? 0),
                                 'transport_cost'        => (float)($tpl->transport_cost ?? 0),
                                 'profit_margin_percent' => (float)($tpl->profit_margin_percent ?? 25),
                             ]) }})">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    @if($tpl->is_default_template)
                                        <span class="text-amber-500 text-sm">⭐</span>
                                    @endif
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                        {{ $tpl->template_name ?: $tpl->package->name }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    ₱{{ number_format($tpl->total_cost, 2) }} cost · {{ number_format($tpl->profit_margin_percent, 0) }}% margin
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quick Quotation Generator --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1 text-sm">Generate Quotation</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Quick printable receipt-style quote</p>
                    <form action="{{ route('caterer.package.quotation', $package->id) }}" method="GET" target="_blank" class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Customer Name</label>
                            <input type="text" name="customer_name" placeholder="Optional"
                                   class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Guest Count</label>
                                <input type="number" name="guest_count" value="{{ $package->pax }}" min="1"
                                       class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Valid for (days)</label>
                                <input type="number" name="validity_days" value="7" min="1" max="90"
                                       class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Event Type</label>
                            <select name="event_type"
                                    class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                                <option value="">Select...</option>
                                <option>Wedding</option>
                                <option>Birthday Party</option>
                                <option>Corporate Event</option>
                                <option>Anniversary</option>
                                <option>Reunion</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Event Date (optional)</label>
                            <input type="date" name="event_date"
                                   class="w-full mt-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 font-semibold text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-white transition-colors flex items-center justify-center gap-2">
                            🖨️ Generate PDF Quotation
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        const componentLabels = {
            ingredient_cost:  'Ingredients',
            labor_cost:       'Labor',
            equipment_cost:   'Equipment',
            consumables_cost: 'Consumables',
            overhead_cost:    'Overhead',
            transport_cost:   'Transport',
        };

        const componentColors = {
            ingredient_cost:  'bg-orange-400',
            labor_cost:       'bg-blue-400',
            equipment_cost:   'bg-green-400',
            consumables_cost: 'bg-yellow-400',
            overhead_cost:    'bg-purple-400',
            transport_cost:   'bg-pink-400',
        };

        // Load a saved template into the cost component inputs
        function loadTemplate(data) {
            const fields = [
                'ingredient_cost', 'labor_cost', 'equipment_cost',
                'consumables_cost', 'overhead_cost', 'transport_cost',
                'profit_margin_percent',
            ];
            fields.forEach(key => {
                const el = document.getElementById(key);
                if (el) {
                    el.value = data[key] || '';
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }

        function costingTool() {
            return {
                componentLabels,
                componentColors,
                marginPercent: {{ $costing->profit_margin_percent ?? 25 }},
                result: {
                    total_cost:      {{ $costing->total_cost ?? 0 }},
                    profit_amount:   {{ $costing->profit_amount ?? 0 }},
                    suggested_price: {{ $costing->suggested_price ?? 0 }},
                    breakdown: {
                        @foreach(['ingredient_cost','labor_cost','equipment_cost','consumables_cost','overhead_cost','transport_cost'] as $key)
                        {{ $key }}: { amount: {{ $costing->{$key} ?? 0 }}, percent: 0 },
                        @endforeach
                    }
                },
                priceDiff: 0,
                debounceTimer: null,

                get currentPrice() { return {{ $package->price }}; },

                init() {
                    this.$nextTick(() => this.fetchCalc());
                },

                recalculate() {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.fetchCalc(), 250);
                },

                async fetchCalc() {
                    const form = document.getElementById('costingForm');
                    const data = new FormData(form);
                    const body = {};
                    for (const [k, v] of data.entries()) body[k] = v;
                    this.marginPercent = parseFloat(body.profit_margin_percent) || 25;

                    try {
                        const resp = await fetch('{{ route("caterer.costing.calculate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(body),
                        });
                        const json = await resp.json();
                        this.result  = json;
                        this.priceDiff = json.suggested_price - {{ $package->price }};
                    } catch (e) {
                        console.error('Calc error', e);
                    }
                },

                applyToFinalPrice() {
                    if (this.$refs.finalPrice) {
                        this.$refs.finalPrice.value = this.result.suggested_price.toFixed(2);
                    }
                },

                formatNumber(val) {
                    return parseFloat(val || 0).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                },
            };
        }
    </script>
</x-app-layout>
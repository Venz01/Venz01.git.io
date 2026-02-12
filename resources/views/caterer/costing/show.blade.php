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
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Current price:</span>
                <span class="font-bold text-gray-900 dark:text-white">₱{{ number_format($package->price, 2) }}/head</span>
            </div>
        </div>
    </x-slot>

    <div x-data="costingTool()" class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-300 text-sm">
                ✅ {{ session('success') }}
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

                    {{-- Menu items quick view --}}
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
                            Menu items total: ₱{{ number_format($package->items->sum('price'), 2) }}/head
                            <span class="text-gray-400 ml-1">(use this as a starting point for ingredient cost)</span>
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

                    <div class="p-6 space-y-4">
                        @php
                            $components = [
                                ['key' => 'ingredient_cost',  'label' => 'Ingredients / Raw Food',  'icon' => '🥩', 'hint' => 'Cost of all raw ingredients per head'],
                                ['key' => 'labor_cost',       'label' => 'Labor & Staffing',         'icon' => '👨‍🍳', 'hint' => 'Cook, servers, crew per head'],
                                ['key' => 'equipment_cost',   'label' => 'Equipment & Rentals',      'icon' => '🍽️', 'hint' => 'Tables, linens, chafing dishes per head'],
                                ['key' => 'consumables_cost', 'label' => 'Consumables & Packaging',  'icon' => '🥄', 'hint' => 'Utensils, softdrinks, napkins per head'],
                                ['key' => 'overhead_cost',    'label' => 'Overhead & Utilities',     'icon' => '⚡', 'hint' => 'Kitchen overhead, electricity per head'],
                                ['key' => 'transport_cost',   'label' => 'Transport & Logistics',    'icon' => '🚐', 'hint' => 'Delivery to venue per head'],
                            ];
                        @endphp

                        @foreach($components as $comp)
                        <div class="flex items-center gap-4 p-4 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-700 transition-colors group">
                            <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center text-xl flex-shrink-0 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                                {{ $comp['icon'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="{{ $comp['key'] }}" class="block text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $comp['label'] }}
                                </label>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $comp['hint'] }}</p>
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
                                           value="{{ old($comp['key'], $costing->{$comp['key']} ?? '') }}"
                                           @input="recalculate()"
                                           class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right font-mono">
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
                            <textarea name="notes"
                                      rows="2"
                                      placeholder="e.g. pricing valid for events > 100 guests, adjust labor for events outside city..."
                                      class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $costing->notes ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- Final Price & Submit --}}
                    <div class="px-6 pb-6 pt-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4 items-end">
                            <div class="flex-1">
                                <label for="final_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Final Price per Head
                                    <span class="text-gray-400 font-normal">(you decide — suggested is below)</span>
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

                {{-- Cost Breakdown Donut Chart --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 text-sm">Cost Breakdown</h3>

                    <div x-show="result.total_cost > 0" class="space-y-2.5">
                        @php
                            $componentLabels = [
                                'ingredient_cost'  => ['label' => 'Ingredients',   'color' => 'bg-orange-400'],
                                'labor_cost'       => ['label' => 'Labor',          'color' => 'bg-blue-400'],
                                'equipment_cost'   => ['label' => 'Equipment',      'color' => 'bg-green-400'],
                                'consumables_cost' => ['label' => 'Consumables',    'color' => 'bg-yellow-400'],
                                'overhead_cost'    => ['label' => 'Overhead',       'color' => 'bg-purple-400'],
                                'transport_cost'   => ['label' => 'Transport',      'color' => 'bg-pink-400'],
                            ];
                        @endphp
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
        // ── Component labels for breakdown display ───────────────────────────
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

                get currentPrice() {
                    return {{ $package->price }};
                },

                recalculate() {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.fetchCalc(), 250);
                },

                async fetchCalc() {
                    const form  = document.getElementById('costingForm');
                    const data  = new FormData(form);
                    const body  = {};

                    for (const [k, v] of data.entries()) {
                        body[k] = v;
                    }

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
                        this.result = json;
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
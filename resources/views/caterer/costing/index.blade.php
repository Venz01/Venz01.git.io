<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💰 Costing & Pricing Tool
            </h2>
            <a href="{{ route('caterer.menus') }}"
               class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                ← Back to Menus
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- ── Summary Cards ─────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $cards = [
                    ['label' => 'Total Packages',   'value' => $stats['total_packages'],  'icon' => '📦', 'color' => 'blue'],
                    ['label' => 'Costed Packages',  'value' => $stats['costed_packages'], 'icon' => '✅', 'color' => 'green'],
                    ['label' => 'Avg. Margin',       'value' => number_format($stats['avg_margin'] ?? 0, 1).'%', 'icon' => '📈', 'color' => 'purple'],
                    ['label' => 'Avg. Price/Head',   'value' => '₱'.number_format($stats['avg_price'] ?? 0, 0), 'icon' => '💵', 'color' => 'amber'],
                ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <div class="text-2xl mb-2">{{ $card['icon'] }}</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $card['value'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $card['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- ── Packages Table ──────────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Packages</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $packages->where('has_costing', true)->count() }} / {{ $packages->count() }} costed
                </span>
            </div>

            @if($packages->isEmpty())
                <div class="text-center py-16">
                    <div class="text-5xl mb-4">📦</div>
                    <p class="text-gray-500 dark:text-gray-400">No packages yet. Create a package first.</p>
                    <a href="{{ route('caterer.menus') }}"
                       class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Go to Menus
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">Package</th>
                                <th class="px-6 py-3 text-right">Current Price</th>
                                <th class="px-6 py-3 text-right">Total Cost</th>
                                <th class="px-6 py-3 text-right">Suggested</th>
                                <th class="px-6 py-3 text-right">Margin</th>
                                <th class="px-6 py-3 text-center">Costing</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($packages as $pkg)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($pkg['image_path'])
                                            <img src="{{ $pkg['image_path'] }}" alt=""
                                                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0 text-lg">📦</div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $pkg['name'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $pkg['pax'] }} guests · {{ $pkg['items_count'] }} items
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    ₱{{ number_format($pkg['current_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-400">
                                    {{ $pkg['has_costing'] && $pkg['total_cost'] > 0 ? '₱'.number_format($pkg['total_cost'], 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($pkg['suggested_price'] > 0)
                                        @php
                                            $diff = $pkg['current_price'] - $pkg['suggested_price'];
                                            $diffClass = $diff >= 0
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400';
                                        @endphp
                                        <span class="font-medium text-blue-600 dark:text-blue-400">
                                            ₱{{ number_format($pkg['suggested_price'], 2) }}
                                        </span>
                                        <span class="text-xs {{ $diffClass }} block">
                                            {{ $diff >= 0 ? '+' : '' }}₱{{ number_format($diff, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(!is_null($pkg['margin_percent']))
                                        @php
                                            $m = $pkg['margin_percent'];
                                            $mColor = $m >= 20 ? 'text-green-600 dark:text-green-400'
                                                    : ($m >= 10 ? 'text-amber-600 dark:text-amber-400'
                                                    : 'text-red-600 dark:text-red-400');
                                        @endphp
                                        <span class="font-semibold {{ $mColor }}">{{ number_format($m, 1) }}%</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pkg['has_costing'])
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-full font-medium">
                                            ✓ {{ $pkg['components_count'] }}/6 components
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs rounded-full">
                                            Not set up
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('caterer.costing.show', $pkg['id']) }}"
                                           class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                            {{ $pkg['has_costing'] ? 'Edit Costing' : 'Set Up Costing' }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── Clone Tool ────────────────────────────────────────────────── --}}
        @if($packages->where('has_costing', true)->count() > 0 && $packages->count() > 1)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Clone Costing Template</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Copy cost structure from one package to another to save time on similar packages.
            </p>
            <form action="{{ route('caterer.costing.clone') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div class="flex-1 min-w-36">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">From Package</label>
                    <select name="source_package_id" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200">
                        @foreach($packages->where('has_costing', true) as $pkg)
                            <option value="{{ $pkg['id'] }}">{{ $pkg['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-36">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">To Package</label>
                    <select name="target_package_id" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200">
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg['id'] }}">{{ $pkg['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors font-medium">
                    Clone Template
                </button>
            </form>
        </div>
        @endif

    </div>
</x-app-layout>
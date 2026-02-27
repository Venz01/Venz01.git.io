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

        @if(session('success'))
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-800 dark:text-red-300 text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

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

        {{-- ── Default Template Manager ────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border-2 {{ $defaultTemplate ? 'border-amber-300 dark:border-amber-700' : 'border-dashed border-gray-300 dark:border-gray-600' }} shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Default Costing Template</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Auto-applied to every new package you create. You can always override per-package in the Costing Tool.
                        </p>
                    </div>
                </div>
                @if($defaultTemplate)
                    <form method="POST" action="{{ route('caterer.costing.clear-default') }}">
                        @csrf
                        <button type="submit"
                                class="text-xs text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                            Clear Default
                        </button>
                    </form>
                @endif
            </div>

            @if($defaultTemplate)
                {{-- Show active default --}}
                <div class="px-6 py-5 bg-amber-50/50 dark:bg-amber-900/10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                                📋
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">
                                    {{ $defaultTemplate->template_name ?: $defaultTemplate->package->name }}
                                </p>
                                @if($defaultTemplate->template_name && $defaultTemplate->package)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">From: {{ $defaultTemplate->package->name }}</p>
                                @endif
                                <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-600 dark:text-gray-400">
                                    @if($defaultTemplate->ingredient_cost)
                                        <span class="flex items-center gap-1">🥩 ₱{{ number_format($defaultTemplate->ingredient_cost, 2) }}</span>
                                    @endif
                                    @if($defaultTemplate->labor_cost)
                                        <span class="flex items-center gap-1">👨‍🍳 ₱{{ number_format($defaultTemplate->labor_cost, 2) }}</span>
                                    @endif
                                    @if($defaultTemplate->equipment_cost)
                                        <span class="flex items-center gap-1">🍽️ ₱{{ number_format($defaultTemplate->equipment_cost, 2) }}</span>
                                    @endif
                                    @if($defaultTemplate->consumables_cost)
                                        <span class="flex items-center gap-1">🥄 ₱{{ number_format($defaultTemplate->consumables_cost, 2) }}</span>
                                    @endif
                                    @if($defaultTemplate->overhead_cost)
                                        <span class="flex items-center gap-1">⚡ ₱{{ number_format($defaultTemplate->overhead_cost, 2) }}</span>
                                    @endif
                                    @if($defaultTemplate->transport_cost)
                                        <span class="flex items-center gap-1">🚐 ₱{{ number_format($defaultTemplate->transport_cost, 2) }}</span>
                                    @endif
                                    <span class="flex items-center gap-1 font-semibold text-purple-600 dark:text-purple-400">
                                        📈 {{ number_format($defaultTemplate->profit_margin_percent, 0) }}% margin
                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('caterer.costing.show', $defaultTemplate->package_id) }}"
                           class="flex-shrink-0 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Template
                        </a>
                    </div>
                </div>

                {{-- Other available templates to switch to --}}
                @if($availableTemplates->where('is_default_template', false)->count() > 0)
                <div class="px-6 py-4 border-t border-amber-100 dark:border-amber-900/30">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-3">Switch default to:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableTemplates->where('is_default_template', false) as $tpl)
                        <form method="POST" action="{{ route('caterer.costing.set-default', $tpl->id) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:border-amber-400 hover:text-amber-700 dark:hover:text-amber-400 transition-colors bg-white dark:bg-gray-800">
                                📋 {{ $tpl->template_name ?: $tpl->package->name }}
                                <span class="text-gray-400">₱{{ number_format($tpl->total_cost, 0) }}</span>
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
                @endif

            @elseif($availableTemplates->count() > 0)
                {{-- No default set but templates exist --}}
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        You have saved costings. Pick one to use as your default template for new packages:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($availableTemplates as $tpl)
                        <form method="POST" action="{{ route('caterer.costing.set-default', $tpl->id) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700
                                           hover:border-amber-400 dark:hover:border-amber-600 hover:bg-amber-50/50 dark:hover:bg-amber-900/10
                                           transition-all group">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                        {{ $tpl->template_name ?: $tpl->package->name }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full flex-shrink-0 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                                        Set Default
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-0.5">
                                    <p>Total Cost: ₱{{ number_format($tpl->total_cost, 2) }}</p>
                                    <p>Margin: {{ number_format($tpl->profit_margin_percent, 0) }}%</p>
                                    <p>{{ $tpl->filled_components_count }}/6 components filled</p>
                                </div>
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- No costings at all --}}
                <div class="px-6 py-10 text-center">
                    <div class="text-4xl mb-3">📋</div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        No costing templates yet. Save a costing for any package, then mark it as your default here.
                    </p>
                    @if($packages->count() > 0)
                        <a href="{{ route('caterer.costing.show', $packages->first()['id']) }}"
                           class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors">
                            Set Up First Costing →
                        </a>
                    @endif
                </div>
            @endif
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
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $pkg['name'] }}</span>
                                                @if($pkg['is_default_template'])
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-700">
                                                        ⭐ Default
                                                    </span>
                                                @endif
                                            </div>
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
                                            $diff      = $pkg['current_price'] - $pkg['suggested_price'];
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
                                            $m      = $pkg['margin_percent'];
                                            $mColor = $m >= 20
                                                ? 'text-green-600 dark:text-green-400'
                                                : ($m >= 10 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400');
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
                                        @if($pkg['has_costing'] && !$pkg['is_default_template'])
                                            <form method="POST" action="{{ route('caterer.costing.set-default', $pkg['costing_id']) }}">
                                                @csrf
                                                <button type="submit"
                                                        title="Set as default template"
                                                        class="p-1.5 text-gray-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($pkg['is_default_template'])
                                            <span title="Current default template" class="p-1.5 text-amber-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </span>
                                        @endif
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
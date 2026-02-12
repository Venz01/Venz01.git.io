{{--
    DIETARY TAGS SELECTOR FOR PACKAGES
    ────────────────────────────────────────────────────────────────────────────
    Usage in package form modals:
    
    @include('caterer.partials.package-dietary-tags', [
        'selectedTags' => $package->dietary_tags ?? []  // For edit mode
    ])
    
    This component displays checkboxes for dietary preferences that the package 
    accommodates (No Pork, Vegetarian, Vegan, Halal, Gluten-Free, Dairy-Free, Seafood-Free)
    ────────────────────────────────────────────────────────────────────────────
--}}

@php
    $dietaryOptions = [
        'no_pork'      => ['label' => 'No Pork',      'icon' => '🐷', 'color' => 'red'],
        'vegetarian'   => ['label' => 'Vegetarian',   'icon' => '🥦', 'color' => 'green'],
        'vegan'        => ['label' => 'Vegan',        'icon' => '🌱', 'color' => 'green'],
        'halal'        => ['label' => 'Halal',        'icon' => '☪️',  'color' => 'emerald'],
        'gluten_free'  => ['label' => 'Gluten-Free',  'icon' => '🌾', 'color' => 'yellow'],
        'dairy_free'   => ['label' => 'Dairy-Free',   'icon' => '🥛', 'color' => 'blue'],
        'seafood_free' => ['label' => 'Seafood-Free', 'icon' => '🦐', 'color' => 'cyan'],
    ];
    
    $selectedTags = $selectedTags ?? [];
@endphp

<div class="dietary-tags-section">
    <h3 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-2">
        Dietary Preferences & Allergy Tags
    </h3>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
        Select all dietary preferences that this package accommodates. This helps customers find suitable options.
    </p>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
        @foreach($dietaryOptions as $value => $option)
            @php
                $isChecked = in_array($value, (array) $selectedTags);
            @endphp
            <label
                class="dietary-tag-option relative flex flex-col items-center gap-1.5 p-2.5 rounded-lg border-2 cursor-pointer transition-all duration-200 select-none
                    {{ $isChecked
                        ? 'border-green-500 bg-green-50 dark:bg-green-900/30 shadow-sm'
                        : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 hover:border-green-300 hover:bg-green-50/50 dark:hover:bg-green-900/10' }}"
                data-tag-value="{{ $value }}"
            >
                <input
                    type="checkbox"
                    name="dietary_tags[]"
                    value="{{ $value }}"
                    {{ $isChecked ? 'checked' : '' }}
                    class="dietary-tag-checkbox sr-only"
                    onchange="toggleDietaryTag(this)"
                >
                <span class="text-xl leading-none">{{ $option['icon'] }}</span>
                <span class="text-xs font-medium text-center text-gray-700 dark:text-gray-300 leading-tight">
                    {{ $option['label'] }}
                </span>
                {{-- Checkmark badge --}}
                <span
                    class="dietary-tag-check absolute top-1 right-1 w-3.5 h-3.5 rounded-full bg-green-500 flex items-center justify-center transition-all duration-200
                        {{ $isChecked ? 'opacity-100 scale-100' : 'opacity-0 scale-0' }}"
                >
                    <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </label>
        @endforeach
    </div>

    {{-- Info banner --}}
    <div class="mt-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-3 flex gap-2">
        <div class="shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
            </svg>
        </div>
        <p class="text-xs text-blue-700 dark:text-blue-300">
            <strong>For customers:</strong> Packages with matching dietary tags will be highlighted when they browse. 
            This helps them quickly find suitable catering options.
        </p>
    </div>
</div>

<script>
    function toggleDietaryTag(checkbox) {
        const label = checkbox.closest('label');
        const checkBadge = label.querySelector('.dietary-tag-check');

        if (checkbox.checked) {
            label.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/30', 'shadow-sm');
            label.classList.remove('border-gray-200', 'dark:border-gray-600');
            checkBadge.classList.remove('opacity-0', 'scale-0');
            checkBadge.classList.add('opacity-100', 'scale-100');
        } else {
            label.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/30', 'shadow-sm');
            label.classList.add('border-gray-200', 'dark:border-gray-600');
            checkBadge.classList.add('opacity-0', 'scale-0');
            checkBadge.classList.remove('opacity-100', 'scale-100');
        }
    }
</script>
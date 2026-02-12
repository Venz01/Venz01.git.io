{{--
    PACKAGE DIETARY COMPATIBILITY BADGES
    ────────────────────────────────────────────────────────────────────────────
    Usage (inside package cards on customer view):

        @auth
            @if(auth()->user()->isCustomer() && auth()->user()->hasDietaryPreferences())
                @include('customer.partials.package-dietary-badges', [
                    'package' => $package,
                ])
            @endif
        @endauth

    This component compares the customer's dietary preferences with the package's
    dietary tags and displays compatibility badges.
    ────────────────────────────────────────────────────────────────────────────
--}}

@auth
@if(auth()->user()->isCustomer())
@php
    $customer = auth()->user();
    $customerPrefs = (array) ($customer->dietary_preferences ?? []);
    $packageTags = (array) ($package->dietary_tags ?? []);
    
    // Only show badges if customer has preferences or package has tags
    if (empty($customerPrefs) && empty($packageTags)) {
        return;
    }
    
    $labels = \App\Models\User::dietaryLabels();
    
    // Find matches and mismatches
    $matches = array_intersect($customerPrefs, $packageTags);
    $mismatches = array_diff($customerPrefs, $packageTags);
    
    // If package has tags, show them even if customer has no preferences
    $displayTags = !empty($customerPrefs) ? $matches : $packageTags;
@endphp

@if(!empty($displayTags) || !empty($mismatches))
<div class="mt-3 flex flex-wrap gap-1.5">

    {{-- ✓ Compatible badges (green) --}}
    @foreach(array_slice($displayTags, 0, 3) as $tag)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-700"
          title="Compatible with your {{ $labels[$tag] ?? $tag }} preference">
        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ $labels[$tag] ?? ucfirst(str_replace('_', ' ', $tag)) }}
    </span>
    @endforeach
    
    {{-- Show "+" badge if more than 3 matches --}}
    @if(count($displayTags) > 3)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-700">
        +{{ count($displayTags) - 3 }} more
    </span>
    @endif

    {{-- ⚠ Incompatible badges (yellow/warning) - only if customer has preferences --}}
    @if(!empty($customerPrefs))
        @foreach(array_slice($mismatches, 0, 2) as $tag)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700"
              title="This package may not be suitable for {{ $labels[$tag] ?? $tag }}">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            Not {{ $labels[$tag] ?? ucfirst(str_replace('_', ' ', $tag)) }}
        </span>
        @endforeach
    @endif

</div>
@endif

@endif
@endauth
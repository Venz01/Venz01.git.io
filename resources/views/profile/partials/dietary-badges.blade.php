    {{--
        DIETARY BADGES COMPONENT
        ────────────────────────────────────────────────────────────────────────────
        Usage (inside any package or menu card):

            @auth
                @if(auth()->user()->isCustomer() && auth()->user()->hasDietaryPreferences())
                    @include('profile.partials.dietary-badges', [
                        'packageDescription' => $package->description ?? '',
                        'packageName'        => $package->name,
                    ])
                @endif
            @endauth

        The component compares the customer's saved preferences/allergies against
        the package name + description text and renders coloured badges accordingly.
        ────────────────────────────────────────────────────────────────────────────
    --}}

    @auth
    @if(auth()->user()->isCustomer())
    @php
        $customer    = auth()->user();
        $prefs       = (array) ($customer->dietary_preferences ?? []);
        $allergies   = strtolower($customer->food_allergies ?? '');
        $searchText  = strtolower(($packageName ?? '') . ' ' . ($packageDescription ?? ''));

        // ── Keyword maps for each preference ────────────────────────────────────
        $conflictKeywords = [
            'no_pork'      => ['pork', 'lechon', 'bacon', 'ham', 'chorizo', 'longganisa', 'liempo'],
            'vegetarian'   => ['beef', 'chicken', 'pork', 'fish', 'seafood', 'shrimp', 'meat', 'lechon'],
            'vegan'        => ['beef', 'chicken', 'pork', 'fish', 'seafood', 'egg', 'dairy', 'milk', 'cheese', 'butter', 'cream', 'meat'],
            'halal'        => ['pork', 'bacon', 'ham', 'alcohol', 'wine', 'beer'],
            'gluten_free'  => ['wheat', 'flour', 'bread', 'pasta', 'noodle', 'pancit', 'gluten', 'soy sauce'],
            'dairy_free'   => ['milk', 'cheese', 'butter', 'cream', 'yogurt', 'dairy'],
            'seafood_free' => ['fish', 'shrimp', 'crab', 'shellfish', 'squid', 'prawn', 'lobster', 'clam', 'mussel', 'seafood'],
            'nut_free'     => ['peanut', 'cashew', 'almond', 'walnut', 'pecan', 'nut'],
            'low_sodium'   => ['salty', 'salted', 'bagoong', 'patis', 'soy sauce', 'high sodium'],
            'diabetic'     => ['sugar', 'sweet', 'syrup', 'leche flan', 'halo-halo', 'dessert'],
        ];

        $warnings = [];   // preferences that may conflict
        $safe     = [];   // preferences that look fine

        foreach ($prefs as $pref) {
            $keywords = $conflictKeywords[$pref] ?? [];
            $conflict = false;
            foreach ($keywords as $kw) {
                if (str_contains($searchText, $kw)) {
                    $conflict = true;
                    break;
                }
            }
            if ($conflict) {
                $warnings[] = $pref;
            } else {
                $safe[] = $pref;
            }
        }

        // Check free-text allergies
        $allergyWarning = false;
        if ($allergies) {
            $allergyList = array_filter(array_map('trim', explode(',', $allergies)));
            foreach ($allergyList as $allergen) {
                if ($allergen && str_contains($searchText, $allergen)) {
                    $allergyWarning = true;
                    break;
                }
            }
        }

        $labels = \App\Models\User::dietaryLabels();
    @endphp

    @if($warnings || $allergyWarning || $safe)
    <div class="mt-3 flex flex-wrap gap-1.5">

        {{-- ⚠ Potential conflict badges --}}
        @foreach($warnings as $pref)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-700"
            title="May conflict with your {{ $labels[$pref] ?? $pref }} preference">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            {{ $labels[$pref] ?? ucfirst(str_replace('_', ' ', $pref)) }}
        </span>
        @endforeach

        {{-- ⚠ Allergy conflict badge --}}
        @if($allergyWarning)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 border border-orange-200 dark:border-orange-700"
            title="May contain ingredients you are allergic to">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            Allergy Warning
        </span>
        @endif

        {{-- ✓ Compatible preference badges (max 3 to avoid clutter) --}}
        @foreach(array_slice($safe, 0, 3) as $pref)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-700"
            title="Compatible with your {{ $labels[$pref] ?? $pref }} preference">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ $labels[$pref] ?? ucfirst(str_replace('_', ' ', $pref)) }}
        </span>
        @endforeach

    </div>
    @endif

    @endif
    @endauth


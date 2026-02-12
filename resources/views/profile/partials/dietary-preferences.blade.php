<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Dietary Preferences & Allergies') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Let us know your dietary needs so we can highlight compatible catering packages and menus for you.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.dietary.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- ── Dietary Preferences ── --}}
        <div>
            <x-input-label :value="__('Dietary Preferences')" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Select all that apply — caterers and packages will be flagged accordingly.
            </p>

            @php
                $allTags = \App\Models\DietaryTag::orderBy('name')->get();
                $savedPreferences = old(
                    'dietary_preferences',
                    is_array($user->dietary_preferences) ? $user->dietary_preferences : []
                );
            @endphp

            @if($allTags->count() > 0)
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                    @foreach($allTags as $tag)
                        @php
                            $isChecked = in_array($tag->slug, (array) $savedPreferences);
                        @endphp
                        <label
                            class="dietary-option relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none
                                {{ $isChecked
                                    ? 'border-green-500 bg-green-50 dark:bg-green-900/30 shadow-md'
                                    : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-green-300 hover:bg-green-50/50 dark:hover:bg-green-900/10' }}"
                            id="label-{{ $tag->slug }}"
                        >
                            <input
                                type="checkbox"
                                name="dietary_preferences[]"
                                value="{{ $tag->slug }}"
                                {{ $isChecked ? 'checked' : '' }}
                                class="dietary-checkbox sr-only"
                                onchange="toggleDietaryCard(this)"
                            >
                            <span class="text-2xl leading-none">{{ $tag->icon }}</span>
                            <span class="text-xs font-medium text-center text-gray-700 dark:text-gray-300 leading-tight">
                                {{ $tag->name }}
                            </span>
                            {{-- Checkmark badge --}}
                            <span
                                class="dietary-check absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-green-500 flex items-center justify-center transition-all duration-200
                                    {{ $isChecked ? 'opacity-100 scale-100' : 'opacity-0 scale-0' }}"
                            >
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                        </label>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 italic mt-3">
                    No dietary tags are currently available. Please check back later.
                </p>
            @endif
            
            <x-input-error class="mt-2" :messages="$errors->get('dietary_preferences')" />
        </div>

        {{-- ── Food Allergies ── --}}
        <div>
            <!-- <x-input-label for="food_allergies" :value="__('Food Allergies & Ingredients to Avoid')" /> -->
            <!-- <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                List specific ingredients or allergens you must avoid. This helps caterers prepare safe meals for you.
            </p> -->
            <div class="mt-2 relative">
                <!-- <div class="absolute top-3 left-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div> -->
                <!-- <textarea
                    id="food_allergies"
                    name="food_allergies"
                    rows="3"
                    maxlength="1000"
                    class="mt-1 block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                           focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600
                           rounded-xl shadow-sm resize-none"
                    placeholder="Example: Peanuts, shrimp, eggs, shellfish, tree nuts, soy, wheat, milk..."
                    oninput="updateCharCount(this)"
                >{{ old('food_allergies', $user->food_allergies) }}</textarea> -->
                <!-- <div class="mt-1 flex justify-between text-xs text-gray-400">
                    <span>Separate items with commas</span>
                    <span id="char-count">
                        {{ strlen(old('food_allergies', $user->food_allergies ?? '')) }}/1000
                    </span>
                </div> -->
            </div>
            <!-- <x-input-error class="mt-2" :messages="$errors->get('food_allergies')" /> -->
        </div>

        {{-- ── Info Banner ── --}}
        <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 flex gap-3">
            <div class="shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
                </svg>
            </div>
            <p class="text-sm text-blue-700 dark:text-blue-300">
                <strong>How this is used:</strong> Your saved preferences will automatically highlight compatible
                packages and menus when browsing caterers — and flag items that may not suit your dietary needs.
                Caterers are also notified of your preferences when you make a booking.
            </p>
        </div>

        {{-- ── Save Button ── --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800
                       text-white text-sm font-semibold rounded-xl shadow-sm transition-colors duration-200 focus:outline-none
                       focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Preferences') }}
            </button>

            @if (session('dietary_success'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400 flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('dietary_success') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        function toggleDietaryCard(checkbox) {
            const label = checkbox.closest('label');
            const checkBadge = label.querySelector('.dietary-check');

            if (checkbox.checked) {
                label.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/30', 'shadow-md');
                label.classList.remove('border-gray-200', 'dark:border-gray-700');
                checkBadge.classList.remove('opacity-0', 'scale-0');
                checkBadge.classList.add('opacity-100', 'scale-100');
            } else {
                label.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/30', 'shadow-md');
                label.classList.add('border-gray-200', 'dark:border-gray-700');
                checkBadge.classList.add('opacity-0', 'scale-0');
                checkBadge.classList.remove('opacity-100', 'scale-100');
            }
        }

        function updateCharCount(textarea) {
            const counter = document.getElementById('char-count');
            if (counter) counter.textContent = textarea.value.length + '/1000';
        }
    </script>
</section>
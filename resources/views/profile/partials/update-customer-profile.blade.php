<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Personal Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your personal details and preferences.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Preferred Cuisine -->
        <div>
            <x-input-label for="preferred_cuisine" :value="__('Preferred Cuisine')" />
            <select id="preferred_cuisine" name="preferred_cuisine"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Select your preferred cuisine</option>
                @php
                    $cuisines = ['Filipino', 'Chinese', 'Japanese', 'Korean', 'Italian', 'American', 'Mexican', 'Indian', 'Thai', 'Mediterranean', 'Fusion', 'International'];
                @endphp
                @foreach($cuisines as $cuisine)
                    <option value="{{ $cuisine }}" {{ old('preferred_cuisine', $user->preferred_cuisine) == $cuisine ? 'selected' : '' }}>
                        {{ $cuisine }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Help us show you relevant caterers</p>
            <x-input-error class="mt-2" :messages="$errors->get('preferred_cuisine')" />
        </div>

        <!-- Default Address -->
        <div>
            <x-input-label for="default_address" :value="__('Default Event Address')" />
            <textarea id="default_address" name="default_address" rows="2"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="Your typical event venue address...">{{ old('default_address', $user->default_address) }}</textarea>
            <p class="mt-1 text-xs text-gray-500">This will be pre-filled when making bookings</p>
            <x-input-error class="mt-2" :messages="$errors->get('default_address')" />
        </div>

        <!-- City & Postal Code -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                              :value="old('city', $user->city)" placeholder="e.g., General Santos" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal Code')" />
                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                              :value="old('postal_code', $user->postal_code)" placeholder="9500" />
                <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Information') }}</x-primary-button>

            @if (session('success'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
            @endif
        </div>
    </form>
</section>
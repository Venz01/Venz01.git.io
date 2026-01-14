<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Business Hours & Availability') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Set your typical operating hours and working days.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
    
        <!-- Operating Hours -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="business_hours_start" :value="__('Opening Time')" />
                <input type="time" id="business_hours_start" name="business_hours_start"
                       value="{{ old('business_hours_start', $user->business_hours_start ? \Carbon\Carbon::parse($user->business_hours_start)->format('H:i') : '') }}"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <x-input-error class="mt-2" :messages="$errors->get('business_hours_start')" />
            </div>

            <div>
                <x-input-label for="business_hours_end" :value="__('Closing Time')" />
                <input type="time" id="business_hours_end" name="business_hours_end"
                       value="{{ old('business_hours_end', $user->business_hours_end ? \Carbon\Carbon::parse($user->business_hours_end)->format('H:i') : '') }}"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <x-input-error class="mt-2" :messages="$errors->get('business_hours_end')" />
            </div>
        </div>

        <!-- Operating Days -->
        <div>
            <x-input-label :value="__('Operating Days')" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $days = [
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday'
                    ];
                    $selectedDays = old('business_days', $user->business_days ?? []);
                @endphp
                @foreach($days as $value => $label)
                    <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                        <input type="checkbox" name="business_days[]" value="{{ $value }}"
                               {{ in_array($value, $selectedDays) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <p class="mt-2 text-xs text-gray-500">Select the days you typically accept bookings</p>
            <x-input-error class="mt-2" :messages="$errors->get('business_days')" />
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Note:</strong> These are your typical business hours. For specific date availability, use the 
                    <a href="{{ route('caterer.calendar') }}" class="underline font-semibold">Calendar & Availability</a> page.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Hours') }}</x-primary-button>

            @if (session('success'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
            @endif
        </div>
    </form>
</section>
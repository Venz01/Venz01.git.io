<x-guest-layout>
    <!-- Logo Section -->
    <div class="flex justify-center mb-6">
        <a href="/">
            <img src="{{ asset('images/logo.jpg') }}" alt="Restaurant Logo" class="h-28 w-auto">
        </a>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">CaterEase</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Catering Management System</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="{ role: '{{ old('role', 'customer') }}' }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <select id="role" name="role" x-model="role"
                class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                <option value="customer">{{ __('Customer') }}</option>
                <option value="caterer">{{ __('Caterer') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Caterer Additional Fields -->
        <template x-if="role === 'caterer'">
            <div class="mt-4 space-y-4">
                <p class="text-sm text-yellow-600 dark:text-yellow-400 font-semibold">
                    {{ __("All caterer applications require admin approval before activation.") }}
                </p>

                <div>
                    <x-input-label for="business_name" :value="__('Business Name (Registered Name)')" />
                    <x-text-input id="business_name" name="business_name" type="text" :value="old('business_name')"
                        class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="owner_full_name" :value="__('Owner\'s Full Name')" />
                    <x-text-input id="owner_full_name" name="owner_full_name" type="text"
                        :value="old('owner_full_name')" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('owner_full_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="business_address" :value="__('Business Address')" />
                    <x-text-input id="business_address" name="business_address" type="text"
                        :value="old('business_address')" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('business_address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="business_permit_number"
                        :value="__('Business Permit Number (from city/municipality)')" />
                    <x-text-input id="business_permit_number" name="business_permit_number" type="text"
                        :value="old('business_permit_number')" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('business_permit_number')" class="mt-2" />
                </div>

                <!-- Business Permit Upload -->
                <div>
                    <x-input-label for="business_permit_file"
                        :value="__('Upload Scanned Business Permit (PDF/JPG/PNG)')" class="dark:text-gray-300" />
                    <input id="business_permit_file" name="business_permit_file" type="file"
                        accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500
               file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0
               file:text-sm file:font-semibold
               file:bg-indigo-50 file:text-indigo-700
               hover:file:bg-indigo-100
               dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600" />
                    <x-input-error :messages="$errors->get('business_permit_file')" class="mt-2" />
                </div>

            </div>
        </template>

        <!-- Terms and Conditions Checkbox -->
        <div class="mt-6">
            <label class="flex items-start">
                <input type="checkbox" 
                       name="terms_accepted" 
                       id="terms_accepted"
                       value="1"
                       {{ old('terms_accepted') ? 'checked' : '' }}
                       required
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 mt-1">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    I agree to the 
                    <a href="{{ route('terms') }}" 
                       target="_blank" 
                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline font-medium">
                        Terms and Conditions
                    </a>
                    <span class="text-red-600 dark:text-red-400">*</span>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms_accepted')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-3">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
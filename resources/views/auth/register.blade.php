<x-guest-layout>
    <div class="flex items-center justify-center gap-3 mb-6">
        <div class="h-12 w-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-600/20 dark:border-emerald-400/20">
            <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase" class="h-9 w-9 object-contain dark:invert" />
        </div>
        <div class="text-left">
            <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Create your account</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Join CaterEase in under a minute</p>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="{ role: '{{ old('role', 'customer') }}' }">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 space-y-1.5">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 space-y-1.5">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 space-y-1.5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4 space-y-1.5">
            <x-input-label for="role" :value="__('Register As')" />
            <select id="role" name="role" x-model="role"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="customer">{{ __('Customer') }}</option>
                <option value="caterer">{{ __('Caterer') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Caterer Additional Fields -->
        <template x-if="role === 'caterer'">
            <div class="mt-4 space-y-4">
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200">
                    {{ __("All caterer applications require admin approval before activation.") }}
                </div>

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

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <p class="mt-5 text-center text-sm text-gray-600 dark:text-gray-400">
            {{ __('Already registered?') }}
            <a href="{{ route('login') }}"
               class="font-semibold text-emerald-700 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                {{ __('Sign in') }}
            </a>
        </p>
    </form>
</x-guest-layout>
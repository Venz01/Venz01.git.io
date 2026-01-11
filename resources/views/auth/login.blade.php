<x-guest-layout>
    <!-- Logo Section at the Top -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.jpg') }}" alt="Restaurant Logo" class="h-28 w-auto">
    </div>
    
    <!-- Optional: Add site name/tagline below logo -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">CaterEase</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Catering Management System</p>
    </div>

    <!-- Display ALL errors at the top prominently -->
    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-base font-semibold text-red-800 dark:text-red-200">
                        Unable to Access Account
                    </h3>
                    <div class="mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700 dark:text-red-300">
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                    @if($errors->has('email') && str_contains($errors->first('email'), 'suspended'))
                        <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-700">
                            <p class="text-xs text-red-600 dark:text-red-400">
                                <strong>Need help?</strong> Please contact our administrator for assistance with your account.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('register') }}"
                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 font-medium">
                {{ __('Don\'t have an account? Register here.') }}
            </a>
        </div>


        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
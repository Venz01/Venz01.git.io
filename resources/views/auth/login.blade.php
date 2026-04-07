<x-guest-layout>
    <div class="flex items-center justify-center gap-3 mb-6">
        <div class="h-12 w-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-600/20 dark:border-emerald-400/20">
            <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase" class="h-9 w-9 object-contain dark:invert" />
        </div>
        <div class="text-left">
            <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Welcome back</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Sign in to continue to CaterEase</p>
        </div>
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
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" placeholder="you@example.com" />
        </div>

        <!-- Password -->
        <div class="mt-4 space-y-1.5">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-emerald-700 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password" name="password" required
                autocomplete="current-password" placeholder="••••••••" />
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

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <p class="mt-5 text-center text-sm text-gray-600 dark:text-gray-400">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}"
                class="font-semibold text-emerald-700 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                {{ __('Create one') }}
            </a>
        </p>
    </form>
</x-guest-layout>
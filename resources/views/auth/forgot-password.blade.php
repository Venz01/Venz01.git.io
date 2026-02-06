<x-guest-layout>
    <!-- Logo Section at the Top -->
    <div class="flex justify-center mb-0">
        <img src="{{ asset('images/foodlogo.png') }}" 
             alt="Restaurant Logo" 
             style="height: 200px; width: auto;"
             class="logo-dark-mode">
    </div>
    
    <style>
        @media (prefers-color-scheme: dark) {
            .logo-dark-mode {
                filter: invert(1) brightness(2) !important;
            }
        }
    </style>
    
    <!-- Site Name -->
    <div class="text-center mb-0">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">CaterEase</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0">Reset Your Password</p>
    </div>

    <!-- Information Box -->
    <div class="mb-4 rounded-md bg-blue-50 dark:bg-blue-900/30 p-4 border border-blue-200 dark:border-blue-800">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    {{ __('Forgot your password? No problem. Just enter your email address and we will send you a password reset link.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <div class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700 dark:text-red-300">
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" 
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                {{ __('← Back to Login') }}
            </a>

            <x-primary-button>
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Help Text -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ __('Need help? Contact support at') }} 
            <a href="mailto:support@caterease.com" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                support@caterease.com
            </a>
        </p>
    </div>
</x-guest-layout>
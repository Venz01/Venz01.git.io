<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sign In') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Login Container with Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 rounded-3xl overflow-hidden bg-white dark:bg-gray-900 shadow-2xl dark:shadow-2xl/50">
                
                <!-- Left Side - Hero Image Section -->
                <div class="hidden lg:flex relative overflow-hidden" style="background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.25) 100%), url('{{ asset('images/catering.jpg') }}'); background-size: cover; background-position: center;">
                    <!-- Dark Overlay for better readability -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-black/10"></div>
                    
                    <!-- Logo Badge - Top Left -->
                    <div class="absolute top-8 left-8 z-20">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30">
                            <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase" class="h-8 w-8 object-contain" />
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <!-- Mobile Header (visible only on mobile) -->
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-600/20 dark:border-emerald-400/20">
                            <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase" class="h-9 w-9 object-contain dark:invert" />
                        </div>
                        <div class="text-left">
                            <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Welcome back</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Sign in to CaterEase</p>
                        </div>
                    </div>

                    <!-- Desktop Header (visible only on desktop) -->
                    <div class="hidden lg:block mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Sign In</h2>
                        <p class="text-gray-600 dark:text-gray-400">Enter your credentials to access your account</p>
                    </div>

                    <!-- Display ALL errors at the top prominently -->
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
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
                        <div class="space-y-2">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required
                                autofocus autocomplete="username" placeholder="you@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6 space-y-2" x-data="{ showPassword: false }">
                            <div class="flex items-center justify-between">
                                <x-input-label for="password" :value="__('Password')" />
                                @if (Route::has('password.request'))
                                    <a class="text-sm font-medium text-emerald-700 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input id="password" 
                                    :type="showPassword ? 'text' : 'password'" 
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" 
                                    name="password" 
                                    required
                                    autocomplete="current-password" 
                                    placeholder="••••••••" />
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                                    title="Toggle password visibility">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center mt-6">
                            <input id="remember_me" type="checkbox"
                                class="rounded dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:focus:ring-offset-gray-900 cursor-pointer"
                                name="remember">
                            <label for="remember_me" class="ms-3 text-sm text-gray-600 dark:text-gray-400 cursor-pointer hover:text-gray-900 dark:hover:text-gray-300 transition-colors">
                                {{ __('Keep me signed in') }}
                            </label>
                        </div>

                        <!-- Sign In Button -->
                        <div class="mt-8">
                            <x-primary-button class="w-full justify-center py-3 text-lg font-semibold rounded-xl transition-all hover:shadow-lg hover:shadow-emerald-500/30 dark:hover:shadow-emerald-500/20">
                                {{ __('Sign In') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">Don't have an account?</span>
                        </div>
                    </div>

                    <!-- Create Account CTA -->
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 px-4 rounded-xl border-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200">
                        {{ __('Create Account') }}
                    </a>

                    <!-- Trust Badge -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-800">
                        <p class="text-center text-xs text-gray-500 dark:text-gray-400">
                            🔒 Your data is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
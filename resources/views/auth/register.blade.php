<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Registration Container with Two Columns -->
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

                <!-- Right Side - Registration Form -->
                <div class="p-8 lg:p-12 flex flex-col justify-start lg:justify-center overflow-y-auto lg:max-h-screen">
                    <!-- Mobile Header (visible only on mobile) -->
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-600/20 dark:border-emerald-400/20">
                            <img src="{{ asset('images/foodlogo.png') }}" alt="CaterEase" class="h-9 w-9 object-contain dark:invert" />
                        </div>
                        <div class="text-left">
                            <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Create your account</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Join CaterEase</p>
                        </div>
                    </div>

                    <!-- Desktop Header (visible only on desktop) -->
                    <div class="hidden lg:block mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Create Account</h2>
                        <p class="text-gray-600 dark:text-gray-400">Join our community of successful caterers</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="{ role: '{{ old('role', 'customer') }}', showPassword: false, showPasswordConfirm: false }">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2">
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required
                                autofocus autocomplete="name" placeholder="John Doe" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-5 space-y-2">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required
                                autocomplete="username" placeholder="you@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-5 space-y-2">
                            <x-input-label for="password" :value="__('Password')" />
                            <div class="relative">
                                <input id="password" 
                                    :type="showPassword ? 'text' : 'password'" 
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" 
                                    name="password" 
                                    required
                                    autocomplete="new-password" 
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

                        <!-- Confirm Password -->
                        <div class="mt-5 space-y-2">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <div class="relative">
                                <input id="password_confirmation" 
                                    :type="showPasswordConfirm ? 'text' : 'password'" 
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="••••••••" />
                                <button 
                                    type="button"
                                    @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                                    title="Toggle password visibility">
                                    <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Role Selection -->
                        <div class="mt-5 space-y-2">
                            <x-input-label for="role" :value="__('Register As')" />
                            <select id="role" name="role" x-model="role"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                                <option value="customer">Customer - Find Catering Services</option>
                                <option value="caterer">Caterer - Offer Catering Services</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Caterer Additional Fields -->
                        <template x-if="role === 'caterer'">
                            <div class="mt-5 space-y-4 p-5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50">
                                <div class="rounded-lg bg-amber-100/60 dark:bg-amber-900/40 px-4 py-3 text-sm text-amber-800 dark:text-amber-200 border border-amber-200/50 dark:border-amber-800">
                                    <div class="flex gap-2">
                                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v1m0-18v1m0-1a9 9 0 100 18 9 9 0 000-18z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Admin Approval Required</p>
                                            <p class="text-xs mt-1">All caterer applications are reviewed and approved by our admin team before activation.</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="business_name" :value="__('Business Name')" />
                                    <x-text-input id="business_name" name="business_name" type="text" :value="old('business_name')"
                                        class="block mt-2 w-full" placeholder="Your Business Name" />
                                    <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="owner_full_name" :value="__('Owner Full Name')" />
                                    <x-text-input id="owner_full_name" name="owner_full_name" type="text"
                                        :value="old('owner_full_name')" class="block mt-2 w-full" placeholder="Full Name" />
                                    <x-input-error :messages="$errors->get('owner_full_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="business_address" :value="__('Business Address')" />
                                    <x-text-input id="business_address" name="business_address" type="text"
                                        :value="old('business_address')" class="block mt-2 w-full" placeholder="Full Address" />
                                    <x-input-error :messages="$errors->get('business_address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="business_permit_number"
                                        :value="__('Business Permit Number')" />
                                    <x-text-input id="business_permit_number" name="business_permit_number" type="text"
                                        :value="old('business_permit_number')" class="block mt-2 w-full" placeholder="From city/municipality" />
                                    <x-input-error :messages="$errors->get('business_permit_number')" class="mt-2" />
                                </div>

                                <!-- Business Permit Upload -->
                                <div>
                                    <x-input-label for="business_permit_file"
                                        :value="__('Upload Business Permit (PDF/JPG/PNG)')" class="dark:text-gray-300" />
                                    <input id="business_permit_file" name="business_permit_file" type="file"
                                        accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-600 dark:text-gray-400 mt-2
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-emerald-100 file:text-emerald-700 dark:file:bg-emerald-900/40 dark:file:text-emerald-300
                                hover:file:bg-emerald-200 dark:hover:file:bg-emerald-900/60
                                cursor-pointer" />
                                    <x-input-error :messages="$errors->get('business_permit_file')" class="mt-2" />
                                </div>
                            </div>
                        </template>

                        <!-- Terms and Conditions Checkbox -->
                        <div class="mt-6">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" 
                                       name="terms_accepted" 
                                       id="terms_accepted"
                                       value="1"
                                       {{ old('terms_accepted') ? 'checked' : '' }}
                                       required
                                       class="mt-1 rounded border-gray-300 dark:border-gray-600 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:bg-gray-800 dark:focus:ring-emerald-600 dark:focus:ring-offset-gray-900">
                                <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                                    I agree to the 
                                    <a href="{{ route('terms') }}" 
                                       target="_blank" 
                                       class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 underline font-medium">
                                        Terms and Conditions
                                    </a>
                                    <span class="text-red-600 dark:text-red-400">*</span>
                                </span>
                            </label>
                            <x-input-error :messages="$errors->get('terms_accepted')" class="mt-2" />
                        </div>

                        <!-- Register Button -->
                        <div class="mt-8">
                            <x-primary-button class="w-full justify-center py-3 text-lg font-semibold rounded-xl transition-all hover:shadow-lg hover:shadow-emerald-500/30 dark:hover:shadow-emerald-500/20">
                                {{ __('Create My Account') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">Already have an account?</span>
                        </div>
                    </div>

                    <!-- Sign In CTA -->
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 px-4 rounded-xl border-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200">
                        {{ __('Sign In Instead') }}
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
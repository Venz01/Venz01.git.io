<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Terms and Conditions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">
                    Terms and Conditions
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-4">
                    Please read these terms carefully. By using our platform, you agree to be bound by these terms.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Last Updated: January 29, 2026</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Version 1.0</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar - Table of Contents -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            Table of Contents
                        </h3>
                        <nav class="space-y-2">
                            <a href="#introduction" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">1. Introduction</a>
                            <a href="#acceptance" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">2. Acceptance of Terms</a>
                            <a href="#accounts" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">3. User Accounts</a>
                            <a href="#caterer" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">4. Caterer Terms</a>
                            <a href="#booking" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">5. Booking & Payment</a>
                            <a href="#reviews" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">6. Reviews & Ratings</a>
                            <a href="#prohibited" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">7. Prohibited Activities</a>
                            <a href="#liability" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">8. Liability</a>
                            <a href="#privacy" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">9. Privacy</a>
                            <a href="#ip" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">10. IP Rights</a>
                            <a href="#modifications" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">11. Modifications</a>
                            <a href="#termination" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">12. Termination</a>
                            <a href="#law" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">13. Governing Law</a>
                            <a href="#contact" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">14. Contact</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">

                    <!-- Introduction -->
                    <section id="introduction" class="scroll-smooth">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">1. Introduction</h2>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Welcome to our Catering Services Platform. By accessing or using our platform, you agree to be bound by these Terms and Conditions. 
                                    Please read them carefully before registering or using our services.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Acceptance of Terms -->
                    <section id="acceptance" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">2. Acceptance of Terms</h2>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    By creating an account and using our platform, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. 
                                    If you do not agree to these terms, you may not use our services.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- User Accounts -->
                    <section id="accounts" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. User Accounts</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">3.1 Registration</p>
                                        <p>You must provide accurate, current, and complete information during registration.</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-2">3.2 Account Types</p>
                                        <ul class="space-y-2">
                                            <li class="flex items-start gap-2">
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900 text-xs font-semibold text-blue-600 dark:text-blue-400 mt-0.5">1</span>
                                                <span><strong>Customers:</strong> Can browse, book, and review catering services</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900 text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">2</span>
                                                <span><strong>Caterers:</strong> Must provide valid business permits and wait for admin approval before listing services</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">3.3 Account Security</p>
                                        <p>You are responsible for maintaining the confidentiality of your account credentials.</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">3.4 Age Requirement</p>
                                        <p>You must be at least 18 years old to register and use this platform.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Caterer Terms -->
                    <section id="caterer" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-orange-100 dark:bg-orange-900/30">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Caterer-Specific Terms</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">4.1 Business Verification</p>
                                        <p>All caterers must provide valid business permits and documentation for verification.</p>
                                    </div>
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">4.2 Approval Process</p>
                                        <p>Your caterer account will be reviewed by our admin team. Approval typically takes 1-3 business days.</p>
                                    </div>
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">4.3 Service Quality</p>
                                        <p>Caterers must maintain high standards of food safety, hygiene, and customer service.</p>
                                    </div>
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">4.4 Accurate Information</p>
                                        <p>All menu items, prices, and service details must be accurate and up-to-date.</p>
                                    </div>
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">4.5 Platform Fees</p>
                                        <p>Commission rates and payment terms will be communicated separately upon approval.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Booking and Payment -->
                    <section id="booking" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Booking and Payment Terms</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">5.1 Booking Process</p>
                                        <p>Customers can book catering services through the platform by providing event details and making required payments.</p>
                                    </div>
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">5.2 Payment</p>
                                        <p>All payments must be made through the platform's secure payment system.</p>
                                    </div>
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">5.3 Cancellations</p>
                                        <p>Cancellation policies vary by caterer and will be clearly stated before booking confirmation.</p>
                                    </div>
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">5.4 Refunds</p>
                                        <p>Refund eligibility depends on the cancellation policy and timing. Please review carefully before booking.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Reviews and Ratings -->
                    <section id="reviews" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Reviews and Ratings</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">6.1 Honest Reviews</p>
                                        <p>Customers may leave honest reviews after their event is completed.</p>
                                    </div>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">6.2 Prohibited Content</p>
                                        <p>Reviews must not contain offensive language, personal attacks, or false information.</p>
                                    </div>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">6.3 Response Rights</p>
                                        <p>Caterers have the right to respond to reviews professionally.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Prohibited Activities -->
                    <section id="prohibited" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/30">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Prohibited Activities</h2>
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-6 border border-red-200 dark:border-red-800">
                                    <p class="text-gray-600 dark:text-gray-300 font-semibold mb-3">Users must not:</p>
                                    <ul class="space-y-2">
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Provide false or misleading information</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Engage in fraudulent activities</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Circumvent the platform to avoid fees</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Harass or abuse other users</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Violate any applicable laws or regulations</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                            <span>Post content that infringes intellectual property rights</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Liability -->
                    <section id="liability" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-pink-100 dark:bg-pink-900/30">
                                <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. Limitation of Liability</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4 border border-pink-200 dark:border-pink-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">8.1 Platform Role</p>
                                        <p>We act as a marketplace connecting customers with caterers. We are not responsible for the quality, safety, or legality of services provided by caterers.</p>
                                    </div>
                                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4 border border-pink-200 dark:border-pink-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">8.2 Disputes</p>
                                        <p>Any disputes between customers and caterers should be resolved directly between the parties. We may assist in mediation but are not liable for outcomes.</p>
                                    </div>
                                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4 border border-pink-200 dark:border-pink-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">8.3 Food Safety</p>
                                        <p>Caterers are solely responsible for food safety, hygiene, and compliance with health regulations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Privacy -->
                    <section id="privacy" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-cyan-100 dark:bg-cyan-900/30">
                                <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">9. Privacy and Data Protection</h2>
                                <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-lg p-4 border border-cyan-200 dark:border-cyan-800">
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                                        We are committed to protecting your personal information in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173). 
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                        Please review our Privacy Policy for detailed information about how we collect, use, and protect your data.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Intellectual Property -->
                    <section id="ip" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-teal-100 dark:bg-teal-900/30">
                                <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. Intellectual Property</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-4 border border-teal-200 dark:border-teal-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">10.1 Platform Content</p>
                                        <p>All content on the platform, including logos, text, and design, is owned by us and protected by intellectual property laws.</p>
                                    </div>
                                    <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-4 border border-teal-200 dark:border-teal-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">10.2 User Content</p>
                                        <p>By uploading content (photos, descriptions, reviews), you grant us a license to use, display, and distribute that content on the platform.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Modifications -->
                    <section id="modifications" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-lime-100 dark:bg-lime-900/30">
                                <svg class="w-6 h-6 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">11. Changes to Terms</h2>
                                <div class="bg-lime-50 dark:bg-lime-900/20 rounded-lg p-4 border border-lime-200 dark:border-lime-800">
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                        We reserve the right to modify these Terms and Conditions at any time. Users will be notified of significant changes via email or platform notification. 
                                        Continued use of the platform after changes constitutes acceptance of the new terms.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Termination -->
                    <section id="termination" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-violet-100 dark:bg-violet-900/30">
                                <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">12. Account Termination</h2>
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-4 border border-violet-200 dark:border-violet-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">12.1 By User</p>
                                        <p>You may terminate your account at any time by contacting support.</p>
                                    </div>
                                    <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-4 border border-violet-200 dark:border-violet-800">
                                        <p class="font-semibold text-gray-900 dark:text-white mb-1">12.2 By Platform</p>
                                        <p>We reserve the right to suspend or terminate accounts that violate these terms or engage in prohibited activities.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Governing Law -->
                    <section id="law" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-rose-100 dark:bg-rose-900/30">
                                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">13. Governing Law</h2>
                                <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4 border border-rose-200 dark:border-rose-800">
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                        These Terms and Conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines. 
                                        Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of the Philippines.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Contact -->
                    <section id="contact" class="pt-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">14. Contact Information</h2>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                                    If you have any questions about these Terms and Conditions, please contact us at:
                                </p>
                                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <a href="mailto:support@cateringplatform.com" class="text-amber-600 dark:text-amber-400 hover:underline">support@cateringplatform.com</a>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a9.008 9.008 0 100-2.468l-2.048 1.029a1 1 0 00-.502.756l-1.498 4.493a1 1 0 00-.948.684H5a2 2 0 01-2-2V5z"/>
                                            </svg>
                                            <span class="text-gray-600 dark:text-gray-300">+63 XXX XXX XXXX</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Acknowledgment -->
                    <section class="pt-8 border-t border-gray-200 dark:border-gray-700 mt-12">
                        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-900/20 dark:to-blue-900/20 border-l-4 border-emerald-600 dark:border-emerald-400 rounded-lg p-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        By checking the <strong>"I agree to the Terms and Conditions"</strong> box during registration, you acknowledge that you have read, 
                                        understood, and agree to be bound by these Terms and Conditions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 dark:from-emerald-700 dark:to-emerald-800 dark:hover:from-emerald-600 dark:hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Registration
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

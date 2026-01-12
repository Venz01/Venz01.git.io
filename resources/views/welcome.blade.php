<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CaterEase') }} - Find Your Perfect Caterer</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 w-full">
        
        <!-- Navigation - Fully Responsive -->
        <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-40 transition-all duration-300 w-full">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center transform hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="xxround" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">CaterEase</h1>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <a href="{{ route('login') }}" class="text-sm sm:text-base text-gray-700 hover:text-blue-600 px-3 sm:px-4 py-2 font-medium transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-sm sm:text-base rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold whitespace-nowrap">
                            Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section - Enhanced Responsiveness -->
        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-700 text-white py-16 sm:py-24 lg:py-32 overflow-hidden w-full">
            <!-- Animated background elements -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-10 left-10 w-32 h-32 sm:w-64 sm:h-64 bg-white rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-10 right-10 w-32 h-32 sm:w-64 sm:h-64 bg-purple-300 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            
            <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-block mb-4 sm:mb-6">
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-semibold tracking-wide uppercase">
                        🎉 Trusted by 1000+ Customers
                    </span>
                </div>
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 leading-tight animate-fade-in">
                    Find Your Perfect<br class="hidden sm:block" />
                    <span class="bg-gradient-to-r from-yellow-200 to-pink-200 bg-clip-text text-transparent">Caterer</span>
                </h2>
                <p class="text-base sm:text-xl lg:text-2xl mb-8 sm:mb-10 px-4 text-blue-100 max-w-3xl mx-auto">
                    Browse verified caterers, explore delicious menus, and book effortlessly for your special event
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 sm:py-4 bg-white text-blue-600 text-base sm:text-lg rounded-xl font-bold hover:bg-gray-100 transform hover:scale-105 transition-all duration-200 shadow-2xl">
                        Get Started Free
                    </a>
                    <button onclick="document.getElementById('caterers-section').scrollIntoView({behavior: 'smooth'})" class="w-full sm:w-auto px-8 py-3 sm:py-4 border-2 border-white text-white text-base sm:text-lg rounded-xl font-bold hover:bg-white/10 transition-all duration-200">
                        Browse Caterers
                    </button>
                </div>
                
                <!-- Stats -->
                <div class="mt-12 sm:mt-16 grid grid-cols-3 gap-4 sm:gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-2xl sm:text-4xl font-bold mb-1">500+</div>
                        <div class="text-xs sm:text-sm text-blue-200">Caterers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-4xl font-bold mb-1">10k+</div>
                        <div class="text-xs sm:text-sm text-blue-200">Events</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-4xl font-bold mb-1">4.9★</div>
                        <div class="text-xs sm:text-sm text-blue-200">Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Caterers List - Enhanced Responsive Grid -->
        <div id="caterers-section" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
            <div class="text-center mb-10 sm:mb-12">
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                    Discover Amazing Caterers
                </h3>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Handpicked professionals ready to make your event unforgettable
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($caterers as $caterer)
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                        <div class="relative overflow-hidden">
                            @if($caterer->profile_image)
                                <img src="{{ asset('storage/' . $caterer->profile_image) }}" 
                                     alt="{{ $caterer->business_name }}" 
                                     class="w-full h-48 sm:h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-48 sm:h-56 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 sm:w-20 sm:h-20 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs sm:text-sm font-bold text-blue-600">
                                ⚡ Available
                            </div>
                        </div>
                        
                        <div class="p-5 sm:p-6">
                            <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $caterer->business_name }}
                            </h4>
                            <p class="text-sm sm:text-base text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                                {{ $caterer->bio ?? 'Professional catering service for all occasions' }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                                <div class="flex items-center space-x-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < floor($caterer->average_rating ?? 5.0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm sm:text-base text-gray-700 font-bold">{{ number_format($caterer->average_rating ?? 5.0, 1) }}</span>
                                </div>
                                <span class="text-gray-500 text-xs sm:text-sm font-medium">({{ $caterer->reviews_count ?? 0 }} reviews)</span>
                            </div>

                            <button onclick="showLoginModal('{{ $caterer->business_name }}')" 
                                    class="w-full px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-sm sm:text-base rounded-xl hover:shadow-lg transform group-hover:scale-105 transition-all duration-200 font-semibold flex items-center justify-center space-x-2">
                                <span>View Details</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-base sm:text-lg">No caterers available at the moment.</p>
                        <p class="text-gray-400 text-sm mt-2">Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($caterers->hasPages())
                <div class="mt-10 sm:mt-12">
                    {{ $caterers->links() }}
                </div>
            @endif
        </div>

        <!-- Features Section with icons -->
        <div class="bg-white py-16 sm:py-20 lg:py-24 w-full">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 sm:mb-16">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        How It Works
                    </h3>
                    <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                        Simple steps to book your perfect catering experience
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    <div class="relative text-center group">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-6 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                            1
                        </div>
                        <div class="pt-8 pb-6 px-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-2">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center mx-auto mb-5 transform group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Browse Caterers</h4>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Explore our verified caterers with detailed menus and customer reviews</p>
                        </div>
                    </div>
                    
                    <div class="relative text-center group">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-6 w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-lg">
                            2
                        </div>
                        <div class="pt-8 pb-6 px-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-2">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-5 transform group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Book a Date</h4>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Select your event date, choose a package, and customize your menu</p>
                        </div>
                    </div>
                    
                    <div class="relative text-center group">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-6 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg">
                            3
                        </div>
                        <div class="pt-8 pb-6 px-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-2">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-purple-600 to-pink-700 rounded-2xl flex items-center justify-center mx-auto mb-5 transform group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Enjoy Your Event</h4>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Sit back, relax, and enjoy your perfectly catered event</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 sm:py-20 w-full">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-4">
                    Ready to Make Your Event Special?
                </h3>
                <p class="text-base sm:text-xl text-blue-100 mb-8">
                    Join thousands of happy customers who trust CaterEase
                </p>
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-blue-600 text-base sm:text-lg rounded-xl font-bold hover:bg-gray-100 transform hover:scale-105 transition-all duration-200 shadow-2xl">
                    Start Booking Now
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-10 sm:py-12 w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">CaterEase</span>
                    </div>
                    <p class="text-sm sm:text-base text-gray-400">&copy; {{ date('Y') }} CaterEase. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>

    <!-- Enhanced Login Modal -->
    <div id="loginModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 transform scale-95 hover:scale-100 transition-transform">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Login Required</h3>
                    <p class="text-sm text-gray-500">Access premium features</p>
                </div>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <p class="text-center text-gray-600 text-sm sm:text-base leading-relaxed" id="loginModalMessage">
                    You need to login first to view caterer details and make bookings.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('login') }}" class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-sm sm:text-base rounded-xl hover:shadow-lg text-center font-semibold transform hover:scale-105 transition-all">
                    Login Now
                </a>
                <a href="{{ route('register') }}" class="flex-1 px-5 py-3 border-2 border-blue-600 text-blue-600 text-sm sm:text-base rounded-xl hover:bg-blue-50 text-center font-semibold transition-all">
                    Create Account
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    </style>

    <script>
        function showLoginModal(catererName) {
            const modal = document.getElementById('loginModal');
            const message = document.getElementById('loginModalMessage');
            
            if (catererName) {
                message.textContent = `You need to login first to view details for ${catererName} and make bookings.`;
            }
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.opacity = '1';
                document.body.style.overflow = '';
            }, 200);
        }

        // Close modal when clicking outside
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
            }
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
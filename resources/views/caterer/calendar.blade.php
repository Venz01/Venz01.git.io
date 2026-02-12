<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    📅 Calendar & Availability
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Manage your bookings, orders, and availability in one place
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="toggleBlockModal()" 
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Block Date
                </button>
                <button onclick="toggleRangeModal()" 
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage Ranges
                </button>
                <button onclick="confirmClearAll()" 
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear All Blocks
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats Cards - Responsive Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Total Events Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-4 sm:p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-indigo-100 mb-1">Total Events</p>
                            <p class="text-2xl sm:text-3xl font-bold text-white">
                                {{ \App\Models\Booking::where('caterer_id', auth()->id())->count() + \App\Models\Order::where('caterer_id', auth()->id())->count() }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Bookings Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 sm:p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-blue-100 mb-1">🎉 Bookings</p>
                            <p class="text-2xl sm:text-3xl font-bold text-white">
                                {{ \App\Models\Booking::where('caterer_id', auth()->id())->count() }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 sm:p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-green-100 mb-1">📦 Orders</p>
                            <p class="text-2xl sm:text-3xl font-bold text-white">
                                {{ \App\Models\Order::where('caterer_id', auth()->id())->count() }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- This Month Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 sm:p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-purple-100 mb-1">This Month</p>
                            <p class="text-2xl sm:text-3xl font-bold text-white">
                                {{ \App\Models\Booking::where('caterer_id', auth()->id())
                                    ->whereMonth('event_date', now()->month)
                                    ->whereYear('event_date', now()->year)
                                    ->count() + 
                                   \App\Models\Order::where('caterer_id', auth()->id())
                                    ->whereMonth('fulfillment_date', now()->month)
                                    ->whereYear('fulfillment_date', now()->year)
                                    ->count() 
                                }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Blocked Days Card -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 sm:p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 col-span-2 sm:col-span-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-red-100 mb-1">Blocked Days</p>
                            <p class="text-2xl sm:text-3xl font-bold text-white">
                                {{ \App\Models\CatererAvailability::where('caterer_id', auth()->id())->where('status', 'blocked')->count() }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">
                <!-- Calendar Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Your Schedule
                            </h3>
                            <p class="text-indigo-100 text-sm mt-1">View and manage all your bookings, orders, and blocked dates</p>
                        </div>
                    </div>
                </div>

                <!-- Calendar Body -->
                <div class="p-4 sm:p-6">
                    <div id='calendar' class="dark:text-gray-100"></div>
                </div>
            </div>

            <!-- Legend Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Color Legend
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-yellow-500"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-blue-500"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Confirmed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-green-500"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Completed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-500"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Cancelled</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-purple-500"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Orders</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-600"></div>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Blocked (click to unblock)</span>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 sm:p-6 border border-blue-200 dark:border-gray-600">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-2">Quick Tips</h4>
                        <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 mt-0.5">•</span>
                                <span><strong>Click any event</strong> on the calendar to view full details</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 mt-0.5">•</span>
                                <span><strong>Click on blocked dates</strong> (red background) to quickly unblock them</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 mt-0.5">•</span>
                                <span>Use <strong>"Manage Ranges"</strong> to block or unblock multiple dates at once</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 mt-0.5">•</span>
                                <span>Use <strong>"Clear All Blocks"</strong> to remove all blocked dates instantly</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Single Date Modal -->
    <div id="blockDateModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 modal-content">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 p-5 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Block Date</h3>
                    </div>
                    <button onclick="toggleBlockModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-red-100 text-sm mt-2">Mark a date when you're unavailable for new bookings</p>
            </div>
            
            <!-- Modal Body -->
            <form id="blockDateForm" class="p-6">
                @csrf
                
                <div class="space-y-5">
                    <!-- Date Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Select Date to Block
                            </span>
                        </label>
                        <input type="date" 
                               name="date" 
                               id="blockDate" 
                               required
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100 transition-all duration-200">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            You can only block future dates
                        </p>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Reason (Optional)
                            </span>
                        </label>
                        <textarea name="notes" 
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100 transition-all duration-200 resize-none"
                                  placeholder="E.g., Personal day off, Holiday, Equipment maintenance..."></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Help you remember why this date is blocked
                        </p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Block This Date
                    </button>
                    <button type="button" 
                            onclick="toggleBlockModal()"
                            class="flex-1 px-5 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Date Ranges Modal -->
    <div id="rangeModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-5 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Manage Date Ranges</h3>
                    </div>
                    <button onclick="toggleRangeModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-purple-100 text-sm mt-2">Block or unblock multiple dates at once</p>
            </div>
            
            <!-- Modal Body with Tabs -->
            <div class="p-6">
                <!-- Tab Buttons -->
                <div class="flex gap-2 mb-6 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button onclick="switchRangeTab('block')" id="blockTab" class="flex-1 px-4 py-2 rounded-md font-medium transition-all duration-200 bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm">
                        Block Range
                    </button>
                    <button onclick="switchRangeTab('unblock')" id="unblockTab" class="flex-1 px-4 py-2 rounded-md font-medium transition-all duration-200 text-gray-600 dark:text-gray-300">
                        Unblock Range
                    </button>
                </div>

                <!-- Block Range Form -->
                <form id="blockRangeForm" action="{{ route('caterer.availability.block-range') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" 
                               name="start_date" 
                               required
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" 
                               name="end_date" 
                               required
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reason (Optional)</label>
                        <textarea name="notes" 
                                  rows="2"
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-gray-100 resize-none"
                                  placeholder="E.g., Holiday period, Renovation..."></textarea>
                    </div>
                    <button type="submit"
                            class="w-full px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Block Date Range
                    </button>
                </form>

                <!-- Unblock Range Form -->
                <form id="unblockRangeForm" action="{{ route('caterer.availability.unblock-range') }}" method="POST" class="space-y-4 hidden">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" 
                               name="start_date" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" 
                               name="end_date" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <button type="submit"
                            class="w-full px-5 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Unblock Date Range
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FullCalendar CSS & JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @media (max-width: 640px) {
            .fc .fc-toolbar {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
            }
            
            .fc .fc-toolbar-title {
                font-size: 1.25rem !important;
            }
            
            .fc-button {
                padding: 0.4rem 0.6rem !important;
                font-size: 0.875rem !important;
            }
        }

        .fc {
            background: transparent;
        }
        
        .fc-theme-standard td, 
        .fc-theme-standard th {
            border-color: #e5e7eb;
        }
        
        .dark .fc-theme-standard td,
        .dark .fc-theme-standard th {
            border-color: #374151;
        }
        
        .fc-day-today {
            background-color: #EFF6FF !important;
        }
        
        .dark .fc-day-today {
            background-color: #1E3A8A !important;
        }
        
        .fc-event {
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.25rem 0.5rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
        }
        
        .fc-event:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        .fc-button-primary {
            background-color: #4F46E5 !important;
            border-color: #4F46E5 !important;
        }
        
        .fc-button-primary:hover {
            background-color: #4338CA !important;
            border-color: #4338CA !important;
        }

        /* Make blocked dates more obvious with cursor */
        .fc-daygrid-day.blocked-date {
            cursor: pointer !important;
        }

        .fc-daygrid-day.blocked-date:hover {
            opacity: 0.8 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var events = @json($allEvents);
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: events,
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    
                    if (props.type === 'booking' || props.type === 'order') {
                        showEventDetails(info.event);
                    }
                },
                dateClick: function(info) {
                    // ✅ NEW: Click on blocked dates to unblock them
                    const clickedDate = info.dateStr;
                    const blockedEvent = events.find(e => 
                        e.type === 'blocked' && e.start === clickedDate
                    );
                    
                    if (blockedEvent) {
                        confirmUnblockDate(clickedDate);
                    }
                },
                dayCellDidMount: function(info) {
                    // Add visual indicator for blocked dates
                    const dateStr = info.date.toISOString().split('T')[0];
                    const isBlocked = events.some(e => 
                        e.type === 'blocked' && e.start === dateStr
                    );
                    
                    if (isBlocked) {
                        info.el.classList.add('blocked-date');
                        info.el.style.cursor = 'pointer';
                        info.el.title = 'Click to unblock this date';
                    }
                },
                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 1.8
            });
            
            calendar.render();
        });

        // ✅ NEW: Confirm and unblock a date via AJAX
        function confirmUnblockDate(date) {
            Swal.fire({
                title: 'Unblock Date?',
                html: `Do you want to unblock <strong>${new Date(date).toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                })}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Unblock It',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    unblockDate(date);
                }
            });
        }

        // ✅ NEW: Unblock date via AJAX
        function unblockDate(date) {
            fetch('{{ route("caterer.availability.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    date: date,
                    action: 'unblock'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Date Unblocked!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
            });
        }

        // Block single date form submission via AJAX
        document.getElementById('blockDateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const date = formData.get('date');
            const notes = formData.get('notes');
            
            fetch('{{ route("caterer.availability.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    date: date,
                    action: 'block',
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toggleBlockModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Date Blocked!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
            });
        });

        // ✅ NEW: Clear all blocked dates
        function confirmClearAll() {
            Swal.fire({
                title: 'Clear All Blocked Dates?',
                text: 'This will remove all your currently blocked dates. This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Clear All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("caterer.availability.clear-all") }}';
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function toggleBlockModal() {
            const modal = document.getElementById('blockDateModal');
            modal.classList.toggle('hidden');
            
            if (modal.classList.contains('hidden')) {
                document.getElementById('blockDate').value = '';
                document.querySelector('#blockDateForm textarea[name="notes"]').value = '';
            }
        }

        function toggleRangeModal() {
            const modal = document.getElementById('rangeModal');
            modal.classList.toggle('hidden');
        }

        // ✅ NEW: Switch between block and unblock tabs in range modal
        function switchRangeTab(tab) {
            const blockTab = document.getElementById('blockTab');
            const unblockTab = document.getElementById('unblockTab');
            const blockForm = document.getElementById('blockRangeForm');
            const unblockForm = document.getElementById('unblockRangeForm');

            if (tab === 'block') {
                blockTab.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                blockTab.classList.remove('text-gray-600', 'dark:text-gray-300');
                unblockTab.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                unblockTab.classList.add('text-gray-600', 'dark:text-gray-300');
                
                blockForm.classList.remove('hidden');
                unblockForm.classList.add('hidden');
            } else {
                unblockTab.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                unblockTab.classList.remove('text-gray-600', 'dark:text-gray-300');
                blockTab.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                blockTab.classList.add('text-gray-600', 'dark:text-gray-300');
                
                unblockForm.classList.remove('hidden');
                blockForm.classList.add('hidden');
            }
        }

        // Close modals when clicking outside
        document.getElementById('blockDateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleBlockModal();
            }
        });

        document.getElementById('rangeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleRangeModal();
            }
        });

        function showEventDetails(event) {
            const props = event.extendedProps;
            let details = '';
            
            if (props.type === 'booking') {
                details = `
                    <div class="text-left space-y-3 p-2">
                        <div class="flex items-center gap-3 pb-3 border-b">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <span class="text-3xl">🎉</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Catering Booking</h4>
                                <p class="text-sm text-gray-500">${event.title}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Customer:</strong> ${props.customer_name}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Guests:</strong> ${props.guests}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Venue:</strong> ${props.venue}</span>
                            </div>
                            <div class="flex items-center gap-2 pt-2">
                                <span class="text-gray-700"><strong>Status:</strong></span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${getStatusClass(props.status)}">${props.status.toUpperCase()}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-700"><strong>Payment:</strong></span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${getPaymentStatusClass(props.payment_status)}">${props.payment_status.replace('_', ' ').toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t">
                            <a href="/caterer/bookings/${props.booking_id}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
                                View Full Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                `;
            } else if (props.type === 'order') {
                details = `
                    <div class="text-left space-y-3 p-2">
                        <div class="flex items-center gap-3 pb-3 border-b">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <span class="text-3xl">📦</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Menu Order</h4>
                                <p class="text-sm text-gray-500">${event.title}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Customer:</strong> ${props.customer_name}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <span class="text-gray-700"><strong>Type:</strong> ${props.fulfillment_type.toUpperCase()}</span>
                            </div>
                            ${props.fulfillment_time ? `
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Time:</strong> ${props.fulfillment_time}</span>
                            </div>
                            ` : ''}
                            <div class="flex items-center gap-2 pt-2">
                                <span class="text-gray-700"><strong>Status:</strong></span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${getOrderStatusClass(props.status)}">${props.status.toUpperCase()}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-700"><strong>Payment:</strong></span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${getPaymentStatusClass(props.payment_status)}">${props.payment_status.replace('_', ' ').toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t">
                            <a href="/caterer/orders/${props.order_id}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
                                View Full Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                `;
            }
            
            Swal.fire({
                html: details,
                showCloseButton: true,
                showConfirmButton: false,
                width: '600px',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }

        function getStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                'confirmed': 'bg-blue-100 text-blue-800 border border-blue-300',
                'completed': 'bg-green-100 text-green-800 border border-green-300',
                'cancelled': 'bg-red-100 text-red-800 border border-red-300'
            };
            return classes[status] || 'bg-gray-100 text-gray-800 border border-gray-300';
        }

        function getOrderStatusClass(status) {
            const classes = {
                'pending': 'bg-amber-100 text-amber-800 border border-amber-300',
                'confirmed': 'bg-indigo-100 text-indigo-800 border border-indigo-300',
                'preparing': 'bg-purple-100 text-purple-800 border border-purple-300',
                'ready': 'bg-teal-100 text-teal-800 border border-teal-300',
                'completed': 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                'cancelled': 'bg-red-100 text-red-800 border border-red-300'
            };
            return classes[status] || 'bg-gray-100 text-gray-800 border border-gray-300';
        }

        function getPaymentStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                'deposit_paid': 'bg-blue-100 text-blue-800 border border-blue-300',
                'paid': 'bg-green-100 text-green-800 border border-green-300',
                'fully_paid': 'bg-green-100 text-green-800 border border-green-300'
            };
            return classes[status] || 'bg-gray-100 text-gray-800 border border-gray-300';
        }
    </script>
</x-app-layout>
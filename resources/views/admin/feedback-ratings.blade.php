<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Feedback & Ratings Management') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Total Reviews -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-blue-500">
                    <div class="flex flex-col">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Reviews</p>
                        <p class="text-2xl md:text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Avg: {{ $stats['average_rating'] }} ⭐
                        </p>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-green-500">
                    <div class="flex flex-col">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Approved</p>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Live reviews</p>
                    </div>
                </div>

                <!-- Flagged -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-red-500">
                    <div class="flex flex-col">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Flagged</p>
                        <p class="text-2xl md:text-3xl font-bold text-red-600">{{ $stats['flagged'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Requires review</p>
                    </div>
                </div>

                <!-- Under Review -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-yellow-500">
                    <div class="flex flex-col">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Under Review</p>
                        <p class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $stats['under_review'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Being reviewed</p>
                    </div>
                </div>

                <!-- Removed -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-gray-500">
                    <div class="flex flex-col">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Removed</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-600">{{ $stats['removed'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Hidden from public</p>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-800 dark:text-orange-300 font-medium">Needs Attention</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['needs_attention'] }}</p>
                        </div>
                        <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-800 dark:text-purple-300 font-medium">Low Rated (1-2 ⭐)</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['low_rated'] }}</p>
                        </div>
                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-800 dark:text-red-300 font-medium">Caterers Warned</p>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['caterers_warned'] }}</p>
                        </div>
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg mb-6">
                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <nav class="flex -mb-px min-w-max md:min-w-0">
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'all'] + request()->except('status')) }}"
                           class="tab-button {{ (!request('status') || request('status') == 'all') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            All Reviews
                        </a>
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'needs_attention'] + request()->except('status')) }}"
                           class="tab-button {{ request('status') == 'needs_attention' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Needs Attention
                            @if($stats['needs_attention'] > 0)
                                <span class="ml-1 bg-orange-500 text-white rounded-full px-2 py-0.5 text-xs">{{ $stats['needs_attention'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'approved'] + request()->except('status')) }}"
                           class="tab-button {{ request('status') == 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Approved
                        </a>
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'flagged'] + request()->except('status')) }}"
                           class="tab-button {{ request('status') == 'flagged' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Flagged
                        </a>
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'under_review'] + request()->except('status')) }}"
                           class="tab-button {{ request('status') == 'under_review' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Under Review
                        </a>
                        <a href="{{ route('admin.feedback-ratings', ['status' => 'removed'] + request()->except('status')) }}"
                           class="tab-button {{ request('status') == 'removed' ? 'border-gray-500 text-gray-600' : 'border-transparent text-gray-500' }} px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium border-b-2 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Removed
                        </a>
                    </nav>
                </div>

                <!-- Filters -->
                <div class="p-4 md:p-6">
                    <form method="GET" action="{{ route('admin.feedback-ratings') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Review content, customer, caterer..."
                                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <select 
                                name="rating"
                                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                                <option value="all">All Ratings</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 ⭐⭐⭐⭐⭐</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 ⭐⭐⭐⭐</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 ⭐⭐⭐</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 ⭐⭐</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 ⭐</option>
                            </select>
                        </div>

                        <!-- Caterer Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Caterer</label>
                            <select 
                                name="caterer"
                                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                                <option value="">All Caterers</option>
                                @foreach($caterers as $caterer)
                                    <option value="{{ $caterer->id }}" {{ request('caterer') == $caterer->id ? 'selected' : '' }}>
                                        {{ $caterer->business_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm md:text-base rounded-lg hover:bg-blue-700 transition-colors">
                                Apply
                            </button>
                            <a href="{{ route('admin.feedback-ratings') }}" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 text-sm md:text-base rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
                                Clear
                            </a>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    <div id="bulkActionsBar" class="hidden mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                <span id="selectedCount">0</span> reviews selected
                            </span>
                            <div class="flex flex-wrap gap-2">
                                <button onclick="bulkAction('approve')" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                    Approve
                                </button>
                                <button onclick="bulkAction('flag')" class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                    Flag
                                </button>
                                <button onclick="bulkAction('remove')" class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
                                    Remove
                                </button>
                                <button onclick="clearSelection()" class="px-3 py-1.5 border border-gray-300 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Clear Selection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            @if($reviews->count() > 0)
                <div class="space-y-4 md:space-y-6">
                    @foreach($reviews as $review)
                        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <!-- Header -->
                            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <!-- Checkbox for bulk selection -->
                                        <input type="checkbox" 
                                               class="review-checkbox mt-1 h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                               data-review-id="{{ $review->id }}"
                                               onchange="updateBulkSelection()">
                                        
                                        <div class="flex-1">
                                            <!-- Rating -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-4 h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->rating }}.0</span>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium
                                                    @if($review->admin_status == 'approved') bg-green-100 text-green-800
                                                    @elseif($review->admin_status == 'flagged') bg-red-100 text-red-800
                                                    @elseif($review->admin_status == 'under_review') bg-yellow-100 text-yellow-800
                                                    @elseif($review->admin_status == 'removed') bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $review->status_text }}
                                                </span>

                                                @if($review->caterer_warned)
                                                    <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ⚠️ Caterer Warned
                                                    </span>
                                                @endif

                                                @if($review->hasResponse())
                                                    <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        💬 Has Response
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.feedback-ratings.show', $review->id) }}" 
                                           class="px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs md:text-sm font-medium">
                                            View Details
                                        </a>

                                        @if($review->admin_status !== 'approved')
                                            <button onclick="approveReview({{ $review->id }})" 
                                                    class="px-3 md:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs md:text-sm font-medium">
                                                Approve
                                            </button>
                                        @endif

                                        @if($review->admin_status !== 'flagged')
                                            <button onclick="flagReview({{ $review->id }})" 
                                                    class="px-3 md:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-xs md:text-sm font-medium">
                                                Flag
                                            </button>
                                        @endif

                                        @if($review->admin_status === 'removed')
                                            <button onclick="restoreReview({{ $review->id }})" 
                                                    class="px-3 md:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-xs md:text-sm font-medium">
                                                Restore
                                            </button>
                                        @else
                                            <button onclick="removeReview({{ $review->id }})" 
                                                    class="px-3 md:px-4 py-2 border border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-xs md:text-sm font-medium">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Review Content -->
                            <div class="p-4 md:p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                                    <!-- Customer & Caterer Info -->
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Customer</h4>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->customer->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->customer->email }}</p>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Caterer</h4>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->caterer->business_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->caterer->email }}</p>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Booking</h4>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->booking->booking_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->booking->event_date->format('M d, Y') }}</p>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Review Date</h4>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                    <!-- Review Comment -->
                                    <div class="lg:col-span-2 space-y-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Review Comment</h4>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                <p class="text-sm text-gray-900 dark:text-white">{{ $review->comment }}</p>
                                            </div>
                                        </div>

                                        @if($review->hasResponse())
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Caterer's Response</h4>
                                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $review->caterer_response }}</p>
                                                    <p class="text-xs text-gray-500 mt-2">Responded: {{ $review->responded_at->format('M d, Y h:i A') }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($review->flagged_reason)
                                            <div>
                                                <h4 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Flag/Removal Reason</h4>
                                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $review->flagged_reason }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($review->admin_notes)
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Admin Notes</h4>
                                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $review->admin_notes }}</p>
                                                    @if($review->reviewer)
                                                        <p class="text-xs text-gray-500 mt-2">By: {{ $review->reviewer->name }} on {{ $review->admin_reviewed_at->format('M d, Y') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 md:mt-8">
                    {{ $reviews->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-lg p-8 md:p-12 text-center">
                    <svg class="w-16 h-16 md:w-24 md:h-24 mx-auto mb-4 md:mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white mb-3 md:mb-4">No reviews found</h3>
                    <p class="text-sm md:text-base text-gray-600 dark:text-gray-400">
                        No reviews match your current filter criteria.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Approve Review</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                This review will be made visible to the public.
            </p>
            <form id="approveForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Admin Notes (optional)
                    </label>
                    <textarea 
                        name="admin_notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Add any notes about this approval..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Approve Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flag Modal -->
    <div id="flagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Flag Review as Inappropriate</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                This review will be hidden from public view and marked for review.
            </p>
            <form id="flagForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Flagging <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="flagged_reason"
                        rows="3"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Explain why this review is inappropriate..."
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Admin Notes (optional)
                    </label>
                    <textarea 
                        name="admin_notes"
                        rows="2"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Internal notes..."
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="warn_caterer" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send warning notification to caterer</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeFlagModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Flag Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Remove Modal -->
    <div id="removeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Remove Review</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                This review will be permanently hidden from public view.
            </p>
            <form id="removeForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Removal <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="removal_reason"
                        rows="3"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Explain why this review is being removed..."
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Admin Notes (optional)
                    </label>
                    <textarea 
                        name="admin_notes"
                        rows="2"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Internal notes..."
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="warn_caterer" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send warning notification to caterer</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRemoveModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Remove Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Restore Modal -->
    <div id="restoreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Restore Review</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                This review will be restored and made visible to the public.
            </p>
            <form id="restoreForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Admin Notes (optional)
                    </label>
                    <textarea 
                        name="admin_notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Add any notes about this restoration..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRestoreModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Restore Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Action Reason Modal -->
    <div id="bulkReasonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4" id="bulkModalTitle">Bulk Action</h3>
            <form id="bulkReasonForm" method="POST" action="{{ route('admin.feedback-ratings.bulk-action') }}">
                @csrf
                <input type="hidden" name="action" id="bulkAction">
                <input type="hidden" name="review_ids" id="bulkReviewIds">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="reason"
                        rows="3"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Provide a reason for this action..."
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeBulkReasonModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedReviews = new Set();

        // Update bulk selection
        function updateBulkSelection() {
            selectedReviews.clear();
            document.querySelectorAll('.review-checkbox:checked').forEach(checkbox => {
                selectedReviews.add(checkbox.dataset.reviewId);
            });

            const bulkBar = document.getElementById('bulkActionsBar');
            const countSpan = document.getElementById('selectedCount');
            
            if (selectedReviews.size > 0) {
                bulkBar.classList.remove('hidden');
                countSpan.textContent = selectedReviews.size;
            } else {
                bulkBar.classList.add('hidden');
            }
        }

        // Clear selection
        function clearSelection() {
            document.querySelectorAll('.review-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkSelection();
        }

        // Bulk action
        function bulkAction(action) {
            if (selectedReviews.size === 0) {
                alert('Please select at least one review.');
                return;
            }

            if (action === 'approve') {
                if (confirm(`Are you sure you want to approve ${selectedReviews.size} reviews?`)) {
                    document.getElementById('bulkAction').value = action;
                    document.getElementById('bulkReviewIds').value = JSON.stringify([...selectedReviews]);
                    document.getElementById('bulkReasonForm').submit();
                }
            } else {
                document.getElementById('bulkModalTitle').textContent = `Bulk ${action.charAt(0).toUpperCase() + action.slice(1)}`;
                document.getElementById('bulkAction').value = action;
                document.getElementById('bulkReviewIds').value = JSON.stringify([...selectedReviews]);
                document.getElementById('bulkReasonModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeBulkReasonModal() {
            document.getElementById('bulkReasonModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Approve review
        function approveReview(reviewId) {
            const form = document.getElementById('approveForm');
            form.action = `/admin/feedback-ratings/${reviewId}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Flag review
        function flagReview(reviewId) {
            const form = document.getElementById('flagForm');
            form.action = `/admin/feedback-ratings/${reviewId}/flag`;
            document.getElementById('flagModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFlagModal() {
            document.getElementById('flagModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Remove review
        function removeReview(reviewId) {
            const form = document.getElementById('removeForm');
            form.action = `/admin/feedback-ratings/${reviewId}/remove`;
            document.getElementById('removeModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRemoveModal() {
            document.getElementById('removeModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Restore review
        function restoreReview(reviewId) {
            const form = document.getElementById('restoreForm');
            form.action = `/admin/feedback-ratings/${reviewId}/restore`;
            document.getElementById('restoreModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRestoreModal() {
            document.getElementById('restoreModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals on outside click
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Review Details') }}
            </h2>
            <a href="{{ route('admin.feedback-ratings') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                ← Back to Reviews
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Review Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Review #{{ $review->id }}</h3>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $review->rating }}.0</span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($review->admin_status == 'approved') bg-green-100 text-green-800
                            @elseif($review->admin_status == 'flagged') bg-red-100 text-red-800
                            @elseif($review->admin_status == 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($review->admin_status == 'removed') bg-gray-100 text-gray-800
                            @endif">
                            {{ $review->status_text }}
                        </span>

                        @if($review->caterer_warned)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                ⚠️ Caterer Warned
                            </span>
                        @endif

                        @if($review->hasResponse())
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                💬 Has Response
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                    @if($review->admin_status !== 'approved')
                        <button onclick="approveReview({{ $review->id }})" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            ✓ Approve Review
                        </button>
                    @endif

                    @if($review->admin_status !== 'flagged')
                        <button onclick="flagReview({{ $review->id }})" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            ⚠ Flag Review
                        </button>
                    @endif

                    @if($review->admin_status === 'removed')
                        <button onclick="restoreReview({{ $review->id }})" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            ↻ Restore Review
                        </button>
                    @else
                        <button onclick="removeReview({{ $review->id }})" 
                                class="px-4 py-2 border-2 border-red-600 text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-medium">
                            ✕ Remove Review
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Info Cards -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer
                        </h4>
                        <div class="space-y-2">
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $review->customer->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->customer->email }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->customer->phone }}</p>
                        </div>
                    </div>

                    <!-- Caterer Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Caterer
                        </h4>
                        <div class="space-y-2">
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $review->caterer->business_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->caterer->email }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->caterer->phone }}</p>
                        </div>
                        
                        <!-- Caterer Stats -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Reviews</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $catererStats['total_reviews'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Avg Rating</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $catererStats['average_rating'] }} ⭐</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Flagged</p>
                                    <p class="text-lg font-bold text-red-600">{{ $catererStats['flagged_count'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Warnings</p>
                                    <p class="text-lg font-bold text-orange-600">{{ $catererStats['warnings_count'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Booking Details
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Booking Number</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $review->booking->booking_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Event Date</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $review->booking->event_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Package</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $review->booking->package->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Timeline
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Review Created</p>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            @if($review->responded_at)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Caterer Responded</p>
                                        <p class="text-xs text-gray-500">{{ $review->responded_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($review->admin_reviewed_at)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Admin Reviewed</p>
                                        <p class="text-xs text-gray-500">{{ $review->admin_reviewed_at->format('M d, Y h:i A') }}</p>
                                        @if($review->reviewer)
                                            <p class="text-xs text-gray-400">By: {{ $review->reviewer->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($review->caterer_warned_at)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Caterer Warned</p>
                                        <p class="text-xs text-gray-500">{{ $review->caterer_warned_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Review Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Review -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Review</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <p class="text-gray-900 dark:text-white leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    </div>

                    <!-- Caterer Response -->
                    @if($review->hasResponse())
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Caterer's Response</h4>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border-l-4 border-blue-500">
                                <p class="text-gray-900 dark:text-white leading-relaxed mb-3">{{ $review->caterer_response }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Responded on {{ $review->responded_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Caterer's Response</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                                <p class="text-gray-500 dark:text-gray-400">No response from caterer yet</p>
                            </div>
                        </div>
                    @endif

                    <!-- Admin Actions/Notes -->
                    @if($review->flagged_reason || $review->admin_notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admin Information</h4>
                            
                            @if($review->flagged_reason)
                                <div class="mb-4">
                                    <h5 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Flag/Removal Reason</h5>
                                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border-l-4 border-red-500">
                                        <p class="text-gray-900 dark:text-white">{{ $review->flagged_reason }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($review->admin_notes)
                                <div>
                                    <h5 class="text-sm font-semibold text-purple-700 dark:text-purple-400 mb-2">Admin Notes</h5>
                                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border-l-4 border-purple-500">
                                        <p class="text-gray-900 dark:text-white">{{ $review->admin_notes }}</p>
                                        @if($review->reviewer)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                                                By: {{ $review->reviewer->name }} on {{ $review->admin_reviewed_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Caterer's Recent Reviews -->
                    @if($catererReviews->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Reviews for This Caterer</h4>
                            <div class="space-y-4">
                                @foreach($catererReviews as $relatedReview)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $relatedReview->rating)
                                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $relatedReview->rating }}.0</span>
                                            </div>
                                            <span class="text-xs px-2 py-1 rounded-full
                                                @if($relatedReview->admin_status == 'approved') bg-green-100 text-green-800
                                                @elseif($relatedReview->admin_status == 'flagged') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $relatedReview->status_text }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $relatedReview->comment }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <p class="text-xs text-gray-500">{{ $relatedReview->customer->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $relatedReview->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include the same modals from feedback-ratings.blade.php -->
    @include('admin.partials.review-modals')

    <script>
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
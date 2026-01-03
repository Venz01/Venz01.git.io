<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Reviews & Ratings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
                {{-- Average Rating --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Average Rating</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($stats['average'], 1) }} / 5.0
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Reviews --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Reviews</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $stats['total'] }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5-Star Reviews --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">5-Star Reviews</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $stats['distribution'][5] }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Response --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Response</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $stats['pending_response'] }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rating Distribution --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Rating Distribution</h3>
                <div class="space-y-3">
                    @foreach([5, 4, 3, 2, 1] as $rating)
                        @php
                            $count = $stats['distribution'][$rating];
                            $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="w-20 flex items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $rating }}</span>
                                <span class="ml-1 text-yellow-400">★</span>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                                    <div class="bg-yellow-400 h-full transition-all duration-500" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-right">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Reviews List --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Customer Reviews</h3>
                </div>
                
                @if($reviews->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reviews as $review)
                        <div class="px-4 sm:px-6 py-6">
                            {{-- Review Header --}}
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                            <span class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                                                {{ substr($review->customer->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $review->customer->name }}
                                        </p>
                                        <div class="flex items-center mt-1">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $review->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Booking #{{ $review->booking->booking_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Review Comment --}}
                            <div class="mt-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $review->comment }}
                                </p>
                            </div>

                            {{-- Caterer Response Section --}}
                            @if($review->hasResponse())
                                <div class="mt-4 ml-8 sm:ml-12 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                Your Response
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $review->responded_at->format('M d, Y') }}
                                            </p>
                                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $review->caterer_response }}
                                            </p>
                                        </div>
                                        <div class="ml-4 flex space-x-2">
                                            <button type="button" 
                                                    onclick="openEditModal({{ $review->id }}, {{ json_encode($review->caterer_response) }})"
                                                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4">
                                    <button type="button"
                                            onclick="openResponseModal({{ $review->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Respond to this review
                                    </button>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($reviews->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900">
                        {{ $reviews->links() }}
                    </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No Reviews Yet</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">When customers complete their events, they can leave reviews here.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Response Modal --}}
    <div id="responseModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white dark:bg-gray-800 rounded-lg w-full sm:max-w-lg sm:my-8 shadow-xl">
                <form id="responseForm" method="POST" class="p-4 sm:p-6">
                    @csrf
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Respond to Review</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="response" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Your Response
                        </label>
                        <textarea id="response" 
                                  name="response" 
                                  rows="4" 
                                  class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                  placeholder="Thank the customer and address their feedback..."
                                  required></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 10 characters, maximum 1000 characters.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Post Response
                        </button>
                        <button type="button" 
                                onclick="closeModal()"
                                class="w-full sm:w-auto px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResponseModal(reviewId) {
            document.getElementById('responseForm').action = `/caterer/reviews/${reviewId}/respond`;
            document.getElementById('response').value = '';
            document.getElementById('responseModal').classList.remove('hidden');
        }
        
        function openEditModal(reviewId, currentResponse) {
            document.getElementById('responseForm').action = `/caterer/reviews/${reviewId}/update-response`;
            document.getElementById('response').value = currentResponse;
            document.getElementById('responseModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('responseModal').classList.add('hidden');
            document.getElementById('response').value = '';
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-app-layout>
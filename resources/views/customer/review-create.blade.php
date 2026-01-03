<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Write a Review') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Booking Information --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Booking Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Booking #:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->booking_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Caterer:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Event Date:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->event_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Package:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $booking->package->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Review Form --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Share Your Experience</h3>
                
                <form action="{{ route('customer.review.store', $booking->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- Rating --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Overall Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2" x-data="{ rating: {{ old('rating', 0) }} }">
                            <template x-for="star in 5" :key="star">
                                <button type="button" 
                                        @click="rating = star"
                                        class="text-4xl transition focus:outline-none"
                                        :class="star <= rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600 hover:text-yellow-400'">
                                    ★
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                            <span x-show="rating > 0" x-text="rating + ' star' + (rating > 1 ? 's' : '')" 
                                  class="ml-3 text-sm text-gray-600 dark:text-gray-400"></span>
                        </div>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Comment --}}
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Your Review <span class="text-red-500">*</span>
                        </label>
                        <textarea id="comment" 
                                  name="comment" 
                                  rows="6" 
                                  class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                  placeholder="Tell us about your experience with this caterer. What did you like? What could be improved?"
                                  required>{{ old('comment') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 10 characters, maximum 1000 characters.</p>
                        @error('comment')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror>
                    </div>

                    {{-- Helpful Tips --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Tips for writing a great review:</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1 list-disc list-inside">
                            <li>Be specific about what you liked or didn't like</li>
                            <li>Mention the quality of food, service, and presentation</li>
                            <li>Was the caterer responsive and professional?</li>
                            <li>Would you recommend them to others?</li>
                            <li>Keep it honest and constructive</li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            Submit Review
                        </button>
                        <a href="{{ route('customer.booking.details', $booking->id) }}" 
                           class="w-full sm:w-auto text-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            {{-- Privacy Notice --}}
            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
                <p>Your review will be publicly visible. Please be respectful and honest.</p>
                <p class="mt-1">By submitting, you agree that your review reflects your genuine experience.</p>
            </div>

        </div>
    </div>

    {{-- Alpine.js for star rating --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
</x-app-layout>
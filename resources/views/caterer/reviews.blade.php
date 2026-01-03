<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Reviews & Ratings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Reviews Coming Soon --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Reviews Feature Coming Soon</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Customer reviews and ratings will be available here once the feature is implemented.
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    For now, focus on delivering excellent service to build your reputation!
                </p>
            </div>

            {{-- Placeholder Stats --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5 text-center">
                        <div class="text-3xl font-bold text-gray-400">-</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Average Rating</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5 text-center">
                        <div class="text-3xl font-bold text-gray-400">-</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Total Reviews</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5 text-center">
                        <div class="text-3xl font-bold text-gray-400">-</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">5-Star Reviews</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5 text-center">
                        <div class="text-3xl font-bold text-gray-400">-%</div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recommendation Rate</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Welcome, {{ auth()->user()->name }}!
            </h3>
            <p class="mt-2 text-gray-700 dark:text-gray-300">
                Here you can manage your bookings, browse caterers and packages, view payments, and receive notifications.
            </p>

            {{-- Placeholder for dashboard cards, stats, recent bookings --}}
        </div>
    </div>
</x-app-layout>

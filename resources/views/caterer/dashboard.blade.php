<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Caterer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Welcome, {{ auth()->user()->name }}!</h3>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Monitor your bookings, manage your menus and packages, and check reviews and payments here.
                </p>
                {{-- Placeholder for analytics graphs can be added here later --}}
            </div>
        </div>
    </div>
</x-app-layout>

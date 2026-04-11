<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/foodlogo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/foodlogo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/foodlogo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 sm:py-12 bg-gray-50 dark:bg-gray-950">
            <!-- Background decoration -->
            <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
                <div class="absolute -top-32 left-1/2 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-gradient-to-r from-emerald-200/60 via-teal-200/50 to-indigo-200/50 blur-3xl dark:from-emerald-900/30 dark:via-teal-900/20 dark:to-indigo-900/20"></div>
                <div class="absolute -bottom-40 right-[-8rem] h-80 w-80 rounded-full bg-gradient-to-tr from-amber-200/50 to-rose-200/40 blur-3xl dark:from-amber-900/20 dark:to-rose-900/20"></div>
            </div>

            <div class="relative w-full max-w-lg">
                <div class="rounded-2xl bg-white/90 dark:bg-gray-900/70 backdrop-blur border border-gray-200/70 dark:border-gray-800 shadow-xl shadow-gray-200/60 dark:shadow-black/40 overflow-hidden">
                    <div class="px-6 py-6 sm:px-10 sm:py-8">
                        {{ $slot }}
                    </div>
                </div>
                <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.name', 'CaterEase') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
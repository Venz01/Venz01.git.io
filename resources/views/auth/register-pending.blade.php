<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Registration Pending Approval</h1>
        <p class="text-gray-600 dark:text-gray-400">
            Thank you for registering as a caterer. Your business permit and information will be reviewed by an administrator.
            Please wait for approval before logging in.
        </p>
        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-600">
                Return to Login
            </a>
        </div>
    </div>
</x-guest-layout>

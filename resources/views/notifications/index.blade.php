<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    Mark all as read
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                @if($notifications->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                        <li class="hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $notification->isUnread() ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                            <a href="{{ route('notifications.read', $notification->id) }}" class="block px-6 py-4">
                                <div class="flex items-start space-x-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="h-10 w-10 rounded-full flex items-center justify-center
                                            @if($notification->color === 'green') bg-green-100 text-green-600
                                            @elseif($notification->color === 'yellow') bg-yellow-100 text-yellow-600
                                            @elseif($notification->color === 'red') bg-red-100 text-red-600
                                            @elseif($notification->color === 'blue') bg-blue-100 text-blue-600
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                            @if($notification->icon === 'check-circle')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notification->icon === 'clock')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notification->icon === 'x-circle')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notification->icon === 'star')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            @elseif($notification->icon === 'credit-card')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $notification->title }}
                                            </p>
                                            @if($notification->isUnread())
                                                <div class="flex-shrink-0 ml-2">
                                                    <div class="h-2 w-2 bg-indigo-600 rounded-full"></div>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $notification->message }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No Notifications</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
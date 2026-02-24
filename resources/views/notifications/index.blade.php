<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $notifications->total() }} total
                </span>
                <button id="mark-all-btn"
                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium disabled:opacity-40"
                        {{ $notifications->total() === 0 ? 'disabled' : '' }}>
                    Mark all as read
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">

                @if($notifications->count() > 0)
                    @php
                        // Defined once — not inside the loop
                        $colorClasses = [
                            'green'  => 'bg-green-100 text-green-600',
                            'yellow' => 'bg-yellow-100 text-yellow-600',
                            'red'    => 'bg-red-100 text-red-600',
                            'blue'   => 'bg-blue-100 text-blue-600',
                            'indigo' => 'bg-indigo-100 text-indigo-600',
                            'gray'   => 'bg-gray-100 text-gray-600',
                        ];
                    @endphp

                    <ul class="divide-y divide-gray-200 dark:divide-gray-700" id="notification-list">
                        @foreach($notifications as $notification)
                            @php
                                $cc       = $colorClasses[$notification->color] ?? 'bg-gray-100 text-gray-600';
                                $isUnread = $notification->isUnread();
                            @endphp

                            <li id="notif-{{ $notification->id }}"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $isUnread ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">

                                <a href="{{ route('notifications.read', $notification->id) }}" class="block px-6 py-4">
                                    <div class="flex items-start space-x-3">

                                        {{-- Icon bubble — pure PHP partial, no Blade directives inside --}}
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $cc }}">
                                                @include('notifications._icon', ['icon' => $notification->icon])
                                            </div>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $notification->title }}
                                                </p>
                                                @if($isUnread)
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

    @push('scripts')
    <script>
        document.getElementById('mark-all-btn')?.addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true;
            btn.textContent = 'Marking…';

            try {
                const res = await fetch('{{ route('notifications.mark-all-read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) throw new Error('Request failed');

                // Remove unread highlights instantly without page reload
                document.querySelectorAll('#notification-list li').forEach(li => {
                    li.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                    li.querySelector('.h-2.w-2.bg-indigo-600')?.closest('.flex-shrink-0')?.remove();
                });

                btn.textContent = 'All read';

                if (window.NotificationPoller) NotificationPoller.refresh();

            } catch (e) {
                btn.disabled = false;
                btn.textContent = 'Mark all as read';
                alert('Something went wrong. Please try again.');
            }
        });
    </script>
    @endpush

</x-app-layout>
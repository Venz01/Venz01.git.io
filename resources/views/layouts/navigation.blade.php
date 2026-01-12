<nav x-data="{ 
    open: false, 
    notificationsOpen: false,
    notifications: [],
    unreadCount: 0,
    loading: false
}" 
@click.away="notificationsOpen = false"
x-init="
    // Fetch notifications on load
    fetch('/notifications/unread')
        .then(res => res.json())
        .then(data => {
            notifications = data.notifications;
            unreadCount = data.unread_count;
        })
        .catch(error => console.error('Error fetching notifications:', error));
    
    // Refresh notifications every 30 seconds
    setInterval(() => {
        fetch('/notifications/unread')
            .then(res => res.json())
            .then(data => {
                notifications = data.notifications;
                unreadCount = data.unread_count;
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }, 30000);
"
class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/logo.jpg') }}" 
                            alt="Restaurant Logo" 
                            class="h-16 w-auto">
                        <span class="text-xl font-bold text-gray-800 dark:text-gray-200 hidden lg:block">CaterEase</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-2 md:space-x-4 lg:space-x-6 sm:-my-px sm:ms-4 lg:ms-8 sm:flex sm:overflow-x-auto">
                    @php $role = auth()->user()->role; @endphp

                    @if ($role === 'customer')
                    <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.caterers')" :active="request()->routeIs('customer.caterers')">
                        {{ __('Caterers') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.bookings')" :active="request()->routeIs('customer.bookings')">
                        {{ __('Bookings') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.payments')" :active="request()->routeIs('customer.payments')">
                        {{ __('Payments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.cart')" :active="request()->routeIs('customer.cart')">
                        {{ __('Cart') }}
                    </x-nav-link>

                    @elseif ($role === 'caterer')
                    <x-nav-link :href="route('caterer.dashboard')" :active="request()->routeIs('caterer.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.calendar')" :active="request()->routeIs('caterer.calendar')">
                        {{ __('Calendar') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.bookings')" :active="request()->routeIs('caterer.bookings')">
                        {{ __('Bookings') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.menus')" :active="request()->routeIs('caterer.menus')">
                        {{ __('Menus') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.verifyReceipt')" :active="request()->routeIs('caterer.verifyReceipt')">
                        {{ __('Receipts') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.payments')" :active="request()->routeIs('caterer.payments')">
                        {{ __('Payments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('caterer.reviews')" :active="request()->routeIs('caterer.reviews')">
                        {{ __('Reviews') }}
                    </x-nav-link>

                    @elseif ($role === 'admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('User Management') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.activity-logs')" :active="request()->routeIs('admin.activity-logs')">
                        {{ __('Activity Logs') }}
                    </x-nav-link>

                    @else
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side: Notifications + User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                
                <!-- Notifications Bell -->
                <div class="relative">
                    <button @click="notificationsOpen = !notificationsOpen" 
                            class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Unread Badge -->
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount > 99 ? '99+' : unreadCount"
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 min-w-5 flex items-center justify-center px-1">
                        </span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="notificationsOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                            <button @click.prevent="
                                if (confirm('Mark all notifications as read?')) {
                                    fetch('/notifications/mark-all-read', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            notifications.forEach(n => n.read_at = new Date().toISOString());
                                            unreadCount = 0;
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                                }
                            "
                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                Mark all as read
                            </button>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No new notifications</p>
                                </div>
                            </template>

                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="'/notifications/' + notification.id + '/read'"
                                   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                   :class="{ 'bg-indigo-50 dark:bg-indigo-900/20': !notification.read_at }">
                                    <div class="flex items-start space-x-3">
                                        <!-- Icon based on type -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                                 :class="{
                                                     'bg-green-100 text-green-600': notification.type.includes('confirmed') || notification.type.includes('completed'),
                                                     'bg-yellow-100 text-yellow-600': notification.type.includes('pending') || notification.type.includes('balance'),
                                                     'bg-red-100 text-red-600': notification.type.includes('rejected') || notification.type.includes('cancelled'),
                                                     'bg-blue-100 text-blue-600': notification.type.includes('review'),
                                                     'bg-gray-100 text-gray-600': !notification.type.includes('confirmed') && !notification.type.includes('pending') && !notification.type.includes('rejected') && !notification.type.includes('review')
                                                 }">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2" x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="new Date(notification.created_at).toLocaleString()"></p>
                                        </div>

                                        <!-- Unread indicator -->
                                        <div x-show="!notification.read_at" class="flex-shrink-0">
                                            <div class="h-2 w-2 bg-indigo-600 rounded-full"></div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-center">
                            <a href="/notifications" 
                               class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="hidden md:block">{{ Auth::user()->name }}</div>
                            <div class="md:hidden">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @php $role = auth()->user()->role; @endphp

        <div class="pt-2 pb-3 space-y-1">
            @if ($role === 'customer')
                <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.caterers')" :active="request()->routeIs('customer.caterers')">
                    {{ __('Caterers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.bookings')" :active="request()->routeIs('customer.bookings')">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.payments')" :active="request()->routeIs('customer.payments')">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Notifications') }}</span>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount"
                              class="bg-red-500 text-white text-xs font-bold rounded-full h-5 min-w-5 flex items-center justify-center px-1.5">
                        </span>
                    </div>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.cart')" :active="request()->routeIs('customer.cart')">
                    {{ __('Cart') }}
                </x-responsive-nav-link>

            @elseif ($role === 'caterer')
                <x-responsive-nav-link :href="route('caterer.dashboard')" :active="request()->routeIs('caterer.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.calendar')" :active="request()->routeIs('caterer.calendar')">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.bookings')" :active="request()->routeIs('caterer.bookings')">
                    {{ __('Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.menus')" :active="request()->routeIs('caterer.menus')">
                    {{ __('Menus & Packages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.verifyReceipt')" :active="request()->routeIs('caterer.verifyReceipt')">
                    {{ __('Verify Receipts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.payments')" :active="request()->routeIs('caterer.payments')">
                    {{ __('Payments & Revenue') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('caterer.reviews')" :active="request()->routeIs('caterer.reviews')">
                    {{ __('Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Notifications') }}</span>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount"
                              class="bg-red-500 text-white text-xs font-bold rounded-full h-5 min-w-5 flex items-center justify-center px-1.5">
                        </span>
                    </div>
                </x-responsive-nav-link>

            @elseif ($role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('User Management') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.activity-logs')" :active="request()->routeIs('admin.activity-logs')">
                    {{ __('Activity Logs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Notifications') }}</span>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount"
                              class="bg-red-500 text-white text-xs font-bold rounded-full h-5 min-w-5 flex items-center justify-center px-1.5">
                        </span>
                    </div>
                </x-responsive-nav-link>

            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
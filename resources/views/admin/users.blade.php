<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.users') }}" class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, email, business name..." 
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <input type="hidden" name="role" value="{{ request('role') }}">
                        </div>
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users', ['role' => request('role')]) }}" 
                           class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('admin.users', ['search' => request('search')]) }}" 
                           class="@if(!request('role')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            All Users
                        </a>
                        <a href="{{ route('admin.users', ['role' => 'customer', 'search' => request('search')]) }}" 
                           class="@if(request('role') === 'customer') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Customers
                        </a>
                        <a href="{{ route('admin.users', ['role' => 'caterer', 'search' => request('search')]) }}" 
                           class="@if(request('role') === 'caterer') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Caterers
                        </a>
                        <a href="{{ route('admin.users', ['role' => 'admin', 'search' => request('search')]) }}" 
                           class="@if(request('role') === 'admin') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Admins
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bulk Actions Bar (Hidden by default, shown when items are selected) -->
            <div id="bulkActionsBar" class="hidden mb-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="selectedCount" class="text-sm font-medium text-indigo-900 dark:text-indigo-100">0 users selected</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="bulkAction('activate')"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activate
                        </button>
                        <button type="button" 
                                onclick="bulkAction('suspend')"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Suspend
                        </button>
                        <button type="button" 
                                onclick="bulkAction('delete')"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                        <button type="button" 
                                onclick="clearSelection()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left">
                                    <input type="checkbox" 
                                           id="selectAll"
                                           onchange="toggleSelectAll(this)"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->id !== auth()->id())
                                        <input type="checkbox" 
                                               class="user-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               value="{{ $user->id }}"
                                               data-user-name="{{ $user->name }}"
                                               data-user-email="{{ $user->email }}"
                                               onchange="updateBulkActionsBar()">
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <span class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span class="ml-1 text-xs text-gray-400 dark:text-gray-500">(You)</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->email }}
                                            </div>
                                            @if($user->role === 'caterer' && $user->business_name)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    {{ $user->business_name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->role === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @elseif($user->role === 'caterer') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'caterer')
                                        @if($user->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Approved
                                            </span>
                                        @elseif($user->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Pending
                                            </span>
                                        @elseif($user->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Rejected
                                            </span>
                                        @elseif($user->status === 'suspended')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        @endif
                                    @else
                                        @if($user->status === 'suspended')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Active
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($user->role === 'caterer')
                                            <!-- Review Caterer Button -->
                                            <a href="{{ route('admin.caterers.show', ['caterer' => $user->id, 'from' => 'users']) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                               title="Review Caterer Information">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Review
                                            </a>

                                            @if($user->status === 'pending')
                                                <!-- Quick Approve Button -->
                                                <button type="button"
                                                        onclick="showConfirmation('approve', {{ $user->id }}, '{{ $user->name }}', '{{ $user->business_name }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                        title="Approve Caterer">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Approve
                                                </button>

                                                <!-- Quick Reject Button -->
                                                <button type="button"
                                                        onclick="showConfirmation('reject', {{ $user->id }}, '{{ $user->name }}', '{{ $user->business_name }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                                        title="Reject Caterer">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            @endif
                                        @endif

                                        @if($user->id !== auth()->id())
                                            <!-- Status Toggle Dropdown -->
                                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                                <button @click="open = !open" 
                                                        type="button" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                                        title="More Actions">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                    </svg>
                                                </button>

                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        <button type="button"
                                                                onclick="showConfirmation('suspend', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            Suspend User
                                                        </button>
                                                        <button type="button"
                                                                onclick="showConfirmation('activate', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            Activate User
                                                        </button>
                                                        <button type="button"
                                                                onclick="showConfirmation('delete', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            Delete User
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No users found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if(request('search'))
                                            No users match your search criteria.
                                        @else
                                            There are no users matching your criteria.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-0 w-full max-w-md">
            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Confirm Action
                        </h3>
                        <button onclick="closeConfirmation()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" id="modalIconContainer">
                        <svg id="modalIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <!-- Message -->
                    <div class="text-center">
                        <p id="modalMessage" class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Are you sure you want to perform this action?
                        </p>
                        <div id="modalUserInfo" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-left max-h-60 overflow-y-auto">
                            <!-- User info will be inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg flex items-center justify-end space-x-3">
                    <button onclick="closeConfirmation()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button id="confirmButton" 
                            onclick="confirmAction()" 
                            class="px-4 py-2 text-sm font-medium text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="approveForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <form id="rejectForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <form id="statusForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulkActionForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="user_ids" id="bulkUserIds">
        <input type="hidden" name="action" id="bulkActionType">
    </form>

    <script>
        let currentAction = '';
        let currentUserId = '';
        let selectedUserIds = [];

        // Toggle individual checkbox selection
        function updateBulkActionsBar() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const count = checkboxes.length;
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');

            if (count > 0) {
                bulkActionsBar.classList.remove('hidden');
                selectedCount.textContent = `${count} user${count !== 1 ? 's' : ''} selected`;
                selectedUserIds = Array.from(checkboxes).map(cb => cb.value);
            } else {
                bulkActionsBar.classList.add('hidden');
                selectedUserIds = [];
            }

            // Update "Select All" checkbox state
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');
            if (allCheckboxes.length > 0) {
                selectAllCheckbox.checked = count === allCheckboxes.length;
                selectAllCheckbox.indeterminate = count > 0 && count < allCheckboxes.length;
            }
        }

        // Toggle all checkboxes
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateBulkActionsBar();
        }

        // Clear all selections
        function clearSelection() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateBulkActionsBar();
        }

        // Bulk action handler
        function bulkAction(action) {
            if (selectedUserIds.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const userNames = Array.from(checkboxes).map(cb => cb.dataset.userName);

            currentAction = 'bulk_' + action;
            
            const modal = document.getElementById('confirmationModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalUserInfo = document.getElementById('modalUserInfo');
            const modalIconContainer = document.getElementById('modalIconContainer');
            const modalIcon = document.getElementById('modalIcon');
            const confirmButton = document.getElementById('confirmButton');

            // Configure modal based on action
            switch(action) {
                case 'activate':
                    modalTitle.textContent = 'Activate Users';
                    modalMessage.textContent = `Are you sure you want to activate ${selectedUserIds.length} user${selectedUserIds.length !== 1 ? 's' : ''}?`;
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-green-100 dark:bg-green-900';
                    modalIcon.className = 'h-6 w-6 text-green-600 dark:text-green-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500';
                    confirmButton.textContent = 'Activate Users';
                    break;

                case 'suspend':
                    modalTitle.textContent = 'Suspend Users';
                    modalMessage.textContent = `Are you sure you want to suspend ${selectedUserIds.length} user${selectedUserIds.length !== 1 ? 's' : ''}? They will not be able to access their accounts until reactivated.`;
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-yellow-100 dark:bg-yellow-900';
                    modalIcon.className = 'h-6 w-6 text-yellow-600 dark:text-yellow-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500';
                    confirmButton.textContent = 'Suspend Users';
                    break;

                case 'delete':
                    modalTitle.textContent = 'Delete Users';
                    modalMessage.textContent = `Are you sure you want to delete ${selectedUserIds.length} user${selectedUserIds.length !== 1 ? 's' : ''}? This action cannot be undone.`;
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-red-100 dark:bg-red-900';
                    modalIcon.className = 'h-6 w-6 text-red-600 dark:text-red-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500';
                    confirmButton.textContent = 'Delete Users';
                    break;
            }

            // Display user list
            modalUserInfo.innerHTML = `
                <div class="text-sm space-y-1">
                    <div class="font-medium text-gray-900 dark:text-gray-100 mb-2">Selected users:</div>
                    ${userNames.map(name => `<div class="text-gray-600 dark:text-gray-400">• ${name}</div>`).join('')}
                </div>
            `;

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function showConfirmation(action, userId, userName, userDetail) {
            currentAction = action;
            currentUserId = userId;

            const modal = document.getElementById('confirmationModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalUserInfo = document.getElementById('modalUserInfo');
            const modalIconContainer = document.getElementById('modalIconContainer');
            const modalIcon = document.getElementById('modalIcon');
            const confirmButton = document.getElementById('confirmButton');

            // Configure modal based on action
            switch(action) {
                case 'approve':
                    modalTitle.textContent = 'Approve Caterer';
                    modalMessage.textContent = 'Are you sure you want to approve this caterer? They will be able to access the platform and receive bookings.';
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-green-100 dark:bg-green-900';
                    modalIcon.className = 'h-6 w-6 text-green-600 dark:text-green-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500';
                    confirmButton.textContent = 'Approve Caterer';
                    modalUserInfo.innerHTML = `
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${userName}</div>
                            <div class="text-gray-500 dark:text-gray-400">${userDetail}</div>
                        </div>
                    `;
                    break;

                case 'reject':
                    modalTitle.textContent = 'Reject Caterer';
                    modalMessage.textContent = 'Are you sure you want to reject this caterer application? This action will deny their access to the platform.';
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-red-100 dark:bg-red-900';
                    modalIcon.className = 'h-6 w-6 text-red-600 dark:text-red-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500';
                    confirmButton.textContent = 'Reject Caterer';
                    modalUserInfo.innerHTML = `
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${userName}</div>
                            <div class="text-gray-500 dark:text-gray-400">${userDetail}</div>
                        </div>
                    `;
                    break;

                case 'suspend':
                    modalTitle.textContent = 'Suspend User';
                    modalMessage.textContent = 'Are you sure you want to suspend this user? They will not be able to access their account until reactivated.';
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-yellow-100 dark:bg-yellow-900';
                    modalIcon.className = 'h-6 w-6 text-yellow-600 dark:text-yellow-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500';
                    confirmButton.textContent = 'Suspend User';
                    modalUserInfo.innerHTML = `
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${userName}</div>
                            <div class="text-gray-500 dark:text-gray-400">${userDetail}</div>
                        </div>
                    `;
                    break;

                case 'activate':
                    modalTitle.textContent = 'Activate User';
                    modalMessage.textContent = 'Are you sure you want to activate this user? They will regain full access to their account.';
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-blue-100 dark:bg-blue-900';
                    modalIcon.className = 'h-6 w-6 text-blue-600 dark:text-blue-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
                    confirmButton.textContent = 'Activate User';
                    modalUserInfo.innerHTML = `
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${userName}</div>
                            <div class="text-gray-500 dark:text-gray-400">${userDetail}</div>
                        </div>
                    `;
                    break;

                case 'delete':
                    modalTitle.textContent = 'Delete User';
                    modalMessage.textContent = 'Are you sure you want to delete this user? This action cannot be undone and will permanently remove all user data.';
                    modalIconContainer.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-red-100 dark:bg-red-900';
                    modalIcon.className = 'h-6 w-6 text-red-600 dark:text-red-300';
                    modalIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500';
                    confirmButton.textContent = 'Delete User';
                    modalUserInfo.innerHTML = `
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${userName}</div>
                            <div class="text-gray-500 dark:text-gray-400">${userDetail}</div>
                        </div>
                    `;
                    break;
            }

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmation() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmAction() {
            let form;
            
            if (currentAction.startsWith('bulk_')) {
                // Handle bulk actions
                const bulkAction = currentAction.replace('bulk_', '');
                form = document.getElementById('bulkActionForm');
                form.action = '/admin/users/bulk-action';
                document.getElementById('bulkUserIds').value = JSON.stringify(selectedUserIds);
                document.getElementById('bulkActionType').value = bulkAction;
            } else {
                // Handle single user actions
                switch(currentAction) {
                    case 'approve':
                        form = document.getElementById('approveForm');
                        form.action = `/admin/caterers/${currentUserId}/approve`;
                        break;
                        
                    case 'reject':
                        form = document.getElementById('rejectForm');
                        form.action = `/admin/caterers/${currentUserId}/reject`;
                        break;
                        
                    case 'suspend':
                        form = document.getElementById('statusForm');
                        form.action = `/admin/users/${currentUserId}/status`;
                        document.getElementById('statusInput').value = 'suspended';
                        break;
                        
                    case 'activate':
                        form = document.getElementById('statusForm');
                        form.action = `/admin/users/${currentUserId}/status`;
                        document.getElementById('statusInput').value = 'active';
                        break;

                    case 'delete':
                        form = document.getElementById('deleteForm');
                        form.action = `/admin/users/${currentUserId}`;
                        break;
                }
            }
            
            if (form) {
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('confirmationModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmation();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmation();
            }
        });
    </script>
</x-app-layout>
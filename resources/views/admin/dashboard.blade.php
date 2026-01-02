<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Statistics Cards --}}
            @php
                $totalUsers = \App\Models\User::count();
                $totalCaterers = \App\Models\User::where('role', 'caterer')->count();
                $pendingApprovals = \App\Models\User::where('role', 'caterer')->where('status', 'pending')->count();
                $totalCustomers = \App\Models\User::where('role', 'customer')->count();
            @endphp
            
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                {{-- Total Users --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $totalUsers }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Caterers --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Caterers</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $totalCaterers }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Approvals --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Approvals</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $pendingApprovals }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Customers --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Customers</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $totalCustomers }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Caterer Approvals --}}
            @php
                $pendingCaterers = \App\Models\User::where('role', 'caterer')
                                                   ->where('status', 'pending')
                                                   ->get();
            @endphp
            
            @if($pendingCaterers->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">Pending Caterer Approvals</h2>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($pendingCaterers as $caterer)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-500 dark:text-gray-400 font-medium">
                                                    {{ substr($caterer->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                {{ $caterer->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $caterer->email }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                Applied: {{ $caterer->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.caterers.show', $caterer->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded shadow-sm text-indigo-700 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Review Details
                                        </a>
                                        <form action="{{ route('admin.caterers.approve', $caterer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Are you sure you want to approve this caterer?')">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.caterers.reject', $caterer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="return confirm('Are you sure you want to reject this caterer?')">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @else
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No pending approvals</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All caterer applications have been reviewed.</p>
                </div>
            </div>
            @endif

            {{-- Recent Activity --}}
            <div class="mb-8">
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">Recent Activity</h2>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentActivities as $activity)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                            {{ $activity->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity->description }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-5 sm:px-6 text-center text-gray-500 dark:text-gray-400">
                        No recent activity
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
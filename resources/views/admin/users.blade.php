<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 uppercase tracking-wider text-left text-gray-900 dark:text-gray-200 text-xs font-medium">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 uppercase tracking-wider text-left text-gray-900 dark:text-gray-200 text-xs font-medium">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 uppercase tracking-wider text-left text-gray-900 dark:text-gray-200 text-xs font-medium">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 uppercase tracking-wider text-left text-gray-900 dark:text-gray-200 text-xs font-medium">
                                Permit Photo
                            </th>

                            <th scope="col"
                                class="px-6 py-3 uppercase tracking-wider text-left text-gray-900 dark:text-gray-200 text-xs font-medium">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                        <tr>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ ucfirst($user->role) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->business_permit_file_path)
                                <a href="{{ asset('storage/' . $user->business_permit_file_path) }}" target="_blank"
                                    class="text-indigo-600 dark:text-indigo-400 underline">
                                    View
                                </a>
                                @else
                                <span class="text-gray-400">None</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->status ?? 'active' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($user->role === 'caterer')
                                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                                        <option value="approved" @if($user->status === 'approved') selected
                                            @endif>Approve</option>
                                        <option value="blocked" @if($user->status === 'blocked') selected @endif>Block
                                        </option>
                                    </select>

                                </form>
                                @else
                                <span class="text-gray-400">{{ __('N/A') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

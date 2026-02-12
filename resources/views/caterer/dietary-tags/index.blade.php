<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dietary Tags Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Info Banner -->
            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                            About Dietary Tags
                        </h3>
                        <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                            Create and manage dietary preference tags that customers can use to filter packages and set their preferences.
                            When you add a new tag, it automatically becomes available for all customers to use.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Add New Tag Button -->
            <div class="mb-6">
                <button onclick="openModal('addTagModal')" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Dietary Tag
                </button>
            </div>

            <!-- Tags List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">All Dietary Tags</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($tags as $tag)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">{{ $tag->icon }}</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $tag->name }}</h4>
                                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $tag->color }}-100 text-{{ $tag->color }}-800 dark:bg-{{ $tag->color }}-900/30 dark:text-{{ $tag->color }}-400">
                                                {{ ucfirst($tag->color) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($tag->is_system)
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                            System
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                    <p>Slug: <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">{{ $tag->slug }}</code></p>
                                </div>

                                @if(!$tag->is_system)
                                    <div class="flex gap-2">
                                        <button onclick="openEditModal({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->icon }}', '{{ $tag->color }}')"
                                                class="flex-1 text-sm px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                            Edit
                                        </button>
                                        <button onclick="confirmDelete({{ $tag->id }}, '{{ $tag->name }}')"
                                                class="flex-1 text-sm px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                                        System tags cannot be modified or deleted
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No dietary tags available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Tag Modal -->
    <div id="addTagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Dietary Tag</h3>
            
            <form action="{{ route('caterer.dietary-tags.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tag Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                               placeholder="e.g., Keto-Friendly"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Icon (Emoji)
                        </label>
                        <input type="text" name="icon" maxlength="10"
                               placeholder="e.g., 🥑"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Color <span class="text-red-500">*</span>
                        </label>
                        <select name="color" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                            <option value="red">Red</option>
                            <option value="green">Green</option>
                            <option value="blue">Blue</option>
                            <option value="yellow">Yellow</option>
                            <option value="purple">Purple</option>
                            <option value="pink">Pink</option>
                            <option value="indigo">Indigo</option>
                            <option value="orange">Orange</option>
                            <option value="cyan">Cyan</option>
                            <option value="emerald">Emerald</option>
                            <option value="gray">Gray</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('addTagModal')"
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        Add Tag
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div id="editTagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Edit Dietary Tag</h3>
            
            <form id="editTagForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tag Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="editTagName" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Icon (Emoji)
                        </label>
                        <input type="text" name="icon" id="editTagIcon" maxlength="10"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Color <span class="text-red-500">*</span>
                        </label>
                        <select name="color" id="editTagColor" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                            <option value="red">Red</option>
                            <option value="green">Green</option>
                            <option value="blue">Blue</option>
                            <option value="yellow">Yellow</option>
                            <option value="purple">Purple</option>
                            <option value="pink">Pink</option>
                            <option value="indigo">Indigo</option>
                            <option value="orange">Orange</option>
                            <option value="cyan">Cyan</option>
                            <option value="emerald">Emerald</option>
                            <option value="gray">Gray</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('editTagModal')"
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Update Tag
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openEditModal(id, name, icon, color) {
            document.getElementById('editTagName').value = name;
            document.getElementById('editTagIcon').value = icon;
            document.getElementById('editTagColor').value = color;
            document.getElementById('editTagForm').action = `/caterer/dietary-tags/${id}`;
            openModal('editTagModal');
        }

        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete the tag "${name}"? This action cannot be undone.`)) {
                const form = document.getElementById('deleteForm');
                form.action = `/caterer/dietary-tags/${id}`;
                form.submit();
            }
        }

        // Close modals when clicking outside
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
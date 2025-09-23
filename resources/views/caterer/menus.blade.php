<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Menu Items') }}
        </h2>
    </x-slot>

    <div x-data="{ selectedCategory: 'all' }" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">


        <!-- Top Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex gap-3">
                <button onclick="toggleModal('categoryModal')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <span>+ Add Category</span>
                </button>
                <button onclick="toggleModal('packageModal')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <span>+ Create New Package</span>
                </button>
            </div>

            <!-- Filter -->
            <div>
                <select x-model="selectedCategory"
                    class="border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Categories -->
        @foreach($categories as $category)
        <div x-show="selectedCategory === 'all' || selectedCategory == '{{ $category->id }}'"
            class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <!-- Category Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {{ $category->name }}
                    <span class="text-sm text-gray-500">({{ $category->items->count() }} items)</span>
                </h3>

                <div class="flex gap-2">
                    <!-- Edit Category -->
                    <button type="button"
                        onclick="openEditCategoryModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                        class="text-blue-500 hover:text-blue-700">
                         Edit
                    </button>

                    <!-- Delete Category -->
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700"> Delete</button>
                    </form>
                </div>
            </div>


            <!-- Items List -->
            <div class="space-y-4">
                @forelse($category->items as $item)
                <div class="flex items-start gap-4 border-b pb-3">
                    <!-- Image -->
                    @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                        class="w-16 h-16 rounded-md object-cover border">
                    @else
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded-md text-xs text-gray-500">
                        No Image
                    </div>
                    @endif

                    <!-- Details -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->name }}</h4>
                            <span
                                class="px-2 py-0.5 text-xs rounded-full 
                                {{ $item->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->description }}</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">
                            Price: ₱{{ $item->price }}/serving
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <!-- Edit -->
                        <button type="button"
                            onclick="openEditItemModal({{ $item->id }}, '{{ $item->name }}', '{{ $item->description }}', {{ $item->price }}, '{{ $item->status }}')"
                            class="text-blue-500 hover:text-blue-700">
                            ✏️
                        </button>

                        <!-- Delete -->
                        <form action="{{ route('menu-items.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">🗑</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">No items yet.</p>
                @endforelse
            </div>
        </div>
        @endforeach

        <!-- Packages Section -->
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Packages</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden flex flex-col">
                    <!-- Package Image -->
                    @if($package->image_path)
                    <img src="{{ asset('storage/' . $package->image_path) }}" class="h-48 w-full object-cover">
                    @else
                    <div class="h-48 w-full flex items-center justify-center bg-gray-200 text-gray-500">
                        No Image
                    </div>
                    @endif


                    <!-- Package Content -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                {{ $package->name }}
                            </h3>
                            <span
                                class="px-2 py-0.5 text-xs rounded-full 
                            {{ $package->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($package->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ $package->description }}
                        </p>

                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ₱{{ number_format($package->price, 0) }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Base price for <strong>{{ $package->guest_count }}</strong> guests
                        </p>

                        <p class="text-sm text-gray-500">
                            Menu Items: <strong>{{ $package->items->count() }}</strong>
                        </p>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-between items-center">
                            <button type="button"
                                onclick="openEditPackageModal({{ $package->id }}, '{{ $package->name }}', '{{ $package->description }}', {{ $package->price }}, {{ $package->pax }})"
                                class="text-blue-600 hover:text-blue-800 font-medium">
                                     Edit
                            </button>

                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this package?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">No packages created yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Category</h2>
            <!-- Flash inside modal -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-lg text-sm mb-3">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg text-sm mb-3">
                {{ session('error') }}
            </div>
            @endif
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Category Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Description"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('categoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Package Modal -->
    <div id="packageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Package</h2>
            <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Package Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Description"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                    <input type="number" name="price" placeholder="Price"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <input type="number" name="pax" placeholder="How many pax?"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>

                    <!-- Image Upload -->
                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">

                    <h3 class="font-semibold mt-4 text-gray-800 dark:text-gray-200">Select Menu Items</h3>
                    <div class="max-h-40 overflow-y-auto border rounded-lg p-2 dark:border-gray-700">
                        @foreach($categories as $category)
                        <p class="font-medium text-sm mt-2 text-gray-600 dark:text-gray-400">{{ $category->name }}</p>
                        @foreach($category->items as $item)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="menu_items[]" value="{{ $item->id }}">
                            <span class="text-gray-800 dark:text-gray-200">{{ $item->name }} -
                                ₱{{ $item->price }}</span>
                        </label>
                        @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('packageModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Save</button>
                </div>
            </form>

        </div>
    </div>


    <!-- Add Item Modal -->
    <div id="itemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Item</h2>
            <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" id="modalCategoryId">

                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Item Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Description"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                    <input type="number" name="price" placeholder="Price"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                    <select name="status" class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('itemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Item</h2>
            <form id="editItemForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="space-y-3">
                    <input type="text" name="name" id="editName"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" id="editDescription"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                    <input type="number" name="price" id="editPrice"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                    <select name="status" id="editStatus"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('editItemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Category</h2>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <input type="text" name="name" id="editCategoryName"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" id="editCategoryDescription"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('editCategoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div id="editPackageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Package</h2>

            <form id="editPackageForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <input type="text" name="name" id="editPackageName"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>

                    <textarea name="description" id="editPackageDescription"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"></textarea>

                    <input type="number" name="price" id="editPackagePrice"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>

                    <input type="number" name="pax" id="editPackagePax"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>

                    <!-- Image Upload -->
                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('editPackageModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>




    <!-- JS for Modals -->
    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function openItemModal(categoryId) {
            document.getElementById('modalCategoryId').value = categoryId;
            toggleModal('itemModal');
        }

        function openEditItemModal(id, name, description, price, status) {
            // Fill form with current values
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPrice').value = price;
            document.getElementById('editStatus').value = status;

            // Update form action dynamically
            const form = document.getElementById('editItemForm');
            form.action = `/caterer/menu-items/${id}`;

            // Show modal
            toggleModal('editItemModal');
        }

        function openEditCategoryModal(id, name, description) {
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editCategoryDescription').value = description;

            const form = document.getElementById('editCategoryForm');
            form.action = `/caterer/categories/${id}`; // now valid because resource route exists

            toggleModal('editCategoryModal');
        }

        function openEditPackageModal(id, name, description, price, pax) {
            document.getElementById('editPackageName').value = name;
            document.getElementById('editPackageDescription').value = description;
            document.getElementById('editPackagePrice').value = price;
            document.getElementById('editPackagePax').value = pax;

            const form = document.getElementById('editPackageForm');
            form.action = `/caterer/packages/${id}`;

            toggleModal('editPackageModal');
        }

    </script>

</x-app-layout>

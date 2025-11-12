<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Menu Items & Packages') }}
        </h2>
    </x-slot>

    <div x-data="menuManager()" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Loading Overlay -->
        <div x-show="loading" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 flex items-center gap-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span>Processing...</span>
            </div>
        </div>

        <!-- Top Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex gap-3">
                <button @click="openModal('categoryModal')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <span>+ Add Category</span>
                </button>
                <button @click="openModal('packageModal')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <span>+ Create Package</span>
                </button>
            </div>

            <!-- Filter -->
            <div>
                <select x-model="selectedCategory"
                    class="border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200 transition-colors">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Categories -->
        @foreach($categories as $category)
        <div x-show="selectedCategory === 'all' || selectedCategory == '{{ $category->id }}'" x-transition
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">

            <!-- Category Header -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ $category->name }}
                        <span class="text-sm text-gray-500 ml-2">({{ $category->items->count() }} items)</span>
                    </h3>
                    @if($category->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $category->description }}</p>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button type="button"
                        @click="openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
                        class="text-blue-600 hover:text-blue-800 transition-colors text-sm font-medium">
                        Edit
                    </button>

                    <form action="{{ route('caterer.categories.destroy', $category->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this category? You must delete all items in this category first.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-800 transition-colors text-sm font-medium">
                            Delete
                        </button>
                    </form>

                    <button type="button" @click="openItemModal({{ $category->id }})"
                        class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-sm hover:bg-indigo-200 transition-colors">
                        + Add Item
                    </button>
                </div>
            </div>

            <!-- Items List -->
            <div class="space-y-4">
                @forelse($category->items as $item)
                <div class="flex items-start gap-4 border-b border-gray-100 dark:border-gray-700 pb-3 last:border-b-0">
                    <!-- Image -->
                    @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                        class="w-16 h-16 rounded-md object-cover border border-gray-200" alt="{{ $item->name }}">
                    @else
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-md text-xs text-gray-500">
                        No Image
                    </div>
                    @endif

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $item->name }}</h4>
                            <span
                                class="px-2 py-0.5 text-xs rounded-full whitespace-nowrap
                                {{ $item->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>

                        @if($item->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $item->description }}</p>
                        @endif

                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price: ₱{{ number_format($item->price, 2) }}/serving
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 flex-shrink-0">
                        <button type="button"
                            @click="openEditItemModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->description ?? '') }}', {{ $item->price }}, '{{ $item->status }}')"
                            class="text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </button>

                        <form action="{{ route('caterer.menu-items.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Delete this menu item?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm italic">No items in this category yet.</p>
                @endforelse
            </div>
        </div>
        @endforeach

        <!-- Packages Section -->
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Packages</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Package Image -->
                    @if($package->image_path)
                    <img src="{{ asset('storage/' . $package->image_path) }}" class="h-48 w-full object-cover"
                        alt="{{ $package->name }}">
                    @else
                    <div
                        class="h-48 w-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-500">
                        No Image Available
                    </div>
                    @endif

                    <!-- Package Content -->
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 leading-tight">
                                {{ $package->name }}
                            </h3>
                            <span
                                class="px-2 py-0.5 text-xs rounded-full whitespace-nowrap
                                {{ $package->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($package->status) }}
                            </span>
                        </div>

                        @if($package->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ $package->description }}
                        </p>
                        @endif

                        <div class="space-y-1 mb-4">
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                ₱{{ number_format($package->price, 2) }} <span
                                    class="text-sm font-normal text-gray-500">per head</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Base price for <strong>{{ $package->pax }}</strong> guests
                            </p>
                            <p class="text-sm font-medium text-blue-600">
                                Total: ₱{{ number_format($package->price * $package->pax, 2) }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <strong>{{ $package->items->count() }}</strong> menu items included
                            </p>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="button"
                                @click="openEditPackageModal({{ $package->id }}, '{{ addslashes($package->name) }}', '{{ addslashes($package->description ?? '') }}', {{ $package->pax }})"
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                Edit Package
                            </button>

                            <form action="{{ route('caterer.packages.destroy', $package->id) }}" method="POST"
                                onsubmit="return confirm('Delete this package?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">No packages</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first package.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Category</h2>
            <form action="{{ route('caterer.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Category Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Description (optional)"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" rows="3"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('categoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Save Category
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
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" rows="3"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editCategoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="itemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Menu Item</h2>
            <form action="{{ route('caterer.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" id="modalCategoryId">
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Item Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Description (optional)"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" rows="3"></textarea>
                    <input type="number" name="price" step="0.01" min="0" placeholder="Price per serving"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                    <select name="status" class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('itemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Menu Item</h2>
            <form id="editItemForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <input type="text" name="name" id="editName" placeholder="Item Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" id="editDescription" placeholder="Description (optional)"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" rows="3"></textarea>
                    <input type="number" name="price" id="editPrice" step="0.01" min="0" placeholder="Price per serving"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Change Image (optional)
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    </div>
                    <select name="status" id="editStatus"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editItemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Package Modal -->
    <div id="packageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Create Package</h2>
            <form action="{{ route('caterer.packages.store') }}" method="POST" enctype="multipart/form-data"
                x-data="packagePriceCalculator()">
                @csrf
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Package Name"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <textarea name="description" placeholder="Package Description (optional)"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" rows="3"></textarea>

                    <!-- Auto-calculated price display -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Package Price per
                                Head:</span>
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400"
                                x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Price automatically calculated from selected menu items
                        </p>
                    </div>

                    <input type="number" name="pax" placeholder="Number of guests" min="1" x-model="pax"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200" required>

                    <!-- Total package cost display -->
                    <div x-show="pax > 0"
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Package
                                Cost:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400"
                                x-text="'₱' + (calculatedPrice * pax).toFixed(2)"></span>
                        </div>
                    </div>

                    <input type="file" name="image" accept="image/*"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">

                    <h3 class="font-semibold mt-4 text-gray-800 dark:text-gray-200">Select Menu Items</h3>
                    <div class="max-h-60 overflow-y-auto border rounded-lg p-3 dark:border-gray-700">
                        @foreach($categories as $category)
                        @if($category->items->count() > 0)
                        <p class="font-medium text-sm mt-2 text-gray-600 dark:text-gray-400 border-b pb-1">
                            {{ $category->name }}</p>
                        @foreach($category->items as $item)
                        <label
                            class="flex items-center gap-2 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded cursor-pointer">
                            <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                                data-price="{{ $item->price }}" @change="updatePrice()"
                                class="rounded menu-item-checkbox">
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $item->name }} -
                                ₱{{ number_format($item->price, 2) }}</span>
                        </label>
                        @endforeach
                        @endif
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div x-show="selectedItems.length > 0"
                        class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-sm">
                        <h4 class="font-semibold mb-2 text-gray-800 dark:text-gray-200">Price Breakdown:</h4>
                        <div class="space-y-1 text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between">
                                <span>Food Cost:</span>
                                <span x-text="'₱' + foodCost.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Labor & Utilities (20%):</span>
                                <span x-text="'₱' + (foodCost * 0.20).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Equipment & Transport (10%):</span>
                                <span x-text="'₱' + (foodCost * 0.10).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Profit Margin (25%):</span>
                                <span x-text="'₱' + (foodCost * 0.25).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between font-bold border-t pt-1 mt-1">
                                <span>Total per Head:</span>
                                <span x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('packageModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">Cancel</button>
                    <button type="submit" :disabled="selectedItems.length === 0"
                        :class="selectedItems.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 rounded-lg text-white transition-colors">
                        Save Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div id="editPackageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Package</h2>
            <form id="editPackageForm" method="POST" enctype="multipart/form-data"
                x-data="editPackagePriceCalculator()">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <input type="text" name="name" id="editPackageName"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Package Name" required>

                    <textarea name="description" id="editPackageDescription"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Package Description (optional)" rows="3"></textarea>

                    <!-- Auto-calculated price display -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Package Price per
                                Head:</span>
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400"
                                x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Price automatically calculated from selected menu items
                        </p>
                    </div>

                    <input type="number" name="pax" id="editPackagePax" min="1" x-model="pax"
                        class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Number of guests" required>

                    <!-- Total package cost display -->
                    <div x-show="pax > 0"
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Package
                                Cost:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400"
                                x-text="'₱' + (calculatedPrice * pax).toFixed(2)"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Package Image (optional)
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    </div>

                    <!-- Menu Items Section -->
                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Menu Items</h3>

                        <!-- Selected Items Display -->
                        <div id="editSelectedItemsContainer" class="mb-3 space-y-2">
                            <p class="text-sm text-gray-500 italic">Loading items...</p>
                        </div>

                        <!-- Available Items to Add -->
                        <div class="border rounded-lg p-3 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Available Menu Items
                            </p>
                            <div class="max-h-48 overflow-y-auto space-y-1">
                                @foreach($categories as $category)
                                @if($category->items->count() > 0)
                                <p class="font-medium text-xs mt-2 text-gray-600 dark:text-gray-400 border-b pb-1">
                                    {{ $category->name }}
                                </p>
                                @foreach($category->items as $item)
                                <label
                                    class="flex items-center gap-2 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded cursor-pointer">
                                    <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                                        data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}"
                                        class="rounded edit-menu-item-checkbox"
                                        @change="updateEditPrice(); updateEditSelectedItemsDisplay()">
                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->name }} - ₱{{ number_format($item->price, 2) }}
                                    </span>
                                </label>
                                @endforeach
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div x-show="selectedEditItems.length > 0"
                        class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-sm">
                        <h4 class="font-semibold mb-2 text-gray-800 dark:text-gray-200">Price Breakdown:</h4>
                        <div class="space-y-1 text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between">
                                <span>Food Cost:</span>
                                <span x-text="'₱' + foodCost.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Labor & Utilities (20%):</span>
                                <span x-text="'₱' + (foodCost * 0.20).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Equipment & Transport (10%):</span>
                                <span x-text="'₱' + (foodCost * 0.10).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Profit Margin (25%):</span>
                                <span x-text="'₱' + (foodCost * 0.25).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between font-bold border-t pt-1 mt-1">
                                <span>Total per Head:</span>
                                <span x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeEditPackageModal()"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="selectedEditItems.length === 0"
                        :class="selectedEditItems.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                        class="px-4 py-2 rounded-lg text-white transition-colors">
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Package Price Calculator for Create Modal
        function packagePriceCalculator() {
            return {
                selectedItems: [],
                foodCost: 0,
                calculatedPrice: 0,
                pax: 1,

                updatePrice() {
                    const checkboxes = document.querySelectorAll('.menu-item-checkbox:checked');
                    this.selectedItems = Array.from(checkboxes).map(cb => ({
                        id: cb.value,
                        price: parseFloat(cb.dataset.price)
                    }));

                    this.foodCost = this.selectedItems.reduce((sum, item) => sum + item.price, 0);

                    const laborUtilities = this.foodCost * 0.20;
                    const equipmentTransport = this.foodCost * 0.10;
                    const profitMargin = this.foodCost * 0.25;

                    const total = this.foodCost + laborUtilities + equipmentTransport + profitMargin;
                    this.calculatedPrice = Math.round(total / 5) * 5; // Round to nearest 5
                }
            }
        }

        // Package Price Calculator for Edit Modal (FIXED)
        function editPackagePriceCalculator() {
            return {
                selectedEditItems: [],
                foodCost: 0,
                calculatedPrice: 0,
                pax: 1,

                init() {
                    // Listen for checkbox changes
                    this.$nextTick(() => {
                        this.updateEditPrice();
                    });
                },

                updateEditPrice() {
                    const checkboxes = document.querySelectorAll('.edit-menu-item-checkbox:checked');
                    this.selectedEditItems = Array.from(checkboxes).map(cb => ({
                        id: cb.value,
                        price: parseFloat(cb.dataset.itemPrice) || 0
                    }));

                    this.foodCost = this.selectedEditItems.reduce((sum, item) => sum + item.price, 0);

                    const laborUtilities = this.foodCost * 0.20;
                    const equipmentTransport = this.foodCost * 0.10;
                    const profitMargin = this.foodCost * 0.25;

                    const total = this.foodCost + laborUtilities + equipmentTransport + profitMargin;
                    this.calculatedPrice = Math.round(total / 5) * 5; // Round to nearest 5
                },

                updateEditSelectedItemsDisplay() {
                    updateEditSelectedItemsDisplay();
                }
            }
        }

        // Edit Package Modal Functions (FIXED)
        function openEditPackageModal(id, name, description, pax) {
            // Set form values
            document.getElementById('editPackageName').value = name;
            document.getElementById('editPackageDescription').value = description || '';
            document.getElementById('editPackagePax').value = pax;

            // Set form action
            document.getElementById('editPackageForm').action = `/caterer/packages/${id}`;

            // Clear all edit checkboxes first
            document.querySelectorAll('.edit-menu-item-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Show loading state
            const container = document.getElementById('editSelectedItemsContainer');
            container.innerHTML = '<p class="text-sm text-gray-500 italic">Loading items...</p>';

            // Open modal
            document.getElementById('editPackageModal').classList.remove('hidden');

            // Fetch and set current package items
            fetch(`/caterer/packages/${id}/items`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch package items');
                    }
                    return response.json();
                })
                .then(data => {
                    // Check the boxes for items in this package
                    if (data.items && Array.isArray(data.items)) {
                        data.items.forEach(itemId => {
                            const checkbox = document.querySelector(
                                `.edit-menu-item-checkbox[value="${itemId}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }

                    // Trigger change event to update Alpine.js price calculation
                    setTimeout(() => {
                        const firstCheckbox = document.querySelector('.edit-menu-item-checkbox');
                        if (firstCheckbox) {
                            const event = new Event('change', {
                                bubbles: true
                            });
                            firstCheckbox.dispatchEvent(event);
                        }

                        // Update the selected items display
                        updateEditSelectedItemsDisplay();
                    }, 100);
                })
                .catch(error => {
                    console.error('Error fetching package items:', error);
                    container.innerHTML =
                        '<p class="text-sm text-red-500">Error loading items. Please refresh and try again.</p>';
                });
        }

        function closeEditPackageModal() {
            document.getElementById('editPackageModal').classList.add('hidden');
        }

        function updateEditSelectedItemsDisplay() {
            const container = document.getElementById('editSelectedItemsContainer');
            const checkedBoxes = document.querySelectorAll('.edit-menu-item-checkbox:checked');

            if (checkedBoxes.length === 0) {
                container.innerHTML =
                    '<p class="text-sm text-gray-500 italic">No items selected. Please select at least one menu item.</p>';
                return;
            }

            let html = '<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 space-y-2">';
            html += '<p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Selected Items (' +
                checkedBoxes.length + '):</p>';

            checkedBoxes.forEach(checkbox => {
                const itemName = checkbox.dataset.itemName;
                const itemPrice = parseFloat(checkbox.dataset.itemPrice).toFixed(2);
                const itemId = checkbox.value;

                html += `
            <div class="flex items-center justify-between bg-white dark:bg-gray-600 rounded px-3 py-2">
                <span class="text-sm text-gray-800 dark:text-gray-200">
                    ${itemName} - ₱${itemPrice}
                </span>
                <button type="button" 
                        onclick="removeEditMenuItem(${itemId})"
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        function removeEditMenuItem(itemId) {
            const checkbox = document.querySelector(`.edit-menu-item-checkbox[value="${itemId}"]`);
            if (checkbox) {
                checkbox.checked = false;
                const event = new Event('change', {
                    bubbles: true
                });
                checkbox.dispatchEvent(event);
                updateEditSelectedItemsDisplay();
            }
        }

    </script>

    <!-- JavaScript Functions -->
    <script>
        function menuManager() {
            return {
                selectedCategory: 'all',
                loading: false,

                openModal(modalId) {
                    document.getElementById(modalId).classList.remove('hidden');
                },

                closeModal(modalId) {
                    document.getElementById(modalId).classList.add('hidden');
                },

                openItemModal(categoryId) {
                    document.getElementById('modalCategoryId').value = categoryId;
                    this.openModal('itemModal');
                },

                openEditItemModal(id, name, description, price, status) {
                    document.getElementById('editName').value = name;
                    document.getElementById('editDescription').value = description;
                    document.getElementById('editPrice').value = price;
                    document.getElementById('editStatus').value = status;

                    document.getElementById('editItemForm').action = `/caterer/menu-items/${id}`;
                    this.openModal('editItemModal');
                },

                openEditCategoryModal(id, name, description) {
                    document.getElementById('editCategoryName').value = name;
                    document.getElementById('editCategoryDescription').value = description;

                    document.getElementById('editCategoryForm').action = `/caterer/categories/${id}`;
                    this.openModal('editCategoryModal');
                }
            }
        }

        // Global functions for non-Alpine.js parts
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });

    </script>
</x-app-layout>

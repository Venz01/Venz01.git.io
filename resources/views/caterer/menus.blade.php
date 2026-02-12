<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Menu Management') }}
        </h2>
    </x-slot>

    <div x-data="menuManager()" class="max-w-7xl mx-auto py-4 sm:py-6 px-3 sm:px-4 lg:px-8 space-y-4 sm:space-y-6">

        <!-- Loading Overlay -->
        <div x-show="loading" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 flex items-center gap-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-sm sm:text-base text-gray-700 dark:text-gray-200">Processing...</span>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="confirmModal.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 px-4"
            style="display: none;">
            <div x-show="confirmModal.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-5 sm:p-6">

                <!-- Icon -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full" :class="{
                         'bg-red-100 dark:bg-red-900/30': confirmModal.type === 'danger',
                         'bg-yellow-100 dark:bg-yellow-900/30': confirmModal.type === 'warning',
                         'bg-blue-100 dark:bg-blue-900/30': confirmModal.type === 'info'
                     }">
                    <svg class="w-6 h-6" :class="{
                             'text-red-600 dark:text-red-400': confirmModal.type === 'danger',
                             'text-yellow-600 dark:text-yellow-400': confirmModal.type === 'warning',
                             'text-blue-600 dark:text-blue-400': confirmModal.type === 'info'
                         }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="confirmModal.type === 'danger' || confirmModal.type === 'warning'"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        <path x-show="confirmModal.type === 'info'" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-2"
                    x-text="confirmModal.title"></h3>

                <!-- Message -->
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6" x-text="confirmModal.message"></p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="confirmModal.show = false" type="button"
                        class="w-full sm:flex-1 px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium order-2 sm:order-1">
                        Cancel
                    </button>
                    <button @click="confirmAction()" type="button"
                        class="w-full sm:flex-1 px-4 py-2.5 rounded-lg font-medium transition-colors order-1 sm:order-2"
                        :class="{
                                'bg-red-600 hover:bg-red-700 text-white': confirmModal.type === 'danger' && confirmModal.action,
                                'bg-yellow-600 hover:bg-yellow-700 text-white': confirmModal.type === 'warning',
                                'bg-blue-600 hover:bg-blue-700 text-white': confirmModal.type === 'info' && confirmModal.action,
                                'bg-gray-400 cursor-not-allowed text-white': !confirmModal.action
                            }" :disabled="!confirmModal.action" x-text="confirmModal.confirmText || 'Confirm'">
                    </button>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGATION -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'packages'" 
                    :class="activeTab === 'packages' ? 
                        'border-purple-500 text-purple-600 dark:text-purple-400' : 
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Packages & Menu Items
                </button>

                <button @click="activeTab = 'display'" 
                    :class="activeTab === 'display' ? 
                        'border-purple-500 text-purple-600 dark:text-purple-400' : 
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Customer Display Menus
                </button>
            </nav>
        </div>

        <!-- PACKAGES TAB CONTENT -->
        <div x-show="activeTab === 'packages'" x-transition class="space-y-6">
            
            <!-- Top Actions for Packages Tab -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="openModal('categoryModal')"
                        class="bg-green-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 shadow-sm text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Category</span>
                    </button>
                    <button @click="openModal('packageModal')"
                        class="bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Create Package</span>
                    </button>

                    <!-- BULK SELECT TOGGLE BUTTON -->
                    <button @click="toggleBulkMode()"
                        :class="bulkMode ? 'bg-purple-600 hover:bg-purple-700' : 'bg-gray-600 hover:bg-gray-700'"
                        class="text-white px-3 sm:px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span x-text="bulkMode ? 'Exit Bulk Select' : 'Bulk Select'"></span>
                    </button>
                </div>

                <!-- Filter -->
                <div class="w-full lg:w-auto">
                    <select x-model="selectedCategory"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- BULK ACTION BAR -->
            <div x-show="bulkMode && (selectedCategories.length > 0 || selectedItems.length > 0)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 sticky top-4 z-40">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 md:gap-4">
                    <!-- Selection Info -->
                    <div class="flex items-center gap-3 sm:gap-4 text-white w-full md:w-auto">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold text-sm sm:text-base">
                                <span x-text="selectedCategories.length + selectedItems.length"></span> selected
                            </span>
                        </div>
                        <span class="text-purple-200 text-xs sm:text-sm">
                            (<span x-text="selectedCategories.length"></span> categories,
                            <span x-text="selectedItems.length"></span> items)
                        </span>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="flex gap-2 flex-wrap w-full md:w-auto">
                        <button @click="clearAllSelections()"
                            class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors text-xs sm:text-sm font-medium">
                            Clear All
                        </button>

                        <!-- Change Status Dropdown -->
                        <div class="relative" x-data="{ showStatusMenu: false }">
                            <button @click="showStatusMenu = !showStatusMenu" :disabled="selectedItems.length === 0"
                                :class="selectedItems.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white/30'"
                                class="px-3 sm:px-4 py-1.5 bg-white/20 text-white rounded-lg transition-colors text-xs sm:text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span class="hidden sm:inline">Change Status</span>
                                <span class="sm:hidden">Status</span>
                            </button>
                            <div x-show="showStatusMenu" @click.away="showStatusMenu = false" x-transition
                                class="absolute right-0 mt-2 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                                <button @click="bulkChangeStatus('available'); showStatusMenu = false"
                                    class="w-full px-3 sm:px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 flex items-center gap-2 text-xs sm:text-sm">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    Set as Available
                                </button>
                                <button @click="bulkChangeStatus('unavailable'); showStatusMenu = false"
                                    class="w-full px-3 sm:px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 flex items-center gap-2 text-xs sm:text-sm">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    Set as Unavailable
                                </button>
                            </div>
                        </div>

                        <!-- Bulk Delete -->
                        <button @click="bulkDelete()"
                            class="px-3 sm:px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-xs sm:text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="hidden sm:inline">Delete Selected</span>
                            <span class="sm:hidden">Delete</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- CATEGORIES SECTION — NOW WITH COLLAPSIBLE TOGGLE             -->
            <!-- ============================================================ -->
            @foreach($categories as $category)
            <div x-show="selectedCategory === 'all' || selectedCategory == '{{ $category->id }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-data="{ collapsed: false }"
                class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                <!-- Category Header -->
                <div class="p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">

                        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto min-w-0 flex-1">
                            <!-- CATEGORY CHECKBOX (bulk mode) -->
                            <div x-show="bulkMode" x-transition class="flex items-center flex-shrink-0">
                                <input type="checkbox" :checked="selectedCategories.includes({{ $category->id }})"
                                    @change="toggleCategorySelection({{ $category->id }})"
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500 cursor-pointer">
                            </div>

                            <!-- Collapse Toggle Button (wraps icon + title) -->
                            <button type="button"
                                @click="collapsed = !collapsed"
                                class="flex items-center gap-2 sm:gap-3 group min-w-0 flex-1 text-left">

                                <!-- Chevron Icon -->
                                <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                                        :class="collapsed ? '-rotate-90' : 'rotate-0'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>

                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                        {{ $category->name }}
                                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-normal ml-1.5">
                                            ({{ $category->items->count() }} items)
                                        </span>
                                    </h3>
                                    @if($category->description)
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                        {{ $category->description }}
                                    </p>
                                    @endif
                                </div>
                            </button>
                        </div>

                        <!-- Action Buttons (Edit / Delete / Add Item) -->
                        <div x-show="!bulkMode" class="flex gap-1.5 sm:gap-2 flex-wrap w-full sm:w-auto justify-end flex-shrink-0">
                            <button type="button"
                                @click.stop="openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
                                class="flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors text-xs sm:text-sm font-medium px-2 sm:px-3 py-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="hidden xs:inline">Edit</span>
                            </button>

                            <button type="button"
                                @click.stop="showDeleteConfirm('{{ route('caterer.categories.destroy', $category->id) }}', 'category', '{{ addslashes($category->name) }}', {{ $category->items->count() }})"
                                class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors text-xs sm:text-sm font-medium px-2 sm:px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="hidden xs:inline">Delete</span>
                            </button>

                            <button type="button" @click.stop="openItemModal({{ $category->id }})"
                                class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition-colors font-medium flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline">Add Item</span>
                                <span class="sm:hidden">Add</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Items List — Collapsible -->
                <div x-show="!collapsed"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="border-t border-gray-100 dark:border-gray-700 px-4 sm:px-6 pb-4 sm:pb-6 pt-4 space-y-3 sm:space-y-4">

                    @forelse($category->items as $item)
                    <div class="flex flex-col xs:flex-row items-start gap-3 sm:gap-4 border-b border-gray-100 dark:border-gray-700 pb-3 sm:pb-4 last:border-b-0 rounded-lg transition-all"
                        :class="selectedItems.includes({{ $item->id }}) ? 'bg-purple-50 dark:bg-purple-900/10 border-l-4 border-l-purple-500 pl-2' : ''">

                        <div class="flex items-start gap-3 w-full xs:w-auto xs:flex-shrink-0">
                            <!-- ITEM CHECKBOX (bulk mode) -->
                            <div x-show="bulkMode" x-transition class="flex-shrink-0 pt-1">
                                <input type="checkbox" :checked="selectedItems.includes({{ $item->id }})"
                                    @change="toggleItemSelection({{ $item->id }})"
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500 cursor-pointer">
                            </div>

                            <!-- Image -->
                            @if($item->image_path)
                            <img src="{{ $item->image_path }}"
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm flex-shrink-0"
                                alt="{{ $item->name }}">
                            @else
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-lg text-xs text-gray-500 dark:text-gray-400 font-medium shadow-sm flex-shrink-0">
                                No Image
                            </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0 w-full xs:w-auto">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h4 class="font-semibold text-sm sm:text-base text-gray-800 dark:text-gray-200">
                                    {{ $item->name }}</h4>
                                <span
                                    class="px-2 sm:px-2.5 py-0.5 text-xs font-medium rounded-full whitespace-nowrap {{ $item->status === 'available' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            @if($item->description)
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">
                                {{ $item->description }}</p>
                            @endif

                            <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100">
                                ₱{{ number_format($item->price, 2) }}<span
                                    class="text-xs font-normal text-gray-500 dark:text-gray-400">/serving</span>
                            </p>
                        </div>

                        <!-- Actions -->
                        <div x-show="!bulkMode"
                            class="flex gap-2 flex-shrink-0 w-full xs:w-auto justify-end xs:justify-start">
                            <button type="button"
                                @click="openEditItemModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->description ?? '') }}', {{ $item->price }}, '{{ $item->status }}')"
                                class="p-1.5 sm:p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                title="Edit item">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <button type="button"
                                @click="showDeleteConfirm('{{ route('caterer.menu-items.destroy', $item->id) }}', 'item', '{{ addslashes($item->name) }}')"
                                class="p-1.5 sm:p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                title="Delete item">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 sm:py-8">
                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 dark:text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 italic">No items in this category yet.</p>
                        <button @click="openItemModal({{ $category->id }})"
                            class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                            + Add your first item
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach

            <!-- Packages Section -->
            <div class="mt-8 sm:mt-12">
                <div class="flex flex-col xs:flex-row items-start xs:items-center justify-between gap-3 mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200">Packages</h2>
                    <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $packages->count() }} total
                        packages</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @forelse($packages as $package)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Package Image -->
                        @if($package->image_path)
                        <img src="{{ $package->image_path }}" class="h-40 sm:h-48 w-full object-cover"
                            alt="{{ $package->name }}">
                        @else
                        <div
                            class="h-40 sm:h-48 w-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 dark:text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        @endif

                        <!-- Package Content -->
                        <div class="p-4 sm:p-5">
                            <div class="flex justify-between items-start gap-2 mb-2">
                                <h3
                                    class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-200 leading-tight line-clamp-1 flex-1">
                                    {{ $package->name }}
                                </h3>
                                <span
                                    class="px-2 sm:px-2.5 py-0.5 text-xs font-medium rounded-full whitespace-nowrap {{ $package->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </div>

                            @if($package->description)
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $package->description }}
                            </p>
                            @endif

                            <div class="space-y-1.5 mb-4">
                                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format($package->price, 2) }}
                                    <span class="text-xs sm:text-sm font-normal text-gray-500 dark:text-gray-400">per
                                        head</span>
                                </p>
                                <div
                                    class="flex items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <strong>{{ $package->pax }}</strong> guests
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <strong>{{ $package->items->count() }}</strong> items
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm font-semibold text-blue-600 dark:text-blue-400">
                                    Total: ₱{{ number_format($package->price * $package->pax, 2) }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-3 sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                                <button type="button"
                                    @click="openEditPackageModal(
                                        {{ $package->id }}, 
                                        '{{ addslashes($package->name) }}', 
                                        '{{ addslashes($package->description ?? '') }}', 
                                        {{ $package->pax }},
                                        {{ json_encode($package->dietary_tags ?? []) }}
                                    )"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 font-medium text-xs sm:text-sm transition-colors">
                                    
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>   

                                <button type="button"
                                    @click="showDeleteConfirm('{{ route('caterer.packages.destroy', $package->id) }}', 'package', '{{ addslashes($package->name) }}')"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 font-medium text-xs sm:text-sm transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div
                        class="col-span-full text-center py-12 sm:py-16 bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 dark:text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-300">No packages yet</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first package.
                        </p>
                        <button @click="openModal('packageModal')"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Package
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- DISPLAY MENUS TAB CONTENT -->
        <div x-show="activeTab === 'display'" x-transition class="space-y-6">
            
            <!-- Display Menu Header with Add Button -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Customer Display Menus</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Menus shown to customers on your profile page (separate from package items)
                    </p>
                </div>
                <button type="button" @click="openDisplayMenuModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Display Menu
                </button>
            </div>

            @if(isset($displayMenus) && $displayMenus->count() > 0)
            <div class="space-y-6">
                @foreach($displayMenus as $category => $menus)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                            </path>
                        </svg>
                        {{ $category }}
                        <span class="text-sm text-gray-500 dark:text-gray-400 font-normal">({{ count($menus) }} items)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($menus as $menu)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            @if($menu->image_path)
                            <img src="{{ $menu->image_path }}" alt="{{ $menu->name }}"
                                class="w-full h-40 object-cover">
                            @else
                            <div class="w-full h-40 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 flex-1">{{ $menu->name }}</h4>
                                    <span
                                        class="text-xs px-2 py-1 rounded-full flex-shrink-0 {{ $menu->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                        {{ ucfirst($menu->status) }}
                                    </span>
                                </div>
                                @if($menu->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                    {{ $menu->description }}
                                </p>
                                @endif
                                @if($menu->price)
                                <p class="text-base font-bold text-purple-600 dark:text-purple-400 mb-3">
                                    ₱{{ number_format($menu->price, 2) }}
                                </p>
                                @endif
                                <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <button
                                        @click="openEditDisplayMenuModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', '{{ $menu->category }}', '{{ addslashes($menu->description ?? '') }}', {{ $menu->price ?? 'null' }}, '{{ $menu->status }}')"
                                        class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 font-medium text-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button
                                        @click="showDeleteConfirm('{{ route('caterer.display-menus.destroy', $menu->id) }}', 'display_menu', '{{ addslashes($menu->name) }}')"
                                        class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 font-medium text-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-gray-600 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-300 mb-2">No Display Menus Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    Create showcase menus that will be displayed to customers on your profile page.
                </p>
                <button @click="openDisplayMenuModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Your First Display Menu
                </button>
            </div>
            @endif
        </div>

    </div>

    <!-- Hidden form for deletions -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Category Modal -->
    <div id="categoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Category</h2>
            <form action="{{ route('caterer.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Category Name"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <textarea name="description" placeholder="Description (optional)"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        rows="3"></textarea>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('categoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 font-medium transition-colors">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Category</h2>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <input type="text" name="name" id="editCategoryName"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <textarea name="description" id="editCategoryDescription"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        rows="3"></textarea>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editCategoryModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium transition-colors">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="itemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Menu Item</h2>
            <form action="{{ route('caterer.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" id="itemCategoryId">
                <div class="space-y-3">
                    <input type="text" name="name" placeholder="Item Name"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <textarea name="description" placeholder="Description (optional)"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        rows="3"></textarea>
                    <input type="number" name="price" step="0.01" min="0" placeholder="Price per serving"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <select name="status"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('itemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 font-medium transition-colors">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Menu Item</h2>
            <form id="editItemForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <input type="text" name="name" id="editItemName" placeholder="Item Name"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <textarea name="description" id="editItemDescription" placeholder="Description (optional)"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        rows="3"></textarea>
                    <input type="number" name="price" id="editItemPrice" step="0.01" min="0"
                        placeholder="Price per serving"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Change Image (optional)
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Leave empty to keep current image</p>
                    </div>
                    <select name="status" id="editItemStatus"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editItemModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium transition-colors">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Package Modal -->
    <div id="packageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Create Package</h2>
            <form action="{{ route('caterer.packages.store') }}" method="POST" enctype="multipart/form-data"
                x-data="packagePriceCalculator()">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Package Name"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <textarea name="description" placeholder="Package Description (optional)"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        rows="3"></textarea>

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
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>

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
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Select Menu Items</h3>
                    <div class="max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                        @foreach($categories as $category)
                        @if($category->items->count() > 0)
                        <p
                            class="font-medium text-xs mt-2 first:mt-0 text-gray-600 dark:text-gray-400 border-b pb-1 mb-1">
                            {{ $category->name }}</p>
                        @foreach($category->items as $item)
                        <label
                            class="flex items-center gap-2 py-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded cursor-pointer">
                            <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                                data-price="{{ $item->price }}" @change="updatePrice()"
                                class="rounded text-green-600 focus:ring-green-500 menu-item-checkbox">
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $item->name }} -
                                ₱{{ number_format($item->price, 2) }}</span>
                        </label>
                        @endforeach
                        @endif
                        @endforeach
                    </div>

                    <!-- Dietary Preferences & Allergy Tags -->
                    <div class="mt-4">
                       @include('admin.partials.package-dietary-tags', ['selectedTags' => []])
                    </div>

                    <!-- Price Breakdown -->
                    <div x-show="selectedItems.length > 0"
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-sm">
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
                            <div
                                class="flex justify-between font-bold border-t border-gray-200 dark:border-gray-600 pt-1.5 mt-1.5">
                                <span>Total per Head:</span>
                                <span x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('packageModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">Cancel</button>
                    <button type="submit" :disabled="selectedItems.length === 0"
                        :class="selectedItems.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 rounded-lg text-white transition-colors font-medium">
                        Save Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div id="editPackageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Package</h2>
            <form id="editPackageForm" method="POST" enctype="multipart/form-data"
                x-data="editPackagePriceCalculator()">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <input type="text" name="name" id="editPackageName"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Package Name" required>

                    <textarea name="description" id="editPackageDescription"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Package Image (optional)
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 dark:bg-gray-700 dark:text-gray-200 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Leave empty to keep current image</p>
                    </div>

                    <!-- Menu Items Section -->
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-2">Menu Items</h3>

                        <!-- Selected Items Display -->
                        <div id="editSelectedItemsContainer" class="mb-3 space-y-2">
                            <p class="text-sm text-gray-500 italic">Loading items...</p>
                        </div>

                        <!-- Available Items to Add -->
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Available Menu Items
                            </p>

                            <!-- Dietary Preferences & Allergy Tags -->
                            <div class="mt-4">
                                @include('admin.partials.package-dietary-tags', ['selectedTags' => []])
                            </div>

                            <div class="max-h-48 overflow-y-auto space-y-1">
                                @foreach($categories as $category)
                                @if($category->items->count() > 0)
                                <p
                                    class="font-medium text-xs mt-2 first:mt-0 text-gray-600 dark:text-gray-400 border-b pb-1 mb-1">
                                    {{ $category->name }}
                                </p>
                                @foreach($category->items as $item)
                                <label
                                    class="flex items-center gap-2 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded cursor-pointer">
                                    <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                                        data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}"
                                        class="rounded text-blue-600 focus:ring-blue-500 edit-menu-item-checkbox"
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
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-sm">
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
                            <div
                                class="flex justify-between font-bold border-t border-gray-200 dark:border-gray-600 pt-1.5 mt-1.5">
                                <span>Total per Head:</span>
                                <span x-text="'₱' + calculatedPrice.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditPackageModal()"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="selectedEditItems.length === 0"
                        :class="selectedEditItems.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                        class="px-4 py-2 rounded-lg text-white transition-colors font-medium">
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Menu Modal -->
    <div id="displayMenuModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 id="displayMenuModalTitle" class="text-xl font-bold text-gray-800 dark:text-gray-200">Add Display Menu</h3>
                <button type="button" onclick="closeDisplayMenuModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="displayMenuForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="displayMenuFormMethod"></div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="display_menu_name" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200"
                            placeholder="e.g., Grilled Chicken">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="display_menu_category_select" required
                            onchange="toggleNewCategoryInput(this)"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                            <option value="">Select Category</option>
                            @if(isset($displayCategories) && count($displayCategories) > 0)
                                @foreach($displayCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            @endif
                            <option value="__new__">+ Add New Category</option>
                        </select>
                        
                        <input type="hidden" name="category" id="newDisplayCategory">
                        
                        <input type="text" id="newDisplayCategoryInput" 
                            class="hidden w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent mt-2 dark:bg-gray-700 dark:text-gray-200"
                            placeholder="Enter new category name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" id="display_menu_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200"
                            placeholder="Brief description of the item"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">₱</span>
                            <input type="number" name="price" id="display_menu_price" step="0.01" min="0" required
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                        <input type="file" name="image" id="display_menu_image" accept="image/jpeg,image/jpg,image/png"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, JPEG, PNG (Max: 2MB)</p>

                        <div id="display_menu_image_preview" class="mt-2 hidden">
                            <img id="display_menu_preview_img" src="" alt="Preview"
                                class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="display_menu_status" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                            <option value="active">Active (Visible to customers)</option>
                            <option value="inactive">Inactive (Hidden from customers)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeDisplayMenuModal()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <span id="displayMenuSubmitText">Add Display Menu</span>
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
                    this.calculatedPrice = Math.round(total / 5) * 5;
                }
            }
        }

        // Package Price Calculator for Edit Modal
        function editPackagePriceCalculator() {
            return {
                selectedEditItems: [],
                foodCost: 0,
                calculatedPrice: 0,
                pax: 1,

                init() {
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
                    this.calculatedPrice = Math.round(total / 5) * 5;
                }
            }
        }

        function openEditPackageModal(packageId, name, description, pax, dietaryTags = []) {
            document.getElementById('editPackageName').value = name;
            document.getElementById('editPackageDescription').value = description;
            document.getElementById('editPackagePax').value = pax;
            document.getElementById('editPackageForm').action = `/caterer/packages/${packageId}`;

            document.querySelectorAll('.dietary-tag-checkbox').forEach(checkbox => {
                const tagValue = checkbox.value;
                checkbox.checked = dietaryTags.includes(tagValue);
                toggleDietaryTag(checkbox);
            });

            document.getElementById('editPackageModal').classList.remove('hidden');

            const container = document.getElementById('editSelectedItemsContainer');
            container.innerHTML = '<p class="text-sm text-gray-500">Loading items...</p>';

            fetch(`/caterer/packages/${packageId}/items`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch package items');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.items && Array.isArray(data.items)) {
                        data.items.forEach(itemId => {
                            const checkbox = document.querySelector(
                                `.edit-menu-item-checkbox[value="${itemId}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }

                    setTimeout(() => {
                        const firstCheckbox = document.querySelector('.edit-menu-item-checkbox');
                        if (firstCheckbox) {
                            const event = new Event('change', {
                                bubbles: true
                            });
                            firstCheckbox.dispatchEvent(event);
                        }
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

        // Main Alpine.js component
        function menuManager() {
            return {
                activeTab: 'packages',
                selectedCategory: 'all',
                loading: false,

                // BULK ACTION PROPERTIES
                bulkMode: false,
                selectedCategories: [],
                selectedItems: [],

                confirmModal: {
                    show: false,
                    title: '',
                    message: '',
                    type: 'danger',
                    confirmText: 'Delete',
                    action: null
                },

                openModal(modalId) {
                    document.getElementById(modalId).classList.remove('hidden');
                },

                showDeleteConfirm(url, type, name, itemCount = 0) {
                    let title, message, confirmText;

                    if (type === 'category') {
                        title = 'Delete Category?';
                        if (itemCount > 0) {
                            message =
                                `Cannot delete "${name}" - This category has ${itemCount} item(s). Please delete all items first before deleting the category.`;
                            confirmText = 'OK, Got It';
                            this.confirmModal.type = 'warning';
                            this.confirmModal.action = null;
                        } else {
                            message =
                                `Are you sure you want to delete the category "${name}"? This action cannot be undone.`;
                            confirmText = 'Delete Category';
                            this.confirmModal.type = 'danger';
                            this.confirmModal.action = url;
                        }
                    } else if (type === 'item') {
                        title = 'Delete Menu Item?';
                        message =
                            `Are you sure you want to delete "${name}"? This will remove it from all packages that include it. This action cannot be undone.`;
                        confirmText = 'Delete Item';
                        this.confirmModal.type = 'danger';
                        this.confirmModal.action = url;
                    } else if (type === 'package') {
                        title = 'Delete Package?';
                        message =
                            `Are you sure you want to delete the package "${name}"? This action cannot be undone.`;
                        confirmText = 'Delete Package';
                        this.confirmModal.type = 'danger';
                        this.confirmModal.action = url;
                    } else if (type === 'display_menu') {
                        title = 'Delete Display Menu?';
                        message =
                            `Are you sure you want to delete "${name}"? This will remove it from your customer-facing menu. This action cannot be undone.`;
                        confirmText = 'Delete Menu';
                        this.confirmModal.type = 'danger';
                        this.confirmModal.action = url;
                    }

                    this.confirmModal.show = true;
                    this.confirmModal.title = title;
                    this.confirmModal.message = message;
                    this.confirmModal.confirmText = confirmText;
                },

                // BULK ACTION METHODS
                toggleBulkMode() {
                    this.bulkMode = !this.bulkMode;
                    if (!this.bulkMode) {
                        this.clearAllSelections();
                    }
                },

                toggleCategorySelection(categoryId) {
                    const index = this.selectedCategories.indexOf(categoryId);
                    if (index > -1) {
                        this.selectedCategories.splice(index, 1);
                    } else {
                        this.selectedCategories.push(categoryId);
                    }
                },

                toggleItemSelection(itemId) {
                    const index = this.selectedItems.indexOf(itemId);
                    if (index > -1) {
                        this.selectedItems.splice(index, 1);
                    } else {
                        this.selectedItems.push(itemId);
                    }
                },

                clearAllSelections() {
                    this.selectedCategories = [];
                    this.selectedItems = [];
                },

                bulkChangeStatus(status) {
                    if (this.selectedItems.length === 0) {
                        alert('Please select at least one item to change status');
                        return;
                    }

                    this.confirmModal.show = true;
                    this.confirmModal.type = 'info';
                    this.confirmModal.title = 'Change Status';
                    this.confirmModal.message = `Set ${this.selectedItems.length} item(s) as ${status}?`;
                    this.confirmModal.confirmText = 'Change Status';
                    this.confirmModal.action = () => {
                        this.performBulkAction('change_status', status);
                    };
                },

                bulkDelete() {
                    const totalSelected = this.selectedCategories.length + this.selectedItems.length;
                    if (totalSelected === 0) {
                        alert('Please select items or categories to delete');
                        return;
                    }

                    let message = `Are you sure you want to delete ${totalSelected} item(s)?`;
                    if (this.selectedCategories.length > 0 && this.selectedItems.length > 0) {
                        message =
                            `Are you sure you want to delete ${this.selectedCategories.length} category(ies) and ${this.selectedItems.length} item(s)?`;
                    } else if (this.selectedCategories.length > 0) {
                        message = `Are you sure you want to delete ${this.selectedCategories.length} category(ies)?`;
                    } else {
                        message = `Are you sure you want to delete ${this.selectedItems.length} item(s)?`;
                    }

                    this.confirmModal.show = true;
                    this.confirmModal.type = 'danger';
                    this.confirmModal.title = 'Delete Selected Items';
                    this.confirmModal.message = message + ' This action cannot be undone.';
                    this.confirmModal.confirmText = 'Delete All';
                    this.confirmModal.action = () => {
                        this.performBulkAction('delete', null);
                    };
                },

                async performBulkAction(action, value = null) {
                    this.loading = true;

                    const formData = new FormData();
                    formData.append('category_ids', JSON.stringify(this.selectedCategories));
                    formData.append('item_ids', JSON.stringify(this.selectedItems));
                    formData.append('action', action);
                    if (value) formData.append('value', value);

                    try {
                        const response = await fetch('/caterer/bulk-action', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request');
                    } finally {
                        this.loading = false;
                    }
                },

                confirmAction() {
                    if (typeof this.confirmModal.action === 'function') {
                        this.confirmModal.action();
                        this.confirmModal.show = false;
                    } else if (this.confirmModal.action) {
                        const form = document.getElementById('deleteForm');
                        if (form) {
                            form.action = this.confirmModal.action;
                            form.submit();
                        }
                    } else {
                        this.confirmModal.show = false;
                    }
                },

                openItemModal(categoryId) {
                    document.getElementById('itemCategoryId').value = categoryId;
                    this.openModal('itemModal');
                },

                openEditItemModal(id, name, description, price, status) {
                    document.getElementById('editItemName').value = name;
                    document.getElementById('editItemDescription').value = description;
                    document.getElementById('editItemPrice').value = price;
                    document.getElementById('editItemStatus').value = status;
                    document.getElementById('editItemForm').action = `/caterer/menu-items/${id}`;
                    this.openModal('editItemModal');
                },

                openEditCategoryModal(id, name, description) {
                    document.getElementById('editCategoryName').value = name;
                    document.getElementById('editCategoryDescription').value = description;
                    document.getElementById('editCategoryForm').action = `/caterer/categories/${id}`;
                    this.openModal('editCategoryModal');
                },

                openEditDisplayMenuModal(id, name, category, description, price, status) {
                    document.getElementById('displayMenuModalTitle').textContent = 'Edit Display Menu';
                    document.getElementById('displayMenuSubmitText').textContent = 'Update Display Menu';
                    
                    document.getElementById('displayMenuForm').action = `/caterer/display-menus/${id}`;
                    document.getElementById('displayMenuFormMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    document.getElementById('display_menu_name').value = name;
                    document.getElementById('display_menu_description').value = description || '';
                    document.getElementById('display_menu_price').value = price || '';
                    document.getElementById('display_menu_status').value = status;

                    const categorySelect = document.getElementById('display_menu_category_select');
                    const newCategoryInput = document.getElementById('newDisplayCategory');

                    let categoryExists = false;
                    for (let option of categorySelect.options) {
                        if (option.value === category && option.value !== '__new__') {
                            categorySelect.value = category;
                            categoryExists = true;
                            break;
                        }
                    }

                    if (!categoryExists && category) {
                        categorySelect.value = '__new__';
                        document.getElementById('newDisplayCategoryInput').classList.remove('hidden');
                        document.getElementById('newDisplayCategoryInput').value = category;
                        document.getElementById('newDisplayCategoryInput').required = true;
                    } else {
                        document.getElementById('newDisplayCategoryInput').classList.add('hidden');
                        document.getElementById('newDisplayCategoryInput').required = false;
                    }

                    const imagePreview = document.getElementById('display_menu_image_preview');
                    if (imagePreview) {
                        imagePreview.classList.add('hidden');
                    }

                    document.getElementById('displayMenuModal').classList.remove('hidden');
                },

                openDisplayMenuModal() {
                    document.getElementById('displayMenuForm').reset();
                    document.getElementById('displayMenuModalTitle').textContent = 'Add Display Menu';
                    document.getElementById('displayMenuSubmitText').textContent = 'Add Display Menu';
                    document.getElementById('displayMenuForm').action = '{{ route("caterer.display-menus.store") }}';
                    document.getElementById('displayMenuFormMethod').innerHTML = '';

                    const categorySelect = document.getElementById('display_menu_category_select');
                    
                    categorySelect.value = '';
                    document.getElementById('newDisplayCategoryInput').classList.add('hidden');
                    document.getElementById('newDisplayCategoryInput').value = '';
                    document.getElementById('newDisplayCategoryInput').required = false;

                    const imagePreview = document.getElementById('display_menu_image_preview');
                    if (imagePreview) {
                        imagePreview.classList.add('hidden');
                    }

                    document.getElementById('displayMenuModal').classList.remove('hidden');
                }
            }
        }

        // Global functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function closeDisplayMenuModal() {
            document.getElementById('displayMenuModal').classList.add('hidden');
            document.getElementById('displayMenuForm').reset();

            const imagePreview = document.getElementById('display_menu_image_preview');
            if (imagePreview) {
                imagePreview.classList.add('hidden');
            }
        }

        function toggleNewCategoryInput(select) {
            const newCategoryInput = document.getElementById('newDisplayCategoryInput');
            const hiddenCategoryInput = document.getElementById('newDisplayCategory');
            
            if (select.value === '__new__') {
                newCategoryInput.classList.remove('hidden');
                newCategoryInput.required = true;
                newCategoryInput.value = '';
                hiddenCategoryInput.value = '';
            } else {
                newCategoryInput.classList.add('hidden');
                newCategoryInput.required = false;
                hiddenCategoryInput.value = select.value;
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    if (!modal.id.includes('confirm')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    if (!modal.id.includes('confirm')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });

        // Image preview on file select
        (function () {
            const imageInput = document.getElementById('display_menu_image');
            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.size > 2097152) {
                            alert('File size must be less than 2MB');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewImg = document.getElementById('display_menu_preview_img');
                            const imagePreview = document.getElementById('display_menu_image_preview');
                            if (previewImg && imagePreview) {
                                previewImg.src = e.target.result;
                                imagePreview.classList.remove('hidden');
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        })();

        // Handle category selection for display menu form
        document.addEventListener('DOMContentLoaded', function() {
            const displayMenuForm = document.getElementById('displayMenuForm');
            if (displayMenuForm) {
                displayMenuForm.addEventListener('submit', function(e) {
                    const categorySelect = document.getElementById('display_menu_category_select');
                    const newCategoryInput = document.getElementById('newDisplayCategory');
                    
                    if (categorySelect.value === '__new__') {
                        newCategoryInput.value = document.getElementById('newDisplayCategoryInput').value;
                    } else {
                        newCategoryInput.value = categorySelect.value;
                    }
                });
            }
        });
    </script>

    <style>
        @media (min-width: 475px) {
            .xs\:flex-row {
                flex-direction: row;
            }

            .xs\:w-auto {
                width: auto;
            }

            .xs\:flex-shrink-0 {
                flex-shrink: 0;
            }

            .xs\:inline {
                display: inline;
            }

            .xs\:items-center {
                align-items: center;
            }

            .xs\:justify-start {
                justify-content: flex-start;
            }
        }

        body.modal-open {
            overflow: hidden;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</x-app-layout>
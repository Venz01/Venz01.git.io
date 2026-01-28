<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Portfolio Gallery') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Showcase your best work with high-quality images of your events and dishes.") }}
        </p>
    </header>

    <!-- Upload Form -->
    <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
        <form method="post" action="{{ route('profile.portfolio.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <x-input-label for="image" :value="__('Upload Image')" />
                    <input type="file" id="image" name="image" accept="image/*" required
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG. Max 5MB. Recommended: 1200x800px</p>
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="title" :value="__('Image Title (Optional)')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      placeholder="e.g., Wedding Reception 2024" />
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mark as Featured</span>
                        </label>
                    </div>
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description (Optional)')" />
                    <textarea id="description" name="description" rows="2"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                              placeholder="Describe this event or dish..."></textarea>
                </div>

                <x-primary-button>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Upload Image') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Gallery Grid -->
    @if($user->portfolioImages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($user->portfolioImages as $image)
                <div class="relative group">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                        <img src="{{ $image->image_path }}" 
                             alt="{{ $image->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Image Info Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-center px-4">
                            @if($image->title)
                                <h4 class="text-white font-semibold mb-2">{{ $image->title }}</h4>
                            @endif
                            @if($image->description)
                                <p class="text-white text-sm mb-4">{{ Str::limit($image->description, 60) }}</p>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2 justify-center">
                                <form method="post" action="{{ route('profile.portfolio.toggle-featured', $image->id) }}" class="inline">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" 
                                            class="px-3 py-1 text-xs rounded-md {{ $image->is_featured ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' }} hover:bg-yellow-400">
                                        {{ $image->is_featured ? '★ Featured' : '☆ Feature' }}
                                    </button>
                                </form>
                                
                                <button type="button" 
                                        onclick="openDeleteModal({{ $image->id }}, '{{ addslashes($image->title ?? 'this image') }}')"
                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Badge -->
                    @if($image->is_featured)
                        <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-semibold shadow-lg">
                            ★ Featured
                        </div>
                    @endif

                    <!-- Hidden Delete Form -->
                    <form id="delete-form-{{ $image->id }}" 
                          method="post" 
                          action="{{ route('profile.portfolio.delete', $image->id) }}" 
                          class="hidden">
                        @csrf
                        @method('delete')
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <strong>💡 Tip:</strong> Featured images will be displayed prominently on your public profile. Select 3-5 of your best photos to feature.
            </p>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No portfolio images yet</h3>
            <p class="mt-1 text-sm text-gray-500">Start building your portfolio by uploading images above.</p>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay with animation -->
        <div id="modalBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-70 transition-opacity duration-300 opacity-0"></div>

        <!-- Modal container -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div id="modalPanel" class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all duration-300 scale-95 opacity-0 sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Modal Content -->
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <!-- Icon -->
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            
                            <!-- Text content -->
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                    Delete Portfolio Image
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete "<span id="imageTitle" class="font-semibold text-gray-900 dark:text-gray-100"></span>"? 
                                        <span class="block mt-1">This action cannot be undone and will permanently remove this image from your portfolio.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <button type="button" 
                                id="confirmDeleteBtn"
                                onclick="confirmDelete()"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800 sm:w-auto transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Image
                        </button>
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800 sm:mt-0 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentImageId = null;

        function openDeleteModal(imageId, imageTitle) {
            currentImageId = imageId;
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            
            // Set the image title
            document.getElementById('imageTitle').textContent = imageTitle;
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Trigger animations
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            
            // Trigger exit animations
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentImageId = null;
            }, 300);
        }

        function confirmDelete() {
            if (currentImageId) {
                // Add loading state
                const btn = document.getElementById('confirmDeleteBtn');
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Deleting...';
                
                // Submit the form
                document.getElementById('delete-form-' + currentImageId).submit();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });

        // Close modal when clicking on backdrop
        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</section>
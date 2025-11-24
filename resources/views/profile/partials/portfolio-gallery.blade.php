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
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
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
                                
                                <form method="post" action="{{ route('profile.portfolio.delete', $image->id) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this image?');" class="inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Badge -->
                    @if($image->is_featured)
                        <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                            ★ Featured
                        </div>
                    @endif
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
</section>
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Photo') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your profile photo.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Upload error flash (from controller redirect->with('error', ...)) -->
        @if (session('error'))
            <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Current Photo + Preview -->
        <div class="flex items-center gap-6">
            <div class="shrink-0">
                <img id="photo-preview"
                     src="{{ $user->profile_photo ?: '' }}"
                     alt="Profile photo"
                     class="h-24 w-24 object-cover rounded-full border-2 border-gray-300 dark:border-gray-600 {{ $user->profile_photo ? '' : 'hidden' }}">
                <div id="photo-placeholder"
                     class="h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 {{ $user->profile_photo ? 'hidden' : '' }}">
                    <svg class="h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex-1">
                <x-input-label for="profile_photo" :value="__('Upload New Photo')" />
                <input type="file"
                       id="profile_photo"
                       name="profile_photo"
                       accept="image/jpeg,image/png,image/jpg,image/gif"
                       onchange="previewPhoto(this)"
                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100
                              dark:file:bg-gray-700 dark:file:text-gray-300
                              dark:hover:file:bg-gray-600">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('JPG, PNG or GIF (MAX. 2MB)') }}
                </p>
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            </div>
        </div>

        <!-- Remove Photo Option -->
        @if($user->profile_photo)
            <div class="flex items-center">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="remove_photo"
                           value="1"
                           class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Remove current photo') }}
                    </span>
                </label>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Photo') }}</x-primary-button>

            @if (session('photo_success'))
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm text-green-600 dark:text-green-400">
                    {{ session('photo_success') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewPhoto(input) {
            const preview    = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</section>
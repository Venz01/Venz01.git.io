<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Business Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your catering business details and services.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Business Name & Owner -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full"
                              :value="old('business_name', $user->business_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
            </div>

            <div>
                <x-input-label for="owner_full_name" :value="__('Owner Full Name')" />
                <x-text-input id="owner_full_name" name="owner_full_name" type="text" class="mt-1 block w-full"
                              :value="old('owner_full_name', $user->owner_full_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('owner_full_name')" />
            </div>
        </div>

        <!-- Business Address -->
        <div>
            <x-input-label for="business_address" :value="__('Business Address')" />
            <textarea id="business_address" name="business_address" rows="2"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      required>{{ old('business_address', $user->business_address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('business_address')" />
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="contact_number" :value="__('Primary Contact Number')" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full"
                              :value="old('contact_number', $user->contact_number)" required />
                <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
            </div>

            <div>
                <x-input-label for="other_contact" :value="__('Alternative Contact')" />
                <x-text-input id="other_contact" name="other_contact" type="text" class="mt-1 block w-full"
                              :value="old('other_contact', $user->other_contact)" />
                <x-input-error class="mt-2" :messages="$errors->get('other_contact')" />
            </div>
        </div>

        <!-- Services Offered -->
        <div>
            <x-input-label for="services_offered" :value="__('Services Offered')" />
            <textarea id="services_offered" name="services_offered" rows="3"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="e.g., Full-service catering, Buffet setup, Plated dinners, Corporate events...">{{ old('services_offered', $user->services_offered) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('services_offered')" />
        </div>

        <!-- Cuisine Types -->
        <div>
            <x-input-label for="cuisine_types" :value="__('Cuisine Types (Select all that apply)')" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                @php
                    $cuisineOptions = ['Filipino', 'Chinese', 'Japanese', 'Korean', 'Italian', 'American', 'Mexican', 'Indian', 'Thai', 'Mediterranean', 'Fusion', 'International'];
                    $selectedCuisines = old('cuisine_types', $user->cuisine_types ?? []);
                @endphp
                @foreach($cuisineOptions as $cuisine)
                    <label class="flex items-center">
                        <input type="checkbox" name="cuisine_types[]" value="{{ $cuisine }}"
                               {{ in_array($cuisine, $selectedCuisines) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $cuisine }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('cuisine_types')" />
        </div>

        <!-- Service Areas -->
        <div>
            <x-input-label for="service_areas" :value="__('Service Areas')" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $areaOptions = ['General Santos', 'Koronadal', 'Polomolok', 'Tupi', 'Surallah', 'Tantangan', 'Banga', 'Lake Sebu'];
                    $selectedAreas = old('service_areas', $user->service_areas ?? []);
                @endphp
                @foreach($areaOptions as $area)
                    <label class="flex items-center">
                        <input type="checkbox" name="service_areas[]" value="{{ $area }}"
                               {{ in_array($area, $selectedAreas) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $area }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('service_areas')" />
        </div>

        <!-- Experience & Team -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="years_of_experience" :value="__('Years of Experience')" />
                <x-text-input id="years_of_experience" name="years_of_experience" type="number" 
                              class="mt-1 block w-full" min="0" max="100"
                              :value="old('years_of_experience', $user->years_of_experience)" />
                <x-input-error class="mt-2" :messages="$errors->get('years_of_experience')" />
            </div>

            <div>
                <x-input-label for="team_size" :value="__('Team Size')" />
                <x-text-input id="team_size" name="team_size" type="number" 
                              class="mt-1 block w-full" min="1"
                              :value="old('team_size', $user->team_size)" 
                              placeholder="Number of staff members" />
                <x-input-error class="mt-2" :messages="$errors->get('team_size')" />
            </div>
        </div>

        <!-- Capacity & Minimum Order -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="minimum_order" :value="__('Minimum Order (₱)')" />
                <x-text-input id="minimum_order" name="minimum_order" type="number" 
                              class="mt-1 block w-full" min="0" step="0.01"
                              :value="old('minimum_order', $user->minimum_order)" />
                <x-input-error class="mt-2" :messages="$errors->get('minimum_order')" />
            </div>

            <div>
                <x-input-label for="maximum_capacity" :value="__('Maximum Capacity (Guests)')" />
                <x-text-input id="maximum_capacity" name="maximum_capacity" type="number" 
                              class="mt-1 block w-full" min="0"
                              :value="old('maximum_capacity', $user->maximum_capacity)" />
                <x-input-error class="mt-2" :messages="$errors->get('maximum_capacity')" />
            </div>
        </div>

        <!-- Service Options -->
        <div class="space-y-3">
            <label class="flex items-center">
                <input type="checkbox" name="offers_delivery" value="1"
                       {{ old('offers_delivery', $user->offers_delivery) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Offers Delivery Service</span>
            </label>

            <label class="flex items-center">
                <input type="checkbox" name="offers_setup" value="1"
                       {{ old('offers_setup', $user->offers_setup) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Offers Setup & Teardown Service</span>
            </label>
        </div>

        <!-- Social Media Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="facebook_link" :value="__('Facebook Page')" />
                <x-text-input id="facebook_link" name="facebook_link" type="url" class="mt-1 block w-full"
                              :value="old('facebook_link', $user->facebook_link)" 
                              placeholder="https://facebook.com/yourpage" />
                <x-input-error class="mt-2" :messages="$errors->get('facebook_link')" />
            </div>

            <div>
                <x-input-label for="instagram_link" :value="__('Instagram')" />
                <x-text-input id="instagram_link" name="instagram_link" type="url" class="mt-1 block w-full"
                              :value="old('instagram_link', $user->instagram_link)" 
                              placeholder="https://instagram.com/yourprofile" />
                <x-input-error class="mt-2" :messages="$errors->get('instagram_link')" />
            </div>

            <div>
                <x-input-label for="website_link" :value="__('Website')" />
                <x-text-input id="website_link" name="website_link" type="url" class="mt-1 block w-full"
                              :value="old('website_link', $user->website_link)" 
                              placeholder="https://yourwebsite.com" />
                <x-input-error class="mt-2" :messages="$errors->get('website_link')" />
            </div>
        </div>

        <!-- Special Features -->
        <div>
            <x-input-label for="special_features" :value="__('Special Features / Highlights')" />
            <textarea id="special_features" name="special_features" rows="3"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="e.g., Halal certified, Organic ingredients, Award-winning chef, Custom menu design...">{{ old('special_features', $user->special_features) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('special_features')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Business Information') }}</x-primary-button>

            @if (session('success'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
            @endif
        </div>
    </form>
</section>
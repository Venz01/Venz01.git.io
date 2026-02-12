<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Photo & Basic Info -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-photo')
                </div>
            </div>

            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(auth()->user()->isCaterer())
                        @include('profile.partials.update-caterer-profile')
                    @else
                        @include('profile.partials.update-customer-profile')
                    @endif
                </div>
            </div>

            <!-- ── Dietary Preferences & Allergies (Customers Only) ── -->
            @if(auth()->user()->isCustomer())
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    @include('profile.partials.dietary-preferences')
                </div>
            </div>
            @endif

            <!-- Portfolio Gallery (Caterers Only) -->
            @if(auth()->user()->isCaterer())
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('profile.partials.portfolio-gallery')
            </div>
            @endif

            <!-- Business Hours & Availability (Caterers Only) -->
            @if(auth()->user()->isCaterer())
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.business-hours')
                </div>
            </div>
            @endif

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
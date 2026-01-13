<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@caterease.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09123456789',
            'status' => 'approved',
        ]);

        // Create Caterer Users (Filipino catering businesses)
        $caterers = [
            [
                'name' => 'Maria Santos',
                'email' => 'maria@delosantos.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'phone' => '09171234567',
                'status' => 'approved',
                'business_name' => 'De Los Santos Catering Services',
                'owner_full_name' => 'Maria De Los Santos',
                'business_address' => '123 Rizal Avenue, Cagayan de Oro City',
                'bio' => 'Specializing in authentic Filipino cuisine for all occasions. Over 15 years of catering experience.',
                'services_offered' => 'Full service catering, event planning, table setup, buffet service',
                'cuisine_types' => ['Filipino', 'Asian Fusion', 'International'], // Array
                'years_of_experience' => 15,
                'team_size' => 12,
                'service_areas' => ['Cagayan de Oro', 'Iligan', 'Valencia', 'Bukidnon'], // Array
                'facebook_link' => 'https://facebook.com/delossantoscatering',
                'instagram_link' => 'https://instagram.com/delossantoscatering',
                'contact_number' => '09171234567',
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '18:00:00',
                'business_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], // Array
                'minimum_order' => 1500.00,
                'maximum_capacity' => 500.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Free tasting for events over 100 pax, Custom menu planning',
                'business_permit_number' => 'BP-2024-001',
            ],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@fiestacatering.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'phone' => '09181234568',
                'status' => 'approved',
                'business_name' => 'Fiesta Catering Co.',
                'owner_full_name' => 'Juan Dela Cruz',
                'business_address' => '456 Velez Street, Cagayan de Oro City',
                'bio' => 'Making every celebration memorable with delicious Filipino favorites and excellent service.',
                'services_offered' => 'Catering for weddings, birthdays, corporate events, buffet and plated service',
                'cuisine_types' => ['Filipino', 'Spanish', 'Chinese'], // Array
                'years_of_experience' => 10,
                'team_size' => 8,
                'service_areas' => ['Cagayan de Oro', 'Malaybalay', 'Opol'], // Array
                'facebook_link' => 'https://facebook.com/fiestacateringco',
                'contact_number' => '09181234568',
                'business_hours_start' => '07:00:00',
                'business_hours_end' => '19:00:00',
                'business_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], // Array
                'minimum_order' => 2000.00,
                'maximum_capacity' => 300.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Live cooking stations, Lechon specialist',
                'business_permit_number' => 'BP-2024-002',
            ],
            [
                'name' => 'Ana Reyes',
                'email' => 'ana@gourmetcdo.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'phone' => '09191234569',
                'status' => 'approved',
                'business_name' => 'Gourmet CDO',
                'owner_full_name' => 'Ana Marie Reyes',
                'business_address' => '789 Corrales Avenue, Cagayan de Oro City',
                'bio' => 'Elevating Filipino cuisine with modern presentation and exceptional taste.',
                'services_offered' => 'Premium catering, cocktail parties, corporate lunches, custom menus',
                'cuisine_types' => ['Modern Filipino', 'International', 'Fusion'], // Array
                'years_of_experience' => 8,
                'team_size' => 10,
                'service_areas' => ['Cagayan de Oro', 'Jasaan', 'Tagoloan'], // Array
                'instagram_link' => 'https://instagram.com/gourmetcdo',
                'website_link' => 'https://gourmetcdo.com',
                'contact_number' => '09191234569',
                'business_hours_start' => '09:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], // Array
                'minimum_order' => 3000.00,
                'maximum_capacity' => 200.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Gourmet presentation, Farm-to-table ingredients',
                'business_permit_number' => 'BP-2024-003',
            ],
        ];

        foreach ($caterers as $caterer) {
            User::create($caterer);
        }

        // Create Customer Users
        $customers = [
            [
                'name' => 'Pedro Garcia',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '09201234570',
                'status' => 'approved',
                'city' => 'Cagayan de Oro',
                'preferred_cuisine' => 'Filipino',
            ],
            [
                'name' => 'Rosa Martinez',
                'email' => 'rosa@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '09211234571',
                'status' => 'approved',
                'city' => 'Cagayan de Oro',
                'default_address' => '234 Luna Street, Cagayan de Oro City',
            ],
            [
                'name' => 'Carlos Lopez',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '09221234572',
                'status' => 'approved',
                'city' => 'Valencia',
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }

        $this->command->info('✓ Users seeded successfully!');
        $this->command->info('  - 1 Admin');
        $this->command->info('  - 3 Caterers');
        $this->command->info('  - 3 Customers');
    }
}
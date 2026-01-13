<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@caterease.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'phone' => '09171234567',
        ]);

        // Sample Customers
        $customers = [
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'phone' => '09171234568',
                'city' => 'Cagayan de Oro',
                'preferred_cuisine' => 'Filipino',
            ],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@email.com',
                'phone' => '09181234567',
                'city' => 'Iligan City',
                'preferred_cuisine' => 'Mixed',
            ],
            [
                'name' => 'Ana Reyes',
                'email' => 'ana.reyes@email.com',
                'phone' => '09191234567',
                'city' => 'Valencia',
                'preferred_cuisine' => 'International',
            ],
        ];

        foreach ($customers as $customer) {
            User::create(array_merge($customer, [
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'approved',
            ]));
        }

        // Sample Caterers
        $caterers = [
            [
                'name' => 'Lola Rosa\'s Catering',
                'email' => 'lolarosa@catering.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'status' => 'approved',
                'phone' => '09171234569',
                'business_name' => 'Lola Rosa\'s Catering Services',
                'owner_full_name' => 'Rosa M. Garcia',
                'business_address' => 'Brgy. Carmen, Cagayan de Oro City',
                'business_permit_number' => 'BP-2024-001',
                'services_offered' => 'Full-service catering for all occasions',
                'cuisine_types' => json_encode(['Filipino', 'Spanish', 'Seafood']),
                'years_of_experience' => 15,
                'team_size' => 12,
                'service_areas' => json_encode(['Cagayan de Oro', 'Iligan', 'Valencia', 'Bukidnon']),
                'contact_number' => '09171234569',
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '18:00:00',
                'business_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
                'minimum_order' => 50.00,
                'maximum_capacity' => 500.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Free dessert buffet for bookings over 200 pax',
                'facebook_link' => 'facebook.com/lolarosascatering',
                'instagram_link' => 'instagram.com/lolarosascatering',
                'bio' => 'Traditional Filipino recipes passed down through generations. Specializing in authentic Pinoy favorites and Spanish-Filipino fusion cuisine.',
            ],
            [
                'name' => 'Kusina ni Tita Glow',
                'email' => 'titaglow@kusina.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'status' => 'approved',
                'phone' => '09181234568',
                'business_name' => 'Kusina ni Tita Glow Catering',
                'owner_full_name' => 'Gloria T. Mendoza',
                'business_address' => 'Brgy. Bulua, Cagayan de Oro City',
                'business_permit_number' => 'BP-2024-002',
                'services_offered' => 'Home-style Filipino cooking for events',
                'cuisine_types' => json_encode(['Filipino', 'Mindanao Cuisine', 'Vegetarian Options']),
                'years_of_experience' => 10,
                'team_size' => 8,
                'service_areas' => json_encode(['Cagayan de Oro', 'Opol', 'Tagoloan', 'Jasaan']),
                'contact_number' => '09181234568',
                'business_hours_start' => '07:00:00',
                'business_hours_end' => '19:00:00',
                'business_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'minimum_order' => 30.00,
                'maximum_capacity' => 300.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Organic vegetables from our own farm',
                'facebook_link' => 'facebook.com/kusinatitglow',
                'bio' => 'Serving authentic Mindanao flavors with a modern twist. Known for our signature sinuglaw and grilled specialties.',
            ],
            [
                'name' => 'Fiesta Foods Catering',
                'email' => 'fiesta@foods.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'status' => 'approved',
                'phone' => '09191234568',
                'business_name' => 'Fiesta Foods Catering & Events',
                'owner_full_name' => 'Roberto P. Villanueva',
                'business_address' => 'Corrales Avenue, Cagayan de Oro City',
                'business_permit_number' => 'BP-2024-003',
                'services_offered' => 'Premium catering with event coordination',
                'cuisine_types' => json_encode(['Filipino', 'International', 'Asian Fusion']),
                'years_of_experience' => 20,
                'team_size' => 25,
                'service_areas' => json_encode(['Cagayan de Oro', 'Iligan', 'Valencia', 'Malaybalay', 'Bukidnon']),
                'contact_number' => '09191234568',
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '20:00:00',
                'business_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'minimum_order' => 100.00,
                'maximum_capacity' => 1000.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Full event planning, themed decorations, live cooking stations',
                'facebook_link' => 'facebook.com/fiestafoods',
                'instagram_link' => 'instagram.com/fiestafoods',
                'website_link' => 'fiestafoods.com',
                'bio' => 'Northern Mindanao\'s premier catering service. We bring elegance and exceptional taste to every celebration.',
            ],
            [
                'name' => 'Bahay Kubo Catering',
                'email' => 'bahaykubo@catering.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'status' => 'approved',
                'phone' => '09201234567',
                'business_name' => 'Bahay Kubo Traditional Catering',
                'owner_full_name' => 'Rosario L. Santiago',
                'business_address' => 'Brgy. Lumbia, Cagayan de Oro City',
                'business_permit_number' => 'BP-2024-004',
                'services_offered' => 'Traditional Filipino catering with native setup',
                'cuisine_types' => json_encode(['Filipino', 'Native Delicacies', 'Grilled Specialties']),
                'years_of_experience' => 8,
                'team_size' => 10,
                'service_areas' => json_encode(['Cagayan de Oro', 'Misamis Oriental']),
                'contact_number' => '09201234567',
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => json_encode(['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'minimum_order' => 40.00,
                'maximum_capacity' => 200.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Banana leaf presentation, native bamboo serving ware',
                'bio' => 'Bringing back the authentic flavors of provincial Filipino cooking. Perfect for intimate gatherings and traditional celebrations.',
            ],
            [
                'name' => 'Metro Manila Catering CDO',
                'email' => 'metromanila@cdo.com',
                'password' => Hash::make('password'),
                'role' => 'caterer',
                'status' => 'approved',
                'phone' => '09211234567',
                'business_name' => 'Metro Manila Style Catering',
                'owner_full_name' => 'Carlos M. Ramos',
                'business_address' => 'Limketkai Area, Cagayan de Oro City',
                'business_permit_number' => 'BP-2024-005',
                'services_offered' => 'Contemporary Filipino and international cuisine',
                'cuisine_types' => json_encode(['Filipino', 'Continental', 'Japanese', 'Korean']),
                'years_of_experience' => 12,
                'team_size' => 15,
                'service_areas' => json_encode(['Cagayan de Oro', 'Iligan', 'Valencia']),
                'contact_number' => '09211234567',
                'business_hours_start' => '09:00:00',
                'business_hours_end' => '21:00:00',
                'business_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'minimum_order' => 80.00,
                'maximum_capacity' => 800.00,
                'offers_delivery' => true,
                'offers_setup' => true,
                'special_features' => 'Instagram-worthy presentations, themed food stations, molecular gastronomy options',
                'facebook_link' => 'facebook.com/metromanilacdo',
                'instagram_link' => 'instagram.com/metromanilacdo',
                'bio' => 'Bringing Metro Manila sophistication to Cagayan de Oro. Modern presentations meet classic Filipino hospitality.',
            ],
        ];

        foreach ($caterers as $caterer) {
            User::create($caterer);
        }

        $this->command->info('Users seeded successfully!');
    }
}
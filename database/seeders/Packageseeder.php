<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = User::where('role', 'caterer')->get();

        $packages = [
            [
                'name' => 'Classic Filipino Package',
                'description' => 'Traditional Filipino favorites perfect for any occasion',
                'price' => 350.00,
                'pax' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Premium Fiesta Package',
                'description' => 'Premium selection of Filipino dishes for special celebrations',
                'price' => 450.00,
                'pax' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Budget-Friendly Package',
                'description' => 'Affordable yet delicious menu for intimate gatherings',
                'price' => 250.00,
                'pax' => 30,
                'status' => 'active',
            ],
            [
                'name' => 'Deluxe Celebration Package',
                'description' => 'Luxurious spread for grand celebrations and weddings',
                'price' => 550.00,
                'pax' => 100,
                'status' => 'active',
            ],
        ];

        foreach ($caterers as $caterer) {
            foreach ($packages as $package) {
                Package::create([
                    'user_id' => $caterer->id,
                    'name' => $package['name'],
                    'description' => $package['description'],
                    'price' => $package['price'],
                    'pax' => $package['pax'],
                    'status' => $package['status'],
                ]);
            }
        }

        $this->command->info('✓ Packages seeded successfully!');
    }
}
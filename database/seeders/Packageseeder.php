<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all approved caterers
        $caterers = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->get();

        if ($caterers->isEmpty()) {
            $this->command->warn('No approved caterers found. Please run UserSeeder first.');
            return;
        }

        // Package templates
        $packageTemplates = [
            [
                'name' => 'Basic Party Package',
                'description' => 'Perfect for intimate gatherings and small celebrations. Includes essential dishes for a complete meal.',
                'pax' => 30,
                'price_per_pax' => 350,
            ],
            [
                'name' => 'Deluxe Celebration Package',
                'description' => 'Ideal for birthdays, anniversaries, and special occasions. Features premium selections and variety.',
                'pax' => 50,
                'price_per_pax' => 450,
            ],
            [
                'name' => 'Premium Fiesta Package',
                'description' => 'Our most popular package! Great for family reunions and town fiestas with abundant servings.',
                'pax' => 100,
                'price_per_pax' => 380,
            ],
            [
                'name' => 'Corporate Event Package',
                'description' => 'Professional catering for business meetings, seminars, and corporate functions.',
                'pax' => 75,
                'price_per_pax' => 420,
            ],
            [
                'name' => 'Wedding Reception Package',
                'description' => 'Elegant dining experience for your special day. Includes multiple course options.',
                'pax' => 150,
                'price_per_pax' => 550,
            ],
            [
                'name' => 'Kiddie Party Package',
                'description' => 'Fun and kid-friendly menu perfect for children\'s birthday parties.',
                'pax' => 40,
                'price_per_pax' => 320,
            ],
            [
                'name' => 'Budget-Friendly Package',
                'description' => 'Affordable option without compromising taste and quality. Great value for money.',
                'pax' => 50,
                'price_per_pax' => 280,
            ],
            [
                'name' => 'Executive Luncheon Package',
                'description' => 'Sophisticated menu for business luncheons and professional gatherings.',
                'pax' => 30,
                'price_per_pax' => 480,
            ],
            [
                'name' => 'Breakfast Buffet Package',
                'description' => 'Morning celebration package with breakfast favorites and coffee service.',
                'pax' => 50,
                'price_per_pax' => 250,
            ],
            [
                'name' => 'Seafood Lovers Package',
                'description' => 'Fresh seafood selections for seafood enthusiasts. Premium quality guaranteed.',
                'pax' => 60,
                'price_per_pax' => 520,
            ],
        ];

        $totalPackages = 0;

        foreach ($caterers as $caterer) {
            // Get caterer's menu items
            $menuItems = MenuItem::where('user_id', $caterer->id)
                ->where('status', 'available')
                ->get();

            if ($menuItems->isEmpty()) {
                $this->command->warn("Caterer {$caterer->business_name} has no menu items. Skipping packages.");
                continue;
            }

            // Each caterer gets 3-6 packages
            $numPackages = rand(3, 6);
            $selectedPackages = collect($packageTemplates)->random($numPackages);

            foreach ($selectedPackages as $packageTemplate) {
                // Calculate total price
                $totalPrice = $packageTemplate['pax'] * $packageTemplate['price_per_pax'];

                // Create the package
                $package = Package::create([
                    'user_id' => $caterer->id,
                    'name' => $packageTemplate['name'],
                    'description' => $packageTemplate['description'],
                    'price' => $totalPrice,
                    'pax' => $packageTemplate['pax'],
                    'status' => rand(1, 10) > 1 ? 'active' : 'inactive', // 90% active
                ]);

                // Attach menu items to package (8-15 items per package)
                $numItems = rand(8, min(15, $menuItems->count()));
                $selectedItems = $menuItems->random($numItems);
                
                $package->items()->attach($selectedItems->pluck('id'));

                $totalPackages++;
                
                $this->command->info("Created package '{$package->name}' for {$caterer->business_name} with {$numItems} menu items");
            }
        }

        $this->command->info("Total packages created: {$totalPackages}");
    }
}
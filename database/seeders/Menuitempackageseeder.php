<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuItemPackageSeeder extends Seeder
{
    public function run(): void
    {
        $caterers = User::where('role', 'caterer')->get();

        foreach ($caterers as $caterer) {
            $packages = Package::where('user_id', $caterer->id)->get();
            $menuItems = MenuItem::where('user_id', $caterer->id)->get();

            foreach ($packages as $package) {
                // Determine how many items based on package type
                $itemCount = match($package->name) {
                    'Budget Fiesta Package' => 5,
                    'Classic Celebration Package' => 7,
                    'Premium Occasion Package' => 10,
                    'Grand Wedding Package' => 12,
                    'Corporate Event Package' => 8,
                    'Kids Party Package' => 6,
                    default => 6,
                };

                // Attach random menu items to each package
                $randomItems = $menuItems->random(min($itemCount, $menuItems->count()));
                
                foreach ($randomItems as $item) {
                    $package->items()->attach($item->id);
                }
            }
        }

        $this->command->info('Menu items attached to packages successfully!');
    }
}
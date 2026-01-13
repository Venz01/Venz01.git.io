<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuItemPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = User::where('role', 'caterer')->get();

        foreach ($caterers as $caterer) {
            $packages = Package::where('user_id', $caterer->id)->get();
            $menuItems = MenuItem::where('user_id', $caterer->id)->get();

            foreach ($packages as $package) {
                // Get menu items by category
                $appetizers = $menuItems->filter(fn($item) => $item->category->name === 'Appetizers');
                $mains = $menuItems->filter(fn($item) => $item->category->name === 'Main Dishes');
                $sides = $menuItems->filter(fn($item) => $item->category->name === 'Side Dishes');
                $desserts = $menuItems->filter(fn($item) => $item->category->name === 'Desserts');
                $beverages = $menuItems->filter(fn($item) => $item->category->name === 'Beverages');

                // Attach items based on package name
                if ($package->name === 'Classic Filipino Package') {
                    $itemsToAttach = array_filter([
                        $appetizers->first()->id ?? null,
                        $mains->skip(0)->first()->id ?? null, // Lechon Kawali
                        $mains->skip(1)->first()->id ?? null, // Chicken Adobo
                        $sides->first()->id ?? null, // Pancit Canton
                        $sides->skip(2)->first()->id ?? null, // Garlic Rice
                        $desserts->first()->id ?? null, // Leche Flan
                        $beverages->first()->id ?? null, // Iced Tea
                    ]);
                    $package->items()->attach($itemsToAttach);
                } elseif ($package->name === 'Premium Fiesta Package') {
                    $itemsToAttach = array_filter([
                        $appetizers->skip(0)->first()->id ?? null,
                        $appetizers->skip(2)->first()->id ?? null,
                        $mains->skip(0)->first()->id ?? null,
                        $mains->skip(2)->first()->id ?? null,
                        $mains->skip(4)->first()->id ?? null, // Kare-Kare
                        $sides->first()->id ?? null,
                        $sides->skip(2)->first()->id ?? null,
                        $desserts->skip(0)->first()->id ?? null,
                        $desserts->skip(1)->first()->id ?? null,
                        $beverages->skip(1)->first()->id ?? null,
                    ]);
                    $package->items()->attach($itemsToAttach);
                } elseif ($package->name === 'Budget-Friendly Package') {
                    $itemsToAttach = array_filter([
                        $appetizers->first()->id ?? null,
                        $mains->skip(1)->first()->id ?? null, // Chicken Adobo
                        $mains->skip(3)->first()->id ?? null, // Pork Menudo
                        $sides->skip(1)->first()->id ?? null, // Pancit Bihon
                        $sides->skip(3)->first()->id ?? null, // Plain Rice
                        $desserts->skip(2)->first()->id ?? null, // Maja Blanca
                        $beverages->first()->id ?? null,
                    ]);
                    $package->items()->attach($itemsToAttach);
                } elseif ($package->name === 'Deluxe Celebration Package') {
                    // Combine all IDs into a single flat array
                    $allIds = collect()
                        ->merge($appetizers->pluck('id')->take(3))
                        ->merge($mains->pluck('id')->take(4))
                        ->merge($sides->pluck('id')->take(2))
                        ->merge($desserts->pluck('id')->take(3))
                        ->merge($beverages->pluck('id')->take(2))
                        ->filter() // Remove any nulls
                        ->toArray();
                    
                    $package->items()->attach($allIds);
                }
            }
        }

        $this->command->info('✓ Menu items linked to packages successfully!');
    }
}
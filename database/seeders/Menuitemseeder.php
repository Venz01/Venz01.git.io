<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = User::where('role', 'caterer')->get();

        $menuItems = [
            'Appetizers' => [
                ['name' => 'Lumpia Shanghai', 'description' => 'Crispy Filipino spring rolls with meat filling', 'price' => 150.00],
                ['name' => 'Lumpiang Sariwa', 'description' => 'Fresh vegetable spring rolls with garlic sauce', 'price' => 120.00],
                ['name' => 'Chicken Lollipop', 'description' => 'Deep-fried chicken winglets', 'price' => 180.00],
                ['name' => 'Dynamite', 'description' => 'Stuffed green chili wrapped in spring roll wrapper', 'price' => 140.00],
            ],
            'Main Dishes' => [
                ['name' => 'Lechon Kawali', 'description' => 'Crispy pork belly with lechon sauce', 'price' => 350.00],
                ['name' => 'Chicken Adobo', 'description' => 'Classic Filipino braised chicken in soy-vinegar sauce', 'price' => 280.00],
                ['name' => 'Beef Caldereta', 'description' => 'Tender beef stew in tomato sauce', 'price' => 320.00],
                ['name' => 'Pork Menudo', 'description' => 'Pork cubes in tomato sauce with vegetables', 'price' => 250.00],
                ['name' => 'Kare-Kare', 'description' => 'Oxtail and vegetables in peanut sauce', 'price' => 380.00],
                ['name' => 'Sinigang na Baboy', 'description' => 'Pork in tamarind soup', 'price' => 300.00],
            ],
            'Side Dishes' => [
                ['name' => 'Pancit Canton', 'description' => 'Stir-fried noodles with vegetables and meat', 'price' => 200.00],
                ['name' => 'Pancit Bihon', 'description' => 'Rice noodles with vegetables', 'price' => 180.00],
                ['name' => 'Garlic Rice', 'description' => 'Fragrant garlic fried rice', 'price' => 80.00],
                ['name' => 'Plain Rice', 'description' => 'Steamed white rice', 'price' => 50.00],
            ],
            'Desserts' => [
                ['name' => 'Leche Flan', 'description' => 'Creamy Filipino caramel custard', 'price' => 150.00],
                ['name' => 'Buko Pandan', 'description' => 'Young coconut with pandan jelly in cream', 'price' => 120.00],
                ['name' => 'Maja Blanca', 'description' => 'Coconut pudding with corn', 'price' => 100.00],
                ['name' => 'Fruit Salad', 'description' => 'Mixed fruits in sweetened cream', 'price' => 130.00],
            ],
            'Beverages' => [
                ['name' => 'Iced Tea', 'description' => 'Refreshing lemon iced tea', 'price' => 60.00],
                ['name' => 'Fresh Buko Juice', 'description' => 'Fresh young coconut juice', 'price' => 80.00],
                ['name' => 'Calamansi Juice', 'description' => 'Filipino lime juice', 'price' => 70.00],
                ['name' => 'Sago Gulaman', 'description' => 'Brown sugar drink with tapioca pearls and jelly', 'price' => 50.00],
            ],
        ];

        foreach ($caterers as $caterer) {
            foreach ($menuItems as $categoryName => $items) {
                $category = Category::where('user_id', $caterer->id)
                    ->where('name', $categoryName)
                    ->first();

                if ($category) {
                    foreach ($items as $item) {
                        MenuItem::create([
                            'user_id' => $caterer->id,
                            'category_id' => $category->id,
                            'name' => $item['name'],
                            'description' => $item['description'],
                            'price' => $item['price'],
                            'status' => 'available',
                        ]);
                    }
                }
            }
        }

        $this->command->info('✓ Menu items seeded successfully!');
    }
}
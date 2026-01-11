<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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

        // Define common categories for catering services
        $categoryTemplates = [
            [
                'name' => 'Appetizers',
                'description' => 'Delicious starters and finger foods to begin your event'
            ],
            [
                'name' => 'Main Course',
                'description' => 'Hearty main dishes for your guests'
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal perfectly'
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing drinks and specialty beverages'
            ],
            [
                'name' => 'Salads',
                'description' => 'Fresh and healthy salad options'
            ],
            [
                'name' => 'Soups',
                'description' => 'Warm and comforting soup selections'
            ],
            [
                'name' => 'Pasta & Rice',
                'description' => 'Carbohydrate-rich dishes and side options'
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh catches and seafood specialties'
            ],
            [
                'name' => 'Grilled Items',
                'description' => 'BBQ and grilled specialties'
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Plant-based and vegetarian-friendly options'
            ],
            [
                'name' => 'Breakfast Items',
                'description' => 'Morning favorites and breakfast classics'
            ],
            [
                'name' => 'Side Dishes',
                'description' => 'Complementary sides for your main course'
            ],
        ];

        foreach ($caterers as $caterer) {
            // Each caterer gets 4-8 random categories
            $numCategories = rand(4, 8);
            $selectedCategories = collect($categoryTemplates)
                ->random($numCategories);

            foreach ($selectedCategories as $category) {
                Category::create([
                    'user_id' => $caterer->id,
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]);
            }

            $this->command->info("Created {$numCategories} categories for caterer: {$caterer->business_name}");
        }

        $totalCategories = Category::count();
        $this->command->info("Total categories created: {$totalCategories}");
    }
}
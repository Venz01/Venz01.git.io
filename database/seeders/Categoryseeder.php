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
        // Get caterer users
        $caterers = User::where('role', 'caterer')->get();

        $categoryTemplates = [
            ['name' => 'Appetizers', 'description' => 'Starters and finger foods'],
            ['name' => 'Main Dishes', 'description' => 'Filipino main course favorites'],
            ['name' => 'Side Dishes', 'description' => 'Complementary side dishes'],
            ['name' => 'Desserts', 'description' => 'Sweet treats and traditional desserts'],
            ['name' => 'Beverages', 'description' => 'Drinks and refreshments'],
        ];

        foreach ($caterers as $caterer) {
            foreach ($categoryTemplates as $template) {
                Category::create([
                    'user_id' => $caterer->id,
                    'name' => $template['name'],
                    'description' => $template['description'],
                ]);
            }
        }

        $this->command->info('✓ Categories seeded successfully!');
    }
}
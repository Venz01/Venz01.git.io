<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Seed in the correct order due to foreign key dependencies
        $this->call([
            CategorySeeder::class,      // Second: Create categories for caterers
            MenuItemSeeder::class,      // Third: Create menu items for categories
            PackageSeeder::class,       // Fourth: Create packages with menu items
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        
        // Display summary
        $this->command->table(
            ['Resource', 'Count'],
            [
                ['Categories', \App\Models\Category::count()],
                ['Menu Items', \App\Models\MenuItem::count()],
                ['Packages', \App\Models\Package::count()],
            ]
        );
    }
}
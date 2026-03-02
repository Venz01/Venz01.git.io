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
        $this->call([
            // ... your existing seeders ...
            CategoryAndMenuItemSeeder::class,
            DisplayMenuSeeder::class,
            PackageSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\PortfolioImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortfolioImageSeeder extends Seeder
{
    public function run(): void
    {
        $caterers = User::where('role', 'caterer')->get();

        $portfolioDescriptions = [
            'Wedding reception buffet setup',
            'Birthday party food display',
            'Corporate event catering',
            'Lechon presentation',
            'Dessert table arrangement',
            'Outdoor garden party setup',
            'Anniversary celebration spread',
        ];

        foreach ($caterers as $caterer) {
            // Create 3-5 portfolio images per caterer
            $imageCount = rand(3, 5);
            
            for ($i = 1; $i <= $imageCount; $i++) {
                PortfolioImage::create([
                    'user_id' => $caterer->id,
                    'image_path' => "portfolio/sample-{$caterer->id}-{$i}.jpg",
                    'title' => "Event Photo {$i}",
                    'description' => $portfolioDescriptions[array_rand($portfolioDescriptions)],
                    'order' => $i,
                    'is_featured' => $i === 1, // First image is featured
                ]);
            }
        }

        $this->command->info('Portfolio images seeded successfully!');
    }
}
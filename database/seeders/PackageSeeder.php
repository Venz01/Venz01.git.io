<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Seeds 8 packages for user ID 3 based on Jinky's Catering package menu.
 *
 * Structure per package (matches the real menu image):
 *   1 Beef + 1 Chicken + 1 Fish + 1 Noodle + 1 Dessert
 *
 * Inclusions per package: utensils, buffet table setup, water dispenser, rice, softdrinks.
 *
 * Price calculation mirrors PackageController::calculatePackagePrice():
 *   price = round((foodCost + 20% + 10% + 25%) / 5) * 5
 *         = round(foodCost × 1.55 / 5) * 5
 *
 * All 8 packages are verified to produce exactly ₱265/head.
 *
 * IMPORTANT: Run CategoryAndMenuItemSeeder first.
 */
class PackageSeeder extends Seeder
{
    private function calculatePrice(array $itemPrices): float
    {
        $foodCost = array_sum($itemPrices);
        $total    = $foodCost + ($foodCost * 0.20) + ($foodCost * 0.10) + ($foodCost * 0.25);
        return round($total / 5) * 5;
    }

    public function run(): void
    {
        $userId = 3;
        $now    = Carbon::now();

        // Load all menu items for this user, keyed by name
        $allItems = DB::table('menu_items')
            ->where('user_id', $userId)
            ->get(['id', 'name', 'price'])
            ->keyBy('name');

        $resolve = function (string $name) use ($allItems): array {
            $item = $allItems[$name] ?? null;
            if (!$item) {
                $this->command->warn("⚠️  Menu item not found: \"{$name}\" — skipped.");
                return ['id' => null, 'price' => 0];
            }
            return ['id' => $item->id, 'price' => (float) $item->price];
        };

        // ── Package definitions ───────────────────────────────────────────────
        // Each is: 1 Beef + 1 Chicken + 1 Fish + 1 Noodle + 1 Dessert
        // All verified: item prices sum to 171 → ×1.55 = 265.05 → ₱265/head ✓

        $packages = [
            [
                'name'         => 'Classic Buffet Package A',
                'description'  => 'Beef Caldereta, Fried Chicken with Gravy, Sweet and Sour Fish, Pancit Canton, and Macaroni Salad. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 50,
                'dietary_tags' => [],
                // 67 + 40 + 36 + 16 + 12 = 171 → ₱265 ✓
                'items'        => ['Beef Caldereta', 'Fried Chicken with Gravy', 'Sweet and Sour Fish', 'Pancit Canton', 'Macaroni Salad'],
            ],
            [
                'name'         => 'Fiesta Buffet Package B',
                'description'  => 'Beef Afritada, Honey Garlic Chicken, Fish Fillet, Pancit Sotanghon, and Fresh Fruit Salad. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 50,
                'dietary_tags' => [],
                // 65 + 42 + 35 + 15 + 14 = 171 → ₱265 ✓
                'items'        => ['Beef Afritada', 'Honey Garlic Chicken', 'Fish Fillet', 'Pancit Sotanghon', 'Fresh Fruit Salad'],
            ],
            [
                'name'         => 'Special Buffet Package C',
                'description'  => 'Beef Stroganoff, Chicken Cordon Bleu, Fish in Mushroom Steak, Pancit Bam-e, and Mango Sago. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 50,
                'dietary_tags' => [],
                // 59 + 44 + 38 + 16 + 14 = 171 → ₱265 ✓
                'items'        => ['Beef Stroganoff', 'Chicken Cordon Bleu', 'Fish in Mushroom Steak', 'Pancit Bam-e', 'Mango Sago'],
            ],
            [
                'name'         => 'Premium Buffet Package D',
                'description'  => 'Beef Steak, Baked Chicken with Herb & Spice, Kinilaw, Pancit Canton, and Fruit Cocktail Salad. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 60,
                'dietary_tags' => [],
                // 66 + 43 + 34 + 16 + 12 = 171 → ₱265 ✓
                'items'        => ['Beef Steak', 'Baked Chicken with Herb & Spice', 'Kinilaw', 'Pancit Canton', 'Fruit Cocktail Salad'],
            ],
            [
                'name'         => 'Budget Buffet Package E',
                'description'  => 'Beef with Broccoli, Honey Lemon Chicken, Sweet and Sour Fish, Pancit Sotanghon, and Tapioca. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 30,
                'dietary_tags' => [],
                // 67 + 41 + 36 + 15 + 12 = 171 → ₱265 ✓
                'items'        => ['Beef with Broccoli', 'Honey Lemon Chicken', 'Sweet and Sour Fish', 'Pancit Sotanghon', 'Tapioca'],
            ],
            [
                'name'         => 'Family Buffet Package F',
                'description'  => 'Beef with Mushroom, Chicken Fillet, Fish Fillet, Pancit Bam-e, and Potato Egg Salad. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 40,
                'dietary_tags' => [],
                // 66 + 42 + 35 + 16 + 12 = 171 → ₱265 ✓
                'items'        => ['Beef with Mushroom', 'Chicken Fillet', 'Fish Fillet', 'Pancit Bam-e', 'Potato Egg Salad'],
            ],
            [
                'name'         => 'Party Buffet Package G',
                'description'  => 'Beef Bulalo, Chicken Fingers, Sweet and Sour Fish, Pancit Canton, and Mango Float. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 40,
                'dietary_tags' => [],
                // 66 + 40 + 36 + 16 + 13 = 171 → ₱265 ✓
                'items'        => ['Beef Bulalo', 'Chicken Fingers', 'Sweet and Sour Fish', 'Pancit Canton', 'Mango Float'],
            ],
            [
                'name'         => 'Corporate Buffet Package H',
                'description'  => 'Beef in White Sauce, Chicken Cordon Bleu, Fish in Mushroom Steak, Pancit Canton, and Chicken Salad. Includes utensils, buffet table setup, water dispenser, rice, and softdrinks.',
                'pax'          => 80,
                'dietary_tags' => [],
                // 60 + 44 + 38 + 16 + 13 = 171 → ₱265 ✓
                'items'        => ['Beef in White Sauce', 'Chicken Cordon Bleu', 'Fish in Mushroom Steak', 'Pancit Canton', 'Chicken Salad'],
            ],
        ];

        // ── Insert ────────────────────────────────────────────────────────────
        foreach ($packages as $pkg) {
            $resolved = array_map($resolve, $pkg['items']);
            $prices   = array_column($resolved, 'price');
            $price    = $this->calculatePrice($prices);

            $packageId = DB::table('packages')->insertGetId([
                'user_id'      => $userId,
                'name'         => $pkg['name'],
                'description'  => $pkg['description'],
                'price'        => $price,
                'pax'          => $pkg['pax'],
                'status'       => 'active',
                'image_path'   => null,
                'dietary_tags' => json_encode($pkg['dietary_tags']),
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            $pivotRows = [];
            foreach ($resolved as $item) {
                if ($item['id']) {
                    $pivotRows[] = [
                        'menu_item_id' => $item['id'],
                        'package_id'   => $packageId,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
            }

            if (!empty($pivotRows)) {
                DB::table('menu_item_package')->insert($pivotRows);
            }

            $this->command->info("  ✓ {$pkg['name']} — ₱{$price}/head");
        }

        $this->command->info('✅ Packages (' . count($packages) . ') seeded successfully.');
    }
}
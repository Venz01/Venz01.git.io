<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisplayMenuSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 3;
        $now    = Carbon::now();

        // Display menus are tray/bilao-based items sold directly to customers.
        // Prices and categories match the uploaded menu image.

        $items = [

            // ── Beef ─────────────────────────────────────────────────────────
            ['category' => 'Beef', 'name' => 'Beef Steak',         'description' => 'Classic Filipino bistek — tender beef slices in soy-citrus sauce.',            'price' => 1350, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef in White Sauce','description' => 'Tender beef in creamy white sauce, served by tray.',                           'price' => 1400, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef Afritada',      'description' => 'Beef stewed in tomato sauce with potatoes, carrots, and bell peppers.',        'price' => 1200, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef Caldereta',     'description' => 'Rich and spicy beef stew with liver spread and vegetables.',                   'price' => 1200, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef Bulalo',        'description' => 'Hearty beef marrow soup with tender shanks and vegetables.',                   'price' => 1500, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef Tapa',          'description' => 'Sweet and savory cured beef, thinly sliced and pan-fried.',                    'price' => 1200, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef Stroganoff',    'description' => 'Tender beef strips in creamy mushroom sauce.',                                 'price' => 1400, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef with Broccoli', 'description' => 'Stir-fried beef and broccoli in savory oyster sauce.',                         'price' => 1200, 'unit_type' => 'tray'],
            ['category' => 'Beef', 'name' => 'Beef with Mushroom', 'description' => 'Sautéed beef with button mushrooms in rich brown sauce.',                      'price' => 1400, 'unit_type' => 'tray'],

            // ── Vegetables ───────────────────────────────────────────────────
            ['category' => 'Vegetables', 'name' => 'Chopsuey',                        'description' => 'Classic Filipino stir-fried mixed vegetables in light sauce.',           'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Stir Fry Vegetables',             'description' => 'Crisp assorted vegetables tossed in savory oyster sauce.',               'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Buttered Vegetables',             'description' => 'Fresh mixed vegetables sautéed in butter with light seasoning.',         'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Blanched Vegetables',             'description' => 'Lightly blanched garden vegetables served with dipping sauce.',          'price' => 650,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Lettuce Salad',                   'description' => 'Fresh garden lettuce salad with house dressing.',                        'price' => 650,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Pinakbet',                        'description' => 'Filipino mixed vegetable stew with shrimp paste.',                      'price' => 600,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Salad Ampalaya',                  'description' => 'Bitter gourd salad with tomatoes and onions.',                           'price' => 600,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Puso ng Saging Salad',            'description' => 'Banana blossom salad with fresh vegetables and dressing.',               'price' => 600,  'unit_type' => 'tray'],
            ['category' => 'Vegetables', 'name' => 'Fried Talong with Gunisang Uyap', 'description' => 'Fried eggplant sautéed with salted small shrimp.',                       'price' => 500,  'unit_type' => 'tray'],

            // ── Cakes & Pastry ───────────────────────────────────────────────
            ['category' => 'Cakes & Pastry', 'name' => 'Blueberry Cheese Cake',       'description' => 'Rich cheesecake topped with blueberry compote.',                         'price' => 1200, 'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Cheese Cake',           'description' => 'Creamy cheesecake layered with fresh mango topping.',                    'price' => 1200, 'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Triple Chocolate Cheese Cake','description' => 'Decadent triple chocolate cheesecake.',                                  'price' => 1200, 'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Brownies',                    'description' => 'Fudgy chocolate brownies baked to perfection.',                          'price' => 800,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Chocolate Cake',              'description' => 'Moist chocolate layer cake with chocolate frosting.',                    'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Ube Cake',                    'description' => 'Vibrant purple yam cake with ube halaya filling.',                       'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Yema Cake',                   'description' => 'Soft chiffon cake coated with creamy yema frosting.',                    'price' => 800,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Cake',                  'description' => 'Light sponge cake with fresh mango cream filling.',                      'price' => 800,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Cheese Cake',                 'description' => 'Classic no-bake cheesecake with graham crust.',                          'price' => 750,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Butter Cake',                 'description' => 'Classic moist butter cake with buttery frosting.',                       'price' => 650,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Chiffon Cake',                'description' => 'Light and airy chiffon cake.',                                           'price' => 600,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Banana Cake',                 'description' => 'Moist banana cake with sweet cream frosting.',                           'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Mocha Cake',                  'description' => 'Coffee-flavored layer cake with mocha buttercream.',                     'price' => 800,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Carrot Cake',                 'description' => 'Moist spiced carrot cake with cream cheese frosting.',                   'price' => 750,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Black Forest Cake',           'description' => 'Classic black forest with cherries, cream, and chocolate sponge.',       'price' => 900,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Custard Cake',                'description' => 'Soft custard cake with silky smooth custard layer.',                     'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Cakes & Pastry', 'name' => 'Caramel Bar',                 'description' => 'Sweet and chewy caramel-topped bar pastry.',                             'price' => 850,  'unit_type' => 'piece'],
            ['category' => 'Cakes & Pastry', 'name' => 'Chantilly Bar',               'description' => 'Light Chantilly cream bar with sponge base.',                            'price' => 850,  'unit_type' => 'piece'],
            ['category' => 'Cakes & Pastry', 'name' => 'Pineapple Bar',               'description' => 'Pineapple-filled pastry bar with crumbly topping.',                      'price' => 850,  'unit_type' => 'piece'],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Bar',                   'description' => 'Fresh mango bar pastry with cream filling.',                             'price' => 850,  'unit_type' => 'piece'],
            ['category' => 'Cakes & Pastry', 'name' => 'Creamy Leche Flan',           'description' => 'Classic silky smooth leche flan with rich caramel.',                     'price' => 850,  'unit_type' => 'bilao'],

            // ── Desserts ─────────────────────────────────────────────────────
            ['category' => 'Desserts', 'name' => 'Potato Egg Salad',           'description' => 'Creamy potato and egg salad, a classic Filipino party staple.',          'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Macaroni Salad',             'description' => 'Sweet and creamy macaroni salad with fruits and mayonnaise.',              'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Fresh Fruit Salad',          'description' => 'Assorted fresh fruits in cream and condensed milk.',                      'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Fruit Cocktail Salad',       'description' => 'Mixed fruit cocktail in whipped cream dressing.',                         'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Mango Sago',                 'description' => 'Refreshing mango dessert soup with sago pearls.',                         'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Tapioca',                    'description' => 'Sweet tapioca pearls in coconut milk with fresh fruits.',                 'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Chicken Salad',              'description' => 'Savory chicken salad with mixed vegetables and mayo dressing.',           'price' => 900,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Mango Graham Leche (8x6)',   'description' => 'Classic mango graham layered dessert — 8x6 round size.',                  'price' => 850,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Mango Graham Leche (14x12)', 'description' => 'Family-size mango graham layered dessert — 14x12 rectangle.',            'price' => 1200, 'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Mango Float',                'description' => 'Chilled mango float with graham crackers and cream layers.',               'price' => 800,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Banana Cream',               'description' => 'Sweet banana cream dessert with whipped topping.',                        'price' => 600,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Maja Blanca',                'description' => 'Soft coconut milk pudding topped with latik.',                            'price' => 650,  'unit_type' => 'bilao'],
            ['category' => 'Desserts', 'name' => 'Bake Bico',                  'description' => 'Oven-baked sticky rice kakanin with coconut milk.',                       'price' => 600,  'unit_type' => 'bilao'],

            // ── Pasta ─────────────────────────────────────────────────────────
            ['category' => 'Pasta', 'name' => 'Lasagna',              'description' => 'Layered pasta with meat sauce, béchamel, and melted cheese.',             'price' => 1000, 'unit_type' => 'tray'],
            ['category' => 'Pasta', 'name' => 'Carbonara',            'description' => 'Creamy Filipino-style carbonara with bacon and cheese.',                   'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Pasta', 'name' => 'Baked Spaghetti',      'description' => 'Oven-baked spaghetti with meat sauce and melted cheese topping.',         'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Pasta', 'name' => 'Plain Spaghetti',      'description' => 'Classic Filipino-style sweet spaghetti with tomato meat sauce.',          'price' => 850,  'unit_type' => 'tray'],
            ['category' => 'Pasta', 'name' => 'Cheesy Bake Macaroni', 'description' => 'Creamy baked macaroni loaded with cheese and white sauce.',               'price' => 900,  'unit_type' => 'tray'],
            ['category' => 'Pasta', 'name' => 'Penne Pasta',          'description' => 'Penne pasta in rich tomato or cream sauce, oven finished.',               'price' => 900,  'unit_type' => 'tray'],
        ];

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [
                'user_id'     => $userId,
                'name'        => $item['name'],
                'category'    => $item['category'],
                'description' => $item['description'],
                'price'       => $item['price'],
                'unit_type'   => $item['unit_type'],
                'image_path'  => null,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('display_menus')->insert($rows);

        $this->command->info('✅ Display Menus (' . count($rows) . ') seeded successfully.');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Seeds categories and menu items for user ID 3.
 *
 * MenuItem prices = per-head INGREDIENT COST contribution.
 *
 * PackageController formula: foodCost × 1.55, rounded to nearest ₱5.
 * These prices are tuned so that every seeded 5-course package
 * (1 Beef + 1 Chicken + 1 Fish + 1 Noodle + 1 Dessert) sums to ₱171,
 * which produces exactly ₱265/head after the controller formula:
 *   171 × 1.55 = 265.05 → ₱265 ✓
 *
 * Individual item prices vary slightly within each category
 * (e.g. Cordon Bleu costs more than plain Fried Chicken)
 * so the prices are realistic relative to one another.
 */
class CategoryAndMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 3;
        $now    = Carbon::now();

        // ── 1. Categories ─────────────────────────────────────────────────────

        $categories = [
            ['name' => 'Beef',           'description' => 'Beef-based main dishes'],
            ['name' => 'Chicken',        'description' => 'Chicken-based main dishes'],
            ['name' => 'Fish',           'description' => 'Fish and seafood dishes'],
            ['name' => 'Noodles',        'description' => 'Noodle dishes'],
            ['name' => 'Vegetables',     'description' => 'Vegetable sides and salads'],
            ['name' => 'Pasta',          'description' => 'Pasta dishes'],
            ['name' => 'Desserts',       'description' => 'Desserts and sweet dishes'],
            ['name' => 'Cakes & Pastry', 'description' => 'Cakes, pastries, and baked goods'],
        ];

        foreach ($categories as &$cat) {
            $cat['user_id']    = $userId;
            $cat['created_at'] = $now;
            $cat['updated_at'] = $now;
        }
        unset($cat);

        DB::table('categories')->insert($categories);

        $catIds = DB::table('categories')
            ->where('user_id', $userId)
            ->pluck('id', 'name')
            ->toArray();

        // ── 2. Menu Items ─────────────────────────────────────────────────────
        // All prices are per-head ingredient costs (₱).
        //
        // Verified price combinations for each seeded package (raw sum = 171):
        //   Package A: Caldereta(67) + FriedChicken(40) + SweetSour(36) + Canton(16)     + MacSalad(12)      = 171 → ₱265
        //   Package B: Afritada(65)  + HoneyGarlic(42)  + FilFillet(35) + Sotanghon(15)  + FruitSalad(14)    = 171 → ₱265
        //   Package C: Stroganoff(59)+ CordonBleu(44)   + MushSteak(38) + BamE(16)       + MangoSago(14)     = 171 → ₱265
        //   Package D: Steak(66)     + BakedChicken(43) + Kinilaw(34)   + Canton(16)     + Cocktail(12)      = 171 → ₱265
        //   Package E: Broccoli(67)  + HoneyLemon(41)   + SweetSour(36) + Sotanghon(15)  + Tapioca(12)       = 171 → ₱265
        //   Package F: Mushroom(66)  + Fillet(42)        + FilFillet(35) + BamE(16)       + PotatoSalad(12)   = 171 → ₱265
        //   Package G: Bulalo(66)    + Fingers(40)       + SweetSour(36) + Canton(16)     + MangoFloat(13)    = 171 → ₱265
        //   Package H: WhiteSauce(60)+ CordonBleu(44)    + MushSteak(38) + Canton(16)     + ChickenSalad(13)  = 171 → ₱265

        $items = [

            // ── Beef ─────────────────────────────────────────────────────────
            // Prices range ₱59–67 to reflect ingredient cost differences.
            ['category' => 'Beef', 'name' => 'Beef Steak',          'description' => 'Classic Filipino bistek — tender beef slices in soy-citrus sauce.',          'price' => 66],
            ['category' => 'Beef', 'name' => 'Beef in White Sauce', 'description' => 'Tender beef in rich creamy white sauce, perfect for buffet.',                'price' => 60],
            ['category' => 'Beef', 'name' => 'Beef Afritada',       'description' => 'Beef stewed in tomato sauce with potatoes, carrots, and bell peppers.',      'price' => 65],
            ['category' => 'Beef', 'name' => 'Beef Caldereta',      'description' => 'Rich and spicy beef stew cooked with liver spread and vegetables.',          'price' => 67],
            ['category' => 'Beef', 'name' => 'Beef Bulalo',         'description' => 'Hearty beef marrow soup with tender shanks and vegetables.',                 'price' => 66],
            ['category' => 'Beef', 'name' => 'Beef Tapa',           'description' => 'Sweet and savory cured beef, thinly sliced and pan-fried.',                  'price' => 64],
            ['category' => 'Beef', 'name' => 'Beef Stroganoff',     'description' => 'Tender beef strips in creamy mushroom sauce.',                               'price' => 59],
            ['category' => 'Beef', 'name' => 'Beef with Broccoli',  'description' => 'Stir-fried beef and broccoli in savory oyster sauce.',                       'price' => 67],
            ['category' => 'Beef', 'name' => 'Beef with Mushroom',  'description' => 'Sautéed beef with button mushrooms in rich brown sauce.',                    'price' => 66],

            // ── Chicken ──────────────────────────────────────────────────────
            // Prices range ₱40–44.
            ['category' => 'Chicken', 'name' => 'Honey Garlic Chicken',            'description' => 'Crispy chicken glazed with sweet honey garlic sauce.',                    'price' => 42],
            ['category' => 'Chicken', 'name' => 'Chicken Cordon Bleu',             'description' => 'Breaded chicken stuffed with ham and cheese, golden fried.',              'price' => 44],
            ['category' => 'Chicken', 'name' => 'Honey Lemon Chicken',             'description' => 'Tender chicken pieces in a bright honey lemon glaze.',                    'price' => 41],
            ['category' => 'Chicken', 'name' => 'Fried Chicken with Gravy',        'description' => 'Classic crispy fried chicken served with savory brown gravy.',            'price' => 40],
            ['category' => 'Chicken', 'name' => 'Chicken Fillet',                  'description' => 'Pan-seared boneless chicken fillet with seasoning.',                      'price' => 42],
            ['category' => 'Chicken', 'name' => 'Baked Chicken with Herb & Spice', 'description' => 'Oven-baked chicken seasoned with aromatic herbs and spices.',             'price' => 43],
            ['category' => 'Chicken', 'name' => 'Chicken Fingers',                 'description' => 'Breaded chicken strips, crispy outside and juicy inside.',                'price' => 40],

            // ── Fish ─────────────────────────────────────────────────────────
            // Prices range ₱34–38.
            ['category' => 'Fish', 'name' => 'Sweet and Sour Fish',    'description' => 'Crispy fish fillet in tangy sweet and sour sauce with bell peppers.',      'price' => 36],
            ['category' => 'Fish', 'name' => 'Fish in Mushroom Steak', 'description' => 'Pan-seared fish fillet topped with creamy mushroom steak sauce.',          'price' => 38],
            ['category' => 'Fish', 'name' => 'Fish Fillet',            'description' => 'Lightly breaded and pan-fried boneless fish fillet.',                      'price' => 35],
            ['category' => 'Fish', 'name' => 'Kinilaw',                'description' => 'Fresh fish cured in vinegar and citrus with ginger and chili.',            'price' => 34],

            // ── Noodles ──────────────────────────────────────────────────────
            // Prices range ₱15–16.
            ['category' => 'Noodles', 'name' => 'Pancit Canton',    'description' => 'Stir-fried egg noodles with vegetables, meat, and soy-based sauce.',       'price' => 16],
            ['category' => 'Noodles', 'name' => 'Pancit Sotanghon', 'description' => 'Glass noodles sautéed with chicken, vegetables, and light seasoning.',     'price' => 15],
            ['category' => 'Noodles', 'name' => 'Pancit Bam-e',     'description' => 'Mixed egg and glass noodles with assorted vegetables and meat.',           'price' => 16],

            // ── Vegetables ───────────────────────────────────────────────────
            // Priced similarly to noodles (sides/substitutes). ₱12–15.
            ['category' => 'Vegetables', 'name' => 'Chopsuey',                        'description' => 'Classic Filipino stir-fried mixed vegetables in light sauce.',         'price' => 14],
            ['category' => 'Vegetables', 'name' => 'Stir Fry Vegetables',             'description' => 'Crisp assorted vegetables tossed in savory oyster sauce.',             'price' => 14],
            ['category' => 'Vegetables', 'name' => 'Buttered Vegetables',             'description' => 'Fresh mixed vegetables sautéed in butter with light seasoning.',       'price' => 13],
            ['category' => 'Vegetables', 'name' => 'Blanched Vegetables',             'description' => 'Lightly blanched garden vegetables served with dipping sauce.',        'price' => 12],
            ['category' => 'Vegetables', 'name' => 'Lettuce Salad',                   'description' => 'Fresh garden lettuce salad with house dressing.',                      'price' => 12],
            ['category' => 'Vegetables', 'name' => 'Pinakbet',                        'description' => 'Filipino mixed vegetable stew with shrimp paste.',                    'price' => 14],
            ['category' => 'Vegetables', 'name' => 'Salad Ampalaya',                  'description' => 'Bitter gourd salad with tomatoes and onions.',                         'price' => 12],
            ['category' => 'Vegetables', 'name' => 'Puso ng Saging Salad',            'description' => 'Banana blossom salad with fresh vegetables and dressing.',             'price' => 12],
            ['category' => 'Vegetables', 'name' => 'Fried Talong with Gunisang Uyap', 'description' => 'Fried eggplant sautéed with salted small shrimp.',                     'price' => 13],

            // ── Pasta ─────────────────────────────────────────────────────────
            // Priced slightly above noodles. ₱16–22.
            ['category' => 'Pasta', 'name' => 'Lasagna',              'description' => 'Layered pasta with meat sauce, béchamel, and melted cheese.',           'price' => 22],
            ['category' => 'Pasta', 'name' => 'Carbonara',            'description' => 'Creamy Filipino-style carbonara with bacon and cheese.',                 'price' => 18],
            ['category' => 'Pasta', 'name' => 'Baked Spaghetti',      'description' => 'Oven-baked spaghetti with meat sauce and melted cheese topping.',       'price' => 18],
            ['category' => 'Pasta', 'name' => 'Plain Spaghetti',      'description' => 'Classic Filipino-style sweet spaghetti with tomato meat sauce.',        'price' => 16],
            ['category' => 'Pasta', 'name' => 'Cheesy Bake Macaroni', 'description' => 'Creamy baked macaroni loaded with cheese and white sauce.',             'price' => 20],
            ['category' => 'Pasta', 'name' => 'Penne Pasta',          'description' => 'Penne pasta in rich tomato or cream sauce, oven finished.',             'price' => 19],

            // ── Desserts ─────────────────────────────────────────────────────
            // Prices range ₱11–14.
            ['category' => 'Desserts', 'name' => 'Potato Egg Salad',           'description' => 'Creamy potato and egg salad, a classic Filipino party staple.',        'price' => 12],
            ['category' => 'Desserts', 'name' => 'Macaroni Salad',             'description' => 'Sweet and creamy macaroni salad with fruits and mayonnaise.',            'price' => 12],
            ['category' => 'Desserts', 'name' => 'Fresh Fruit Salad',          'description' => 'Assorted fresh fruits in cream and condensed milk.',                    'price' => 14],
            ['category' => 'Desserts', 'name' => 'Fruit Cocktail Salad',       'description' => 'Mixed fruit cocktail in whipped cream dressing.',                       'price' => 12],
            ['category' => 'Desserts', 'name' => 'Mango Sago',                 'description' => 'Refreshing mango dessert soup with sago pearls.',                       'price' => 14],
            ['category' => 'Desserts', 'name' => 'Tapioca',                    'description' => 'Sweet tapioca pearls in coconut milk with fresh fruits.',               'price' => 12],
            ['category' => 'Desserts', 'name' => 'Chicken Salad',              'description' => 'Savory chicken salad with mixed vegetables and mayo dressing.',         'price' => 13],
            ['category' => 'Desserts', 'name' => 'Mango Graham Leche (8x6)',   'description' => 'Classic mango graham layered dessert — 8x6 pan.',                       'price' => 13],
            ['category' => 'Desserts', 'name' => 'Mango Graham Leche (14x12)', 'description' => 'Family-size mango graham layered dessert — 14x12 pan.',                 'price' => 14],
            ['category' => 'Desserts', 'name' => 'Mango Float',                'description' => 'Chilled mango float with graham crackers and cream layers.',             'price' => 13],
            ['category' => 'Desserts', 'name' => 'Banana Cream',               'description' => 'Sweet banana cream dessert with whipped topping.',                      'price' => 11],
            ['category' => 'Desserts', 'name' => 'Maja Blanca',                'description' => 'Soft coconut milk pudding topped with latik.',                          'price' => 11],
            ['category' => 'Desserts', 'name' => 'Bake Bico',                  'description' => 'Oven-baked sticky rice kakanin with coconut milk.',                     'price' => 11],

            // ── Cakes & Pastry ────────────────────────────────────────────────
            // Used as dessert substitutes. ₱12–18.
            ['category' => 'Cakes & Pastry', 'name' => 'Blueberry Cheese Cake',        'description' => 'Rich cheesecake topped with blueberry compote.',                       'price' => 18],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Cheese Cake',            'description' => 'Creamy cheesecake layered with fresh mango topping.',                  'price' => 18],
            ['category' => 'Cakes & Pastry', 'name' => 'Triple Chocolate Cheese Cake', 'description' => 'Decadent triple chocolate cheesecake.',                               'price' => 18],
            ['category' => 'Cakes & Pastry', 'name' => 'Brownies',                     'description' => 'Fudgy chocolate brownies baked to perfection.',                        'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Chocolate Cake',               'description' => 'Moist chocolate layer cake with chocolate frosting.',                  'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Ube Cake',                     'description' => 'Vibrant purple yam cake with ube halaya filling.',                     'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Yema Cake',                    'description' => 'Soft chiffon cake coated with creamy yema frosting.',                  'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Cake',                   'description' => 'Light sponge cake with fresh mango cream filling.',                    'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Cheese Cake',                  'description' => 'Classic no-bake cheesecake with graham crust.',                        'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Butter Cake',                  'description' => 'Classic moist butter cake with buttery frosting.',                     'price' => 12],
            ['category' => 'Cakes & Pastry', 'name' => 'Chiffon Cake',                 'description' => 'Light and airy chiffon cake.',                                         'price' => 12],
            ['category' => 'Cakes & Pastry', 'name' => 'Banana Cake',                  'description' => 'Moist banana cake with sweet cream frosting.',                         'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Mocha Cake',                   'description' => 'Coffee-flavored layer cake with mocha buttercream.',                   'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Carrot Cake',                  'description' => 'Moist spiced carrot cake with cream cheese frosting.',                 'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Black Forest Cake',            'description' => 'Classic black forest with cherries, cream, and chocolate sponge.',     'price' => 16],
            ['category' => 'Cakes & Pastry', 'name' => 'Custard Cake',                 'description' => 'Soft custard cake with silky smooth custard layer.',                   'price' => 14],
            ['category' => 'Cakes & Pastry', 'name' => 'Caramel Bar',                  'description' => 'Sweet and chewy caramel-topped bar pastry.',                           'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Chantilly Bar',                'description' => 'Light Chantilly cream bar with sponge base.',                          'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Pineapple Bar',                'description' => 'Pineapple-filled pastry bar with crumbly topping.',                    'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Mango Bar',                    'description' => 'Fresh mango bar pastry with cream filling.',                           'price' => 13],
            ['category' => 'Cakes & Pastry', 'name' => 'Creamy Leche Flan',            'description' => 'Classic silky smooth leche flan with rich caramel.',                   'price' => 14],
        ];

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [
                'category_id' => $catIds[$item['category']],
                'user_id'     => $userId,
                'name'        => $item['name'],
                'description' => $item['description'],
                'price'       => $item['price'],
                'image_path'  => null,
                'status'      => 'available',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('menu_items')->insert($rows);

        $this->command->info('✅ Categories (' . count($categories) . ') and Menu Items (' . count($rows) . ') seeded.');
    }
}
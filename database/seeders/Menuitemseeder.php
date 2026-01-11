<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menu items organized by category
        $menuItemsByCategory = [
            'Appetizers' => [
                ['name' => 'Lumpia Shanghai', 'description' => 'Crispy Filipino spring rolls with ground pork and vegetables', 'price' => 150.00],
                ['name' => 'Cheese Sticks', 'description' => 'Golden fried cheese wrapped in spring roll wrapper', 'price' => 120.00],
                ['name' => 'Calamares', 'description' => 'Deep-fried squid rings served with sweet chili sauce', 'price' => 180.00],
                ['name' => 'Dynamite', 'description' => 'Spicy cheese-stuffed chili peppers wrapped in lumpia wrapper', 'price' => 140.00],
                ['name' => 'Chicken Wings', 'description' => 'Crispy fried chicken wings with special glaze', 'price' => 160.00],
                ['name' => 'Pork Siomai', 'description' => 'Steamed pork dumplings with soy-calamansi sauce', 'price' => 130.00],
                ['name' => 'Fish Balls', 'description' => 'Deep-fried fish balls with sweet and spicy sauce', 'price' => 100.00],
                ['name' => 'Empanada', 'description' => 'Savory meat-filled pastry', 'price' => 135.00],
            ],
            'Main Course' => [
                ['name' => 'Lechon Kawali', 'description' => 'Crispy deep-fried pork belly served with liver sauce', 'price' => 280.00],
                ['name' => 'Beef Caldereta', 'description' => 'Tender beef stew in tomato sauce with vegetables', 'price' => 320.00],
                ['name' => 'Chicken Adobo', 'description' => 'Classic Filipino chicken braised in soy sauce and vinegar', 'price' => 220.00],
                ['name' => 'Pork Menudo', 'description' => 'Pork and liver stew with tomato sauce', 'price' => 240.00],
                ['name' => 'Beef Mechado', 'description' => 'Beef stewed with soy sauce and lemon juice', 'price' => 300.00],
                ['name' => 'Chicken Afritada', 'description' => 'Chicken stewed with potatoes, carrots, and bell peppers', 'price' => 230.00],
                ['name' => 'Pork Barbecue', 'description' => 'Grilled marinated pork skewers', 'price' => 200.00],
                ['name' => 'Kare-Kare', 'description' => 'Oxtail and vegetables in peanut sauce', 'price' => 350.00],
                ['name' => 'Beef Tapa', 'description' => 'Sweet cured beef strips', 'price' => 260.00],
                ['name' => 'Pork Binagoongan', 'description' => 'Pork cooked in shrimp paste', 'price' => 250.00],
            ],
            'Desserts' => [
                ['name' => 'Leche Flan', 'description' => 'Creamy caramel custard', 'price' => 180.00],
                ['name' => 'Buko Pandan', 'description' => 'Young coconut and pandan jelly salad', 'price' => 150.00],
                ['name' => 'Fruit Salad', 'description' => 'Mixed tropical fruits with cream', 'price' => 160.00],
                ['name' => 'Ube Halaya', 'description' => 'Purple yam jam dessert', 'price' => 140.00],
                ['name' => 'Maja Blanca', 'description' => 'Coconut pudding with corn', 'price' => 130.00],
                ['name' => 'Cassava Cake', 'description' => 'Sweet cassava pudding with cheese topping', 'price' => 170.00],
                ['name' => 'Brazo de Mercedes', 'description' => 'Meringue roll with custard filling', 'price' => 220.00],
                ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake', 'price' => 200.00],
            ],
            'Beverages' => [
                ['name' => 'Mango Shake', 'description' => 'Fresh mango blended with ice and milk', 'price' => 80.00],
                ['name' => 'Buko Juice', 'description' => 'Fresh young coconut juice', 'price' => 70.00],
                ['name' => 'Calamansi Juice', 'description' => 'Refreshing Filipino lime juice', 'price' => 60.00],
                ['name' => 'Iced Tea', 'description' => 'Freshly brewed sweetened iced tea', 'price' => 50.00],
                ['name' => 'Sago\'t Gulaman', 'description' => 'Tapioca pearls and jelly drink', 'price' => 55.00],
                ['name' => 'Four Seasons Juice', 'description' => 'Mixed fruit juice blend', 'price' => 75.00],
                ['name' => 'Coffee', 'description' => 'Hot or iced brewed coffee', 'price' => 65.00],
                ['name' => 'Bottled Water', 'description' => 'Purified bottled water', 'price' => 25.00],
            ],
            'Salads' => [
                ['name' => 'Caesar Salad', 'description' => 'Romaine lettuce with parmesan and croutons', 'price' => 140.00],
                ['name' => 'Garden Salad', 'description' => 'Fresh mixed greens with vinaigrette', 'price' => 120.00],
                ['name' => 'Potato Salad', 'description' => 'Creamy potato salad with eggs and pickles', 'price' => 110.00],
                ['name' => 'Coleslaw', 'description' => 'Shredded cabbage in creamy dressing', 'price' => 100.00],
                ['name' => 'Macaroni Salad', 'description' => 'Sweet style Filipino macaroni salad', 'price' => 130.00],
            ],
            'Soups' => [
                ['name' => 'Sinigang na Baboy', 'description' => 'Sour pork soup with vegetables', 'price' => 200.00],
                ['name' => 'Chicken Tinola', 'description' => 'Ginger-based chicken soup with papaya', 'price' => 180.00],
                ['name' => 'Bulalo', 'description' => 'Beef shank soup with bone marrow', 'price' => 250.00],
                ['name' => 'Corn Soup', 'description' => 'Creamy corn and chicken soup', 'price' => 150.00],
                ['name' => 'Molo Soup', 'description' => 'Wonton soup Filipino style', 'price' => 160.00],
            ],
            'Pasta & Rice' => [
                ['name' => 'Pancit Canton', 'description' => 'Stir-fried egg noodles with vegetables and meat', 'price' => 180.00],
                ['name' => 'Pancit Bihon', 'description' => 'Stir-fried rice noodles', 'price' => 170.00],
                ['name' => 'Spaghetti', 'description' => 'Filipino-style sweet spaghetti with hotdog', 'price' => 190.00],
                ['name' => 'Carbonara', 'description' => 'Creamy pasta with bacon', 'price' => 200.00],
                ['name' => 'Garlic Rice', 'description' => 'Fried rice with garlic', 'price' => 80.00],
                ['name' => 'Plain Rice', 'description' => 'Steamed white rice', 'price' => 50.00],
                ['name' => 'Yang Chow Fried Rice', 'description' => 'Special fried rice with shrimp and pork', 'price' => 150.00],
            ],
            'Seafood' => [
                ['name' => 'Grilled Bangus', 'description' => 'Grilled milkfish stuffed with tomatoes and onions', 'price' => 280.00],
                ['name' => 'Sweet and Sour Fish', 'description' => 'Fried fish fillet in sweet and sour sauce', 'price' => 300.00],
                ['name' => 'Buttered Shrimp', 'description' => 'Large shrimp sautéed in butter and garlic', 'price' => 350.00],
                ['name' => 'Fish Fillet', 'description' => 'Breaded and fried fish fillet', 'price' => 250.00],
                ['name' => 'Gambas', 'description' => 'Shrimp in spicy tomato sauce', 'price' => 320.00],
            ],
            'Grilled Items' => [
                ['name' => 'Grilled Chicken', 'description' => 'Marinated and grilled chicken pieces', 'price' => 230.00],
                ['name' => 'Grilled Pork Belly', 'description' => 'Tender grilled pork belly strips', 'price' => 260.00],
                ['name' => 'Pork Ribs', 'description' => 'BBQ pork ribs with special sauce', 'price' => 320.00],
                ['name' => 'Beef Ribs', 'description' => 'Grilled beef ribs', 'price' => 380.00],
                ['name' => 'Inasal na Manok', 'description' => 'Chicken marinated in annatto and grilled', 'price' => 240.00],
            ],
            'Vegetarian' => [
                ['name' => 'Pinakbet', 'description' => 'Mixed vegetables with shrimp paste', 'price' => 160.00],
                ['name' => 'Chopsuey', 'description' => 'Stir-fried mixed vegetables', 'price' => 150.00],
                ['name' => 'Ginataang Gulay', 'description' => 'Vegetables in coconut milk', 'price' => 140.00],
                ['name' => 'Tortang Talong', 'description' => 'Eggplant omelet', 'price' => 130.00],
                ['name' => 'Laing', 'description' => 'Taro leaves in coconut milk', 'price' => 170.00],
            ],
            'Breakfast Items' => [
                ['name' => 'Tapsilog', 'description' => 'Beef tapa with egg and rice', 'price' => 180.00],
                ['name' => 'Tocilog', 'description' => 'Sweet pork tocino with egg and rice', 'price' => 170.00],
                ['name' => 'Longsilog', 'description' => 'Longganisa sausage with egg and rice', 'price' => 160.00],
                ['name' => 'Pancakes', 'description' => 'Fluffy pancakes with syrup', 'price' => 140.00],
                ['name' => 'French Toast', 'description' => 'Classic French toast', 'price' => 130.00],
                ['name' => 'Champorado', 'description' => 'Chocolate rice porridge', 'price' => 100.00],
            ],
            'Side Dishes' => [
                ['name' => 'Ensaladang Talong', 'description' => 'Grilled eggplant salad', 'price' => 90.00],
                ['name' => 'Atchara', 'description' => 'Pickled papaya relish', 'price' => 70.00],
                ['name' => 'Pickled Vegetables', 'description' => 'Assorted pickled vegetables', 'price' => 80.00],
                ['name' => 'Steamed Vegetables', 'description' => 'Seasonal steamed vegetables', 'price' => 100.00],
                ['name' => 'French Fries', 'description' => 'Crispy golden fries', 'price' => 110.00],
            ],
        ];

        $categories = Category::with('user')->get();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        $totalItems = 0;

        foreach ($categories as $category) {
            // Get menu items for this category
            $categoryItems = $menuItemsByCategory[$category->name] ?? [];

            if (empty($categoryItems)) {
                continue;
            }

            // Add 3-8 items per category
            $numItems = rand(3, min(8, count($categoryItems)));
            $selectedItems = collect($categoryItems)->random($numItems);

            foreach ($selectedItems as $item) {
                // Add some price variation (±20%)
                $basePrice = $item['price'];
                $priceVariation = rand(-20, 20) / 100;
                $finalPrice = $basePrice * (1 + $priceVariation);

                MenuItem::create([
                    'category_id' => $category->id,
                    'user_id' => $category->user_id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => round($finalPrice, 2),
                    'status' => rand(1, 10) > 2 ? 'available' : 'unavailable', // 80% available
                ]);

                $totalItems++;
            }

            $this->command->info("Added {$numItems} items to category: {$category->name} (Caterer: {$category->user->business_name})");
        }

        $this->command->info("Total menu items created: {$totalItems}");
    }
}
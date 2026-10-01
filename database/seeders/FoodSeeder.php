<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $allDays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

        $foods = [
            [
                'name' => 'Chicken Teriyaki',
                'description' => 'Tender grilled chicken thigh glazed in authentic sweet-savory Japanese teriyaki sauce, garnished with toasted sesame seeds and fresh scallions.',
                'price' => 16.50,
                'image_url' => 'https://img.freepik.com/free-photo/chicken-teriyaki-don-with-egg-bowl_1339-125028.jpg',
                'type' => 'Lunch',
                'available_days' => $allDays,
                'prep_time' => '20 min',
                'calories' => 520,
                'difficulty' => 'Medium',
                'servings' => '1 person',
                'rating' => 4.8,
                'ingredients' => [
                    ['name' => 'Chicken', 'icon_url' => 'assets/icons/chicken.png'],
                    ['name' => 'Teriyaki Sauce', 'icon_url' => 'assets/icons/sauce.png'],
                    ['name' => 'Sesame', 'icon_url' => 'assets/icons/seeds.png'],
                    ['name' => 'Scallions', 'icon_url' => 'assets/icons/veggie.png'],
                ]
            ],
            [
                'name' => 'Hongkong Hainanese',
                'description' => 'Fragrant poached chicken served with garlic-infused basmati rice, accompanied by signature chili sambal and savory ginger dip.',
                'price' => 14.99,
                'image_url' => 'https://img.freepik.com/free-photo/fresh-gourmet-meal-beef-taco-salad-plate-generated-by-ai_188544-13382.jpg',
                'type' => 'Dinner',
                'available_days' => $allDays,
                'prep_time' => '25 min',
                'calories' => 610,
                'difficulty' => 'Medium',
                'servings' => '1 person',
                'rating' => 4.9,
                'ingredients' => [
                    ['name' => 'Chicken', 'icon_url' => 'assets/icons/chicken.png'],
                    ['name' => 'Basmati Rice', 'icon_url' => 'assets/icons/rice.png'],
                    ['name' => 'Garlic Ginger', 'icon_url' => 'assets/icons/garlic.png'],
                    ['name' => 'Cucumber', 'icon_url' => 'assets/icons/veggie.png'],
                ]
            ],
            [
                'name' => 'Hot & Sour Corn',
                'description' => 'Crispy golden sweet corn kernels wok-tossed with red chillies, cracked Sichuan pepper, and fresh aromatic spring onions.',
                'price' => 20.99,
                'image_url' => 'https://img.freepik.com/free-photo/gourmet-chicken-biryani-with-steamed-basmati-rice-generated-by-ai_188544-13480.jpg',
                'type' => 'Lunch',
                'available_days' => $allDays,
                'prep_time' => '15 min',
                'calories' => 380,
                'difficulty' => 'Easy',
                'servings' => '1 person',
                'rating' => 4.7,
                'ingredients' => [
                    ['name' => 'Sweet Corn', 'icon_url' => 'assets/icons/corn.png'],
                    ['name' => 'Red Chili', 'icon_url' => 'assets/icons/chili.png'],
                    ['name' => 'Black Pepper', 'icon_url' => 'assets/icons/pepper.png'],
                ]
            ],
            [
                'name' => 'Singapura Noodles',
                'description' => 'Curry-flavored wok noodles stir-fried with farm fresh eggs, bell peppers, crisp carrots, and rich savory spices.',
                'price' => 12.50,
                'image_url' => 'https://img.freepik.com/free-photo/penne-pasta-tomato-sauce-with-chicken-tomatoes-wooden-table_2829-19744.jpg',
                'type' => 'Breakfast',
                'available_days' => $allDays,
                'prep_time' => '15 min',
                'calories' => 450,
                'difficulty' => 'Easy',
                'servings' => '1 person',
                'rating' => 4.6,
                'ingredients' => [
                    ['name' => 'Rice Vermicelli', 'icon_url' => 'assets/icons/noodles.png'],
                    ['name' => 'Farm Egg', 'icon_url' => 'assets/icons/egg.png'],
                    ['name' => 'Curry Powder', 'icon_url' => 'assets/icons/spices.png'],
                ]
            ],
            [
                'name' => 'Everyday Pancakes',
                'description' => 'Fluffy golden buttermilk stacked pancakes topped with mixed wild berries, pure organic maple syrup, and fresh dairy butter.',
                'price' => 12.50,
                'image_url' => 'https://img.freepik.com/free-photo/pancakes-with-berries-honey_140725-4566.jpg',
                'type' => 'Breakfast',
                'available_days' => $allDays,
                'prep_time' => '15 min',
                'calories' => 420,
                'difficulty' => 'Easy',
                'servings' => '1 person',
                'rating' => 4.9,
                'ingredients' => [
                    ['name' => 'Buttermilk', 'icon_url' => 'assets/icons/milk.png'],
                    ['name' => 'Fresh Berries', 'icon_url' => 'assets/icons/berries.png'],
                    ['name' => 'Maple Honey', 'icon_url' => 'assets/icons/honey.png'],
                ]
            ],
            [
                'name' => 'Monday Special Squid',
                'description' => 'Tender ocean squid wok-tossed in sweet and tangy plum chili reduction with crunchy bell peppers.',
                'price' => 19.99,
                'image_url' => 'https://img.freepik.com/free-photo/delicious-food-table_23-2150857814.jpg',
                'type' => 'Lunch',
                'available_days' => ['Mon'],
                'prep_time' => '20 min',
                'calories' => 410,
                'difficulty' => 'Medium',
                'servings' => '1 person',
                'rating' => 4.8,
                'ingredients' => [
                    ['name' => 'Fresh Squid', 'icon_url' => 'assets/icons/seafood.png'],
                    ['name' => 'Plum Glaze', 'icon_url' => 'assets/icons/sauce.png'],
                    ['name' => 'Bell Peppers', 'icon_url' => 'assets/icons/veggie.png'],
                ]
            ],
            [
                'name' => 'Tuesday Tacos',
                'description' => 'Three warm artisanal tortillas loaded with seasoned tender beef, house guacamole, pico de gallo, and cotija cheese.',
                'price' => 15.00,
                'image_url' => 'https://img.freepik.com/free-photo/fresh-gourmet-meal-beef-taco-salad-plate-generated-by-ai_188544-13382.jpg',
                'type' => 'Lunch',
                'available_days' => ['Tue'],
                'prep_time' => '15 min',
                'calories' => 540,
                'difficulty' => 'Easy',
                'servings' => '3 tacos',
                'rating' => 4.7,
                'ingredients' => [
                    ['name' => 'Seasoned Beef', 'icon_url' => 'assets/icons/beef.png'],
                    ['name' => 'Guacamole', 'icon_url' => 'assets/icons/avocado.png'],
                    ['name' => 'Corn Tortilla', 'icon_url' => 'assets/icons/tortilla.png'],
                ]
            ],
            [
                'name' => 'Wednesday Wings',
                'description' => 'Crispy flame-glazed jumbo chicken wings tossed in smoky hickory BBQ glaze, served with creamy gorgonzola dip.',
                'price' => 27.12,
                'image_url' => 'https://img.freepik.com/free-photo/roasted-chicken-wings-barbecue-sauce-black-plate_2829-10887.jpg',
                'type' => 'Dinner',
                'available_days' => ['Wed'],
                'prep_time' => '25 min',
                'calories' => 680,
                'difficulty' => 'Medium',
                'servings' => '8 pcs',
                'rating' => 4.9,
                'ingredients' => [
                    ['name' => 'Jumbo Wings', 'icon_url' => 'assets/icons/chicken.png'],
                    ['name' => 'Hickory BBQ', 'icon_url' => 'assets/icons/sauce.png'],
                    ['name' => 'Blue Cheese', 'icon_url' => 'assets/icons/cheese.png'],
                ]
            ],
            [
                'name' => 'Weekend Salmon',
                'description' => 'Pan-seared Atlantic salmon fillet with crispy herb crust, grilled tender asparagus spears, and lemon-dill hollandaise.',
                'price' => 25.00,
                'image_url' => 'https://img.freepik.com/free-photo/grilled-salmon-fillet-with-asparagus-broccoli_2829-16997.jpg',
                'type' => 'Dinner',
                'available_days' => ['Fri', 'Sat', 'Sun'],
                'prep_time' => '20 min',
                'calories' => 490,
                'difficulty' => 'Hard',
                'servings' => '1 person',
                'rating' => 5.0,
                'ingredients' => [
                    ['name' => 'Atlantic Salmon', 'icon_url' => 'assets/icons/fish.png'],
                    ['name' => 'Asparagus', 'icon_url' => 'assets/icons/veggie.png'],
                    ['name' => 'Lemon Butter', 'icon_url' => 'assets/icons/lemon.png'],
                ]
            ],
            [
                'name' => 'Black Pepper Beef',
                'description' => 'Prime flank steak slices seared in high wok heat with cracked black peppercorn, garlic slivers, and sweet red onions.',
                'price' => 18.00,
                'image_url' => 'https://img.freepik.com/free-photo/gourmet-chicken-biryani-with-steamed-basmati-rice-generated-by-ai_188544-13480.jpg',
                'type' => 'Lunch',
                'available_days' => ['Mon', 'Wed', 'Fri'],
                'prep_time' => '20 min',
                'calories' => 560,
                'difficulty' => 'Medium',
                'servings' => '1 person',
                'rating' => 4.8,
                'ingredients' => [
                    ['name' => 'Flank Beef', 'icon_url' => 'assets/icons/beef.png'],
                    ['name' => 'Black Pepper', 'icon_url' => 'assets/icons/pepper.png'],
                    ['name' => 'Garlic', 'icon_url' => 'assets/icons/garlic.png'],
                ]
            ],
            [
                'name' => 'Continental Breakfast',
                'description' => 'Classic European morning spread featuring warm flaky croissants, strawberry preserve, sliced gouda cheese, and seasonal fresh fruits.',
                'price' => 15.00,
                'image_url' => 'https://img.freepik.com/free-photo/continental-breakfast-captured-from-above_23-2147952936.jpg',
                'type' => 'Breakfast',
                'available_days' => $allDays,
                'prep_time' => '10 min',
                'calories' => 430,
                'difficulty' => 'Easy',
                'servings' => '1 person',
                'rating' => 4.6,
                'ingredients' => [
                    ['name' => 'Butter Croissant', 'icon_url' => 'assets/icons/bread.png'],
                    ['name' => 'Fruit Jam', 'icon_url' => 'assets/icons/jam.png'],
                    ['name' => 'Gouda Cheese', 'icon_url' => 'assets/icons/cheese.png'],
                ]
            ],
            [
                'name' => 'Boxed Lunch Sandwich',
                'description' => 'Triple-decker smoked turkey and sharp cheddar club sandwich on toasted brioche bread, paired with sea-salt crisps.',
                'price' => 12.00,
                'image_url' => 'https://img.freepik.com/free-photo/sandwich_1339-1108.jpg',
                'type' => 'Lunch',
                'available_days' => $allDays,
                'prep_time' => '10 min',
                'calories' => 490,
                'difficulty' => 'Easy',
                'servings' => '1 box',
                'rating' => 4.7,
                'ingredients' => [
                    ['name' => 'Smoked Turkey', 'icon_url' => 'assets/icons/meat.png'],
                    ['name' => 'Cheddar', 'icon_url' => 'assets/icons/cheese.png'],
                    ['name' => 'Brioche Bread', 'icon_url' => 'assets/icons/bread.png'],
                ]
            ],
            [
                'name' => 'Italian Feast Buffet',
                'description' => 'Large catering selection featuring Penne all’Arrabbiata, slow-simmered rosemary meatballs, and Tuscan garlic breadsticks.',
                'price' => 35.00,
                'image_url' => 'https://img.freepik.com/free-photo/penne-pasta-tomato-sauce-with-chicken-tomatoes-wooden-table_2829-19744.jpg',
                'type' => 'Dinner',
                'available_days' => $allDays,
                'prep_time' => '40 min',
                'calories' => 850,
                'difficulty' => 'Hard',
                'servings' => '2-3 persons',
                'rating' => 4.9,
                'ingredients' => [
                    ['name' => 'Penne Pasta', 'icon_url' => 'assets/icons/pasta.png'],
                    ['name' => 'Meatballs', 'icon_url' => 'assets/icons/meat.png'],
                    ['name' => 'Garlic Baguette', 'icon_url' => 'assets/icons/bread.png'],
                ]
            ]
        ];

        foreach ($foods as $foodData) {
            $ingredients = $foodData['ingredients'];
            unset($foodData['ingredients']);

            $food = Food::updateOrCreate(
                ['name' => $foodData['name']],
                $foodData
            );

            // Re-seed ingredients
            $food->ingredients()->delete();
            foreach ($ingredients as $ing) {
                Ingredient::create([
                    'food_id' => $food->id,
                    'name' => $ing['name'],
                    'icon_url' => $ing['icon_url'],
                ]);
            }
        }
    }
}

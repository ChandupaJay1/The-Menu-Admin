<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class ExcelMenuSeeder extends Seeder
{
    public function run(): void
    {
        $allDays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

        $items = [
            // ==========================================
            // BREAKFAST - SRI LANKAN (Rows 3-27)
            // ==========================================
            [
                'name' => 'White Rice and Curry with Maldives fish',
                'type' => 'Breakfast',
                'price' => 380.00,
                'description' => 'Steamed white rice served with traditional dhal, pol sambol, seasonal vegetable curry and aromatic umbalakada (Maldives fish) sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 450, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with Katta karawala',
                'type' => 'Breakfast',
                'price' => 420.00,
                'description' => 'Fragrant white rice served with deep-fried spiced Katta dry fish, tempered dhal curry, gotukola sambol and spicy gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 480, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'White Rice and Curry with only vegetable',
                'type' => 'Breakfast',
                'price' => 300.00,
                'description' => 'Wholesome breakfast plate of white rice with 3 authentic Sri Lankan village vegetable curries, creamy dhal and coconut sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 380, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.6,
            ],
            [
                'name' => 'White Rice and Curry with fry egg',
                'type' => 'Breakfast',
                'price' => 350.00,
                'description' => 'Warm white rice paired with a crispy golden fried egg, tempered parippu curry, seeni sambol and fresh coconut milk gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 460, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with boil egg',
                'type' => 'Breakfast',
                'price' => 330.00,
                'description' => 'Nutritious breakfast bowl featuring steamed white rice, farm-fresh boiled egg, creamy yellow dhal and spicy pol sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1506084868230-bb9d95c24759?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 410, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Red Rice and Curry with Maldives fish',
                'type' => 'Breakfast',
                'price' => 390.00,
                'description' => 'Hearty healthy red unpolished rice with Maldives fish sambol, tempered dhal, winged bean salad and spicy curry.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 430, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Red Rice and Curry with Katta karawala',
                'type' => 'Breakfast',
                'price' => 430.00,
                'description' => 'Sri Lankan red raw rice paired with crisp fried Katta dry fish, coconut mallum, dhal curry and rich aromatic gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 470, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Red Rice and Curry with only vegetable',
                'type' => 'Breakfast',
                'price' => 310.00,
                'description' => 'Nutrient-rich red rice with 3 country-style organic vegetable curries, coconut sambol and tempered yellow lentils.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 360, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.6,
            ],
            [
                'name' => 'Red Rice and Curry with fry egg',
                'type' => 'Breakfast',
                'price' => 360.00,
                'description' => 'Steamed red rice served with a sunny-side crispy fried egg, creamy dhal and fresh tomato-onion lunu miris.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 440, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Red Rice and Curry with boil egg',
                'type' => 'Breakfast',
                'price' => 340.00,
                'description' => 'Healthy breakfast with fiber-rich red rice, hard-boiled egg, tempered dhal and grated coconut sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1506084868230-bb9d95c24759?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 390, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Kiri bath with Lunu miris',
                'type' => 'Breakfast',
                'price' => 280.00,
                'description' => 'Traditional rich coconut milk rice cut into diamond cakes, served with spicy chili-onion lunu miris with a squeeze of fresh lime.',
                'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 420, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Kiribath with Lunu miris and Malu Abulthial',
                'type' => 'Breakfast',
                'price' => 450.00,
                'description' => 'Creamy coconut milk rice cakes served alongside fiery lunu miris and authentic dark sour Ambul Thiyal fish steak.',
                'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 520, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Kiribath with Lunu miris and Red Chicken Curry',
                'type' => 'Breakfast',
                'price' => 480.00,
                'description' => 'Authentic Kiribath served with fiery lunu miris and slow-simmered rich red Sri Lankan village style chicken curry.',
                'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => '2 Parotta with chickpeas curry',
                'type' => 'Breakfast',
                'price' => 320.00,
                'description' => 'Two flaky golden Sri Lankan parottas served with spiced Kadala (chickpeas) curry tempered with coconut slices and mustard.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 510, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => '2 Parotta with Red Chicken Curry',
                'type' => 'Breakfast',
                'price' => 480.00,
                'description' => 'Two warm layered parottas served with tender chicken pieces simmered in spicy, roasted Sri Lankan red curry sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 620, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Dose set',
                'type' => 'Breakfast',
                'price' => 350.00,
                'description' => 'Crispy golden South Indian style Dosa set served with piping hot vegetable sambar, red coconut chutney and mint chutney.',
                'image_url' => 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 390, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'String hoppers with curry and sambal',
                'type' => 'Breakfast',
                'price' => 320.00,
                'description' => '10 light and fluffy steamed rice flour string hoppers served with creamy yellow coconut milk sodhi and spicy pol sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 360, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'String hoppers with curry, sambal and egg',
                'type' => 'Breakfast',
                'price' => 380.00,
                'description' => 'Steamed string hoppers served with aromatic kiri hodi gravy, spicy coconut sambol and a whole boiled egg.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 430, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Tosts bread with Curry and sambal',
                'type' => 'Breakfast',
                'price' => 290.00,
                'description' => 'Crispy toasted bakery bread slices served with traditional Sri Lankan dhal curry and spicy homemade pol sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '8 min', 'calories' => 340, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.6,
            ],
            [
                'name' => 'Special mix grains rice with veggie curry',
                'type' => 'Breakfast',
                'price' => 420.00,
                'description' => 'Nutritious fitness blend of steamed mixed grains, millet, and red rice with tempered organic vegetables and light lentil broth.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 380, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Special mix grains rice with chicken curry',
                'type' => 'Breakfast',
                'price' => 550.00,
                'description' => 'High-protein breakfast featuring ancient mixed grains rice served with aromatic lean chicken curry and green mallum.',
                'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 510, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],

            // ==========================================
            // BREAKFAST - ENGLISH (Rows 31-36)
            // ==========================================
            [
                'name' => 'Avocado & chicken wrap',
                'type' => 'Breakfast',
                'price' => 650.00,
                'description' => 'Warm tortilla wrap stuffed with fresh Hass avocado slices, grilled breast chicken strips, crisp lettuce, and garlic yoghurt dressing.',
                'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 480, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Avocado & chicken sandwich',
                'type' => 'Breakfast',
                'price' => 580.00,
                'description' => 'Multi-grain toasted sourdough sandwich loaded with seasoned pulled chicken, mashed ripe avocado, sliced tomato, and creamy mayo.',
                'image_url' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 460, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Avocado & egg sandwich',
                'type' => 'Breakfast',
                'price' => 480.00,
                'description' => 'Toasted farmhouse bread filled with sliced hard-boiled eggs, smashed avocado seasoned with sea salt, pepper and a dash of lemon.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 410, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Avocado & veggie sandwich',
                'type' => 'Breakfast',
                'price' => 420.00,
                'description' => 'Fresh crunchy vegetable sandwich with butter avocado spread, English cucumber, juicy ripe tomatoes and tender arugula greens.',
                'image_url' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '8 min', 'calories' => 350, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Cheese and veggie omelet with 3 eggs',
                'type' => 'Breakfast',
                'price' => 450.00,
                'description' => 'Fluffy 3-egg omelet folded with melted cheddar cheese, bell peppers, button mushrooms, spinach, and toasted buttered bread.',
                'image_url' => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 490, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Vega omelet with 3 eggs',
                'type' => 'Breakfast',
                'price' => 380.00,
                'description' => 'Wholesome three-egg country omelet loaded with diced tomatoes, onions, green chillies and fresh garden herbs.',
                'image_url' => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '8 min', 'calories' => 370, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],

            // ==========================================
            // LUNCH - SRI LANKAN (Rows 42-71)
            // ==========================================
            [
                'name' => 'White Rice and Curry with only vegetable',
                'type' => 'Lunch',
                'price' => 350.00,
                'description' => 'Sri Lankan style lunch featuring white steamed rice, dhal, pumpkin curry, beans, spicy pol sambol and crispy papadum.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 480, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'White Rice and Curry with Chicken curry',
                'type' => 'Lunch',
                'price' => 550.00,
                'description' => 'Generous portion of white rice served with spiced chicken curry, tempered lentils, seasonal vegetable, mallum, and papadum.',
                'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 640, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with Fry fish',
                'type' => 'Lunch',
                'price' => 520.00,
                'description' => 'Steamed rice with crispy pan-fried sea fish marinated in turmeric and chili, served with 3 vegetable sides and spicy gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with malu Abulthiyal',
                'type' => 'Lunch',
                'price' => 540.00,
                'description' => 'Classic southern Sri Lankan dark peppery tuna fish Ambulthiyal cooked with goraka, accompanied by white rice and traditional accompaniments.',
                'image_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 560, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with miris malu',
                'type' => 'Lunch',
                'price' => 540.00,
                'description' => 'Spicy red chili fish curry cooked in thick coconut milk, served with hot white rice, tempered dhal, and fresh leaf salad.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 550, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with fry egg',
                'type' => 'Lunch',
                'price' => 400.00,
                'description' => 'Steamed white rice paired with a golden sunny egg, dhal, tempered potato curry, pol sambol, and crispy papadum.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 490, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'White Rice and Curry with boil egg',
                'type' => 'Lunch',
                'price' => 380.00,
                'description' => 'Nutritious lunch of steamed white rice, whole boiled egg, yellow dhal, vegetable curry, and fresh coconut sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1506084868230-bb9d95c24759?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 460, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'White Rice and Curry with beef curry',
                'type' => 'Lunch',
                'price' => 650.00,
                'description' => 'Slow-cooked tender beef chuck curry infused with roasted spices and curry leaves, accompanied by rice and country vegetable curries.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 710, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with pork curry',
                'type' => 'Lunch',
                'price' => 620.00,
                'description' => 'Famous Sri Lankan black pork curry with roasted black curry powder, goraka, and black pepper, served with white rice and sides.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 730, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and vegetable',
                'type' => 'Lunch',
                'price' => 580.00,
                'description' => 'Luxurious creamy coconut cashew nut (kaju) curry simmered with green peas, served with white rice, dhal, and vegetable curries.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 590, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and Chicken curry',
                'type' => 'Lunch',
                'price' => 750.00,
                'description' => 'Grand feast plate of steamed white rice, tender spiced chicken curry, rich creamy cashew nut curry, dhal, and papadum.',
                'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 780, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and Fry fish',
                'type' => 'Lunch',
                'price' => 720.00,
                'description' => 'Deluxe combination of crispy fried ocean fish steak and rich coconut cashew nut curry served with white rice and vegetables.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 720, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and malu Abulthiyal',
                'type' => 'Lunch',
                'price' => 740.00,
                'description' => 'Heritage combination of tangy southern Ambulthiyal fish and rich, sweet coconut cashew curry on a bed of steamed white rice.',
                'image_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 710, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and miris malu',
                'type' => 'Lunch',
                'price' => 740.00,
                'description' => 'Fiery spicy red chili fish curry paired harmoniously with soothing creamy cashew nut curry and fragrant white rice.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 700, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and prawns',
                'type' => 'Lunch',
                'price' => 850.00,
                'description' => 'Succulent lagoon prawns in a spicy coconut gravy accompanied by rich royal cashew nut curry, steamed rice, and sides.',
                'image_url' => 'https://images.unsplash.com/photo-1559742811-822873691df8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 760, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and fry egg',
                'type' => 'Lunch',
                'price' => 620.00,
                'description' => 'Crispy fried egg and luscious cashew nut curry served over fluffy white rice with parippu and fresh coconut sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 610, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and boil egg',
                'type' => 'Lunch',
                'price' => 600.00,
                'description' => 'Boiled egg and tender simmered whole cashews in yellow coconut milk sauce, served with white rice and vegetables.',
                'image_url' => 'https://images.unsplash.com/photo-1506084868230-bb9d95c24759?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 590, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and beef curry',
                'type' => 'Lunch',
                'price' => 820.00,
                'description' => 'Rich, peppery Sri Lankan beef curry paired with indulgent whole cashew curry, steamed rice, vegetable sides, and papadum.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 840, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'White Rice and Curry with cashew curry and pork curry',
                'type' => 'Lunch',
                'price' => 790.00,
                'description' => 'Traditional roasted black pork curry coupled with velvety cashew nut curry and warm white rice.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 860, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Chicken Biriyani',
                'type' => 'Lunch',
                'price' => 950.00,
                'description' => 'Fragrant long-grain basmati rice cooked in ghee and aromatic Ceylon spices, served with spiced roast chicken leg, boiled egg, mint sambol, and Malay pickle.',
                'image_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '25 min', 'calories' => 850, 'difficulty' => 'Chef Special', 'servings' => '1-2 persons', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Chicken fried rice',
                'type' => 'Lunch',
                'price' => 780.00,
                'description' => 'Wok-tossed aromatic rice with tender shredded chicken, scrambled eggs, carrots, leeks, and signature chili paste with chicken gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 710, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Red Chicken curry with chapathi, veg salads and 3 boil eggs',
                'type' => 'Lunch',
                'price' => 680.00,
                'description' => 'High-protein power meal: 3 soft whole-wheat chapathis, rich red chicken curry, 3 hard-boiled eggs and crisp tossed garden salad.',
                'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 720, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chicken Slad',
                'type' => 'Lunch',
                'price' => 580.00,
                'description' => 'Fresh crisp lettuce, cherry tomatoes, cucumbers and grilled seasoned chicken breast topped with house olive oil and lemon vinaigrette.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 380, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Chicken Bowl with Salad',
                'type' => 'Lunch',
                'price' => 690.00,
                'description' => 'Healthy lunch bowl layered with seasoned brown rice, grilled chicken cubes, avocado slices, corn, beans, and creamy dressing.',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 520, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Beef Bowl with Salad',
                'type' => 'Lunch',
                'price' => 780.00,
                'description' => 'Nutritious bowl filled with tender seared beef strips, quinoa, mixed salad greens, roasted peppers, and sesame dressing.',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Pork Bowl with Salad',
                'type' => 'Lunch',
                'price' => 750.00,
                'description' => 'Savory bowl featuring roasted spiced pork bites, steamed jasmine rice, pickled cucumbers, shredded carrots, and spicy mayo.',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 610, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Smashed potato with grill Chicken',
                'type' => 'Lunch',
                'price' => 850.00,
                'description' => 'Juicy herb-marinated grilled chicken breast served over buttery garlic mashed potatoes with pepper sauce and buttered greens.',
                'image_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 650, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Smashed potato with grill Fish',
                'type' => 'Lunch',
                'price' => 820.00,
                'description' => 'Pan-seared ocean fish fillet served over rustic skin-on mashed potatoes with lemon garlic butter sauce and asparagus.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'smashed sweet potatoes with grillChicken',
                'type' => 'Lunch',
                'price' => 880.00,
                'description' => 'Healthy gym meal: tender grilled chicken breast paired with naturally sweet smashed sweet potatoes and steamed broccoli.',
                'image_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 590, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'smashed sweet potatoes with grill fish',
                'type' => 'Lunch',
                'price' => 850.00,
                'description' => 'Grilled white fish fillet seasoned with herbs and lime, served on a bed of roasted crushed sweet potatoes with garden salad.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 540, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],

            // ==========================================
            // DINNER - SRI LANKAN (Rows 76-137)
            // ==========================================
            [
                'name' => 'Special Sri lankan style Chicken fried rice',
                'type' => 'Dinner',
                'price' => 780.00,
                'description' => 'Signature wok-charred fried rice with chicken, scrambled egg, green leeks, spring onion, spicy chili paste and chili gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 720, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Chicken set menu',
                'type' => 'Dinner',
                'price' => 850.00,
                'description' => 'Complete dinner combo: chicken fried rice or noodles, devilled chicken, fried egg, vegetable chop suey, chili paste, and gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 880, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style vegetable fried rice',
                'type' => 'Dinner',
                'price' => 580.00,
                'description' => 'Fragrant wok-fried basmati rice tossed with garden fresh carrots, leeks, baby corn, mushrooms, and fiery vegetarian chili paste.',
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 520, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Special Sri lankan style egg fried rice',
                'type' => 'Dinner',
                'price' => 650.00,
                'description' => 'Classic dinner favorite featuring wok-tossed basmati rice with double scrambled egg, scallions, sweet soy, and chili paste.',
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 580, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Special Sri lankan style seafood fried rice',
                'type' => 'Dinner',
                'price' => 980.00,
                'description' => 'Deluxe ocean fried rice packed with fresh prawns, cuttlefish rings, fish fillet, egg ribbons, scallions and spicy paste.',
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 790, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style BBQ Chicken with potato mixed rice',
                'type' => 'Dinner',
                'price' => 890.00,
                'description' => 'Smoky glazed BBQ chicken quarter served over aromatic potato-infused seasoned rice with coleslaw and house BBQ glaze.',
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 820, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style BBQ beef with potato mixed rice',
                'type' => 'Dinner',
                'price' => 980.00,
                'description' => 'Tender grilled BBQ beef steak slices paired with spiced potato seasoned rice, grilled corn, and fresh garden salad.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '25 min', 'calories' => 860, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Chicken fried noodles',
                'type' => 'Dinner',
                'price' => 750.00,
                'description' => 'Wok-charred wheat noodles with shredded chicken, crunchy cabbage, shredded carrots, leeks, egg ribbons, and chili sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 690, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Special Sri lankan style vegetable fried noodles',
                'type' => 'Dinner',
                'price' => 550.00,
                'description' => 'Quick stir-fried noodles with crisp seasonal farm vegetables, fragrant sesame oil, garlic, and hot chili paste.',
                'image_url' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 480, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Special Sri lankan style egg fried noodles',
                'type' => 'Dinner',
                'price' => 620.00,
                'description' => 'Comforting evening noodle dish tossed with scrambled eggs, onions, bell peppers, celery, and sweet-spicy seasoning.',
                'image_url' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 540, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Special Sri lankan style seafood fried noodles',
                'type' => 'Dinner',
                'price' => 950.00,
                'description' => 'Stir-fried noodles loaded with sea prawns, calamari, white fish chunks, egg, and fresh scallions with hot chili dip.',
                'image_url' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 760, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'String hopper set - Chicken',
                'type' => 'Dinner',
                'price' => 580.00,
                'description' => '15 steamed string hoppers served with flavorful chicken curry, creamy coconut sodhi gravy, and fresh coconut sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 540, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'String hopper set - Kiri Malu',
                'type' => 'Dinner',
                'price' => 540.00,
                'description' => 'Steamed string hoppers served with mild white fish curry cooked in rich coconut milk, dhal sodhi, and pol sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 510, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'String hopper set - Dhal Curry',
                'type' => 'Dinner',
                'price' => 380.00,
                'description' => 'Light evening set of 15 steamed string hoppers with aromatic tempered lentil curry and zesty lime pol sambol.',
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 420, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Special Sri lankan style Pol Roti set with Chicken Curry',
                'type' => 'Dinner',
                'price' => 520.00,
                'description' => '3 warm grated coconut flatbreads (pol roti) served with spicy red chicken curry and traditional chili-onion lunu miris.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 590, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Pol Roti set with Beef Curry',
                'type' => 'Dinner',
                'price' => 620.00,
                'description' => '3 fresh pol rotis served alongside tender spiced beef curry, dhal gravy, and freshly ground spicy lunu miris.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 640, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Special Sri lankan style Pol Roti set with Black Pork Curry',
                'type' => 'Dinner',
                'price' => 590.00,
                'description' => '3 pol rotis paired with slow-cooked dark roasted pork curry and coconut sambol for an authentic village dinner.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 670, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Chapathi with Red chicken Curry',
                'type' => 'Dinner',
                'price' => 520.00,
                'description' => '3 soft puffed chapathis made from whole wheat flour, accompanied by fiery red chicken gravy and mint raita.',
                'image_url' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 530, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chapathi with beef Curry',
                'type' => 'Dinner',
                'price' => 620.00,
                'description' => '3 whole wheat chapathis paired with succulent slow-cooked Sri Lankan beef curry and fresh onion salad.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 590, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chapathi with pork Curry',
                'type' => 'Dinner',
                'price' => 590.00,
                'description' => 'Warm chapathis served with peppery roasted pork curry cooked in aromatic herbs and rich sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 610, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chapathi with vegetable stew',
                'type' => 'Dinner',
                'price' => 420.00,
                'description' => 'Light and healthy evening dinner: 3 chapathis served with creamy coconut milk vegetable stew and fresh herbs.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 390, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Chapathi with paneer butter masala - veg',
                'type' => 'Dinner',
                'price' => 550.00,
                'description' => 'Fresh cottage cheese cubes simmered in a luscious creamy tomato butter gravy, served with 3 soft chapathis.',
                'image_url' => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Parotta with Red chicken Curry',
                'type' => 'Dinner',
                'price' => 540.00,
                'description' => 'Crispy flaky layered parottas served with tender chicken cooked in roasted red Ceylon spices and rich gravy.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 680, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Parotta with beef Curry',
                'type' => 'Dinner',
                'price' => 640.00,
                'description' => 'Flaky golden parottas served with rich dark peppery beef curry and sliced red onion garnish.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 740, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Parotta with pork Curry',
                'type' => 'Dinner',
                'price' => 610.00,
                'description' => 'Flaky parottas served with famous Sri Lankan black pork curry rich in goraka, black pepper and curry leaf aroma.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 760, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Parotta with vegetable stew',
                'type' => 'Dinner',
                'price' => 440.00,
                'description' => 'Two crisp parottas paired with delicate Kerala-style coconut milk vegetable stew and tempered mustard seeds.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 520, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Parotta with paneer butter masala - veg',
                'type' => 'Dinner',
                'price' => 570.00,
                'description' => 'Golden flaky parottas served with buttery, mild tomato and cream paneer curry, garnished with fresh coriander.',
                'image_url' => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 670, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chicken wrap',
                'type' => 'Dinner',
                'price' => 580.00,
                'description' => 'Toasted flatbread wrap packed with succulent spiced chicken chunks, shredded lettuce, bell peppers, and garlic sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 490, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Vegetable wrap',
                'type' => 'Dinner',
                'price' => 420.00,
                'description' => 'Light and refreshing grilled tortilla wrap filled with roasted zucchini, bell peppers, corn, avocado, and herb mayo.',
                'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 380, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Beef wrap',
                'type' => 'Dinner',
                'price' => 680.00,
                'description' => 'Hearty evening wrap stuffed with grilled beef tenderloin slices, caramelized onions, melted cheese, and smoky chipotle mayo.',
                'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Pork wrap',
                'type' => 'Dinner',
                'price' => 650.00,
                'description' => 'Flavorful toasted wrap stuffed with slow-roasted BBQ pulled pork, tangy coleslaw, and spicy mustard sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 610, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Chicken soup with toasted breds',
                'type' => 'Dinner',
                'price' => 480.00,
                'description' => 'Comforting clear chicken broth loaded with chicken shreds, sweet carrots, celery, herbs, served with golden toasted bread.',
                'image_url' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '12 min', 'calories' => 320, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Seafood Soup with garlic breds',
                'type' => 'Dinner',
                'price' => 620.00,
                'description' => 'Rich aromatic seafood chowder packed with prawns, fish, and squid rings, served with crisp buttery garlic bread slices.',
                'image_url' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 420, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Sweet corn soup garlic breads',
                'type' => 'Dinner',
                'price' => 450.00,
                'description' => 'Velvety Chinese-style sweet corn soup with egg drops and spring onions, accompanied by two slices of warm garlic bread.',
                'image_url' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 340, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Kizhi Poratta - Chicken',
                'type' => 'Dinner',
                'price' => 920.00,
                'description' => 'Kerala specialty: layered parotta soaked in spicy chicken roast gravy, wrapped in a banana leaf and steam-grilled to perfection.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '25 min', 'calories' => 840, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 5.0,
            ],
            [
                'name' => 'Kizhi Poratta - Beef',
                'type' => 'Dinner',
                'price' => 1050.00,
                'description' => 'Flaky parotta packed with rich spicy beef roast and banana leaf steam-infused curry sauce, bursting with southern flavor.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '25 min', 'calories' => 920, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 5.0,
            ],
            [
                'name' => 'Kizhi Poratta - Pork',
                'type' => 'Dinner',
                'price' => 990.00,
                'description' => 'Tender dark pork roast curry layered inside soft parotta, wrapped in smoked banana leaf and slow-griddled.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '25 min', 'calories' => 950, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 5.0,
            ],
            [
                'name' => 'Spagedy',
                'type' => 'Dinner',
                'price' => 750.00,
                'description' => 'Al dente Italian spaghetti tossed in rich herb-infused slow-cooked tomato marinara sauce, garnished with parmesan.',
                'image_url' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 560, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Creamy Pasta with bone less Chicken',
                'type' => 'Dinner',
                'price' => 890.00,
                'description' => 'Fettuccine pasta in a decadent garlic parmesan Alfredo cream sauce, tossed with tender grilled boneless chicken strips and herbs.',
                'image_url' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281728?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 740, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Creamy Pasta with Prawns',
                'type' => 'Dinner',
                'price' => 1080.00,
                'description' => 'Penne pasta tossed with juicy pan-seared ocean king prawns in a rich white wine garlic cream sauce with parsley.',
                'image_url' => 'https://images.unsplash.com/photo-1559742811-822873691df8?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 780, 'difficulty' => 'Chef Special', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Noodles Soup - Chicken',
                'type' => 'Dinner',
                'price' => 550.00,
                'description' => 'Steaming bowl of Asian style ramen noodles in seasoned chicken broth, topped with sliced chicken, bok choy, and a boiled egg.',
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '15 min', 'calories' => 480, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Noodles Soup - Beef',
                'type' => 'Dinner',
                'price' => 650.00,
                'description' => 'Rich aromatic 5-spice beef broth poured over springy noodles, tender braised beef shank slices, greens and chili oil.',
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 540, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Noodles Soup - Pork',
                'type' => 'Dinner',
                'price' => 620.00,
                'description' => 'Japanese tonkotsu style rich cloudy broth with tender pork slices, springy noodles, bamboo shoots and scallions.',
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 580, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'BBQ Chicken with Vegetable salad',
                'type' => 'Dinner',
                'price' => 850.00,
                'description' => 'Smoked barbecue chicken leg quarter glazed in tangy sauce, served alongside an oversized crisp garden salad with vinaigrette.',
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 560, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'BBQ Fish with Vegetable salad',
                'type' => 'Dinner',
                'price' => 820.00,
                'description' => 'Fresh charcoal-grilled whole fish steak with smoky paprika marinade, served with fresh leafy green salad and lemon wedges.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 480, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'BBQ Beef with Vegetable salad',
                'type' => 'Dinner',
                'price' => 950.00,
                'description' => 'Tender flame-grilled marinated beef medallions served with charred peppers, onions, tossed greens, and honey-mustard dressing.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 610, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => '2 Boil eggs with Vegetable salad',
                'type' => 'Dinner',
                'price' => 420.00,
                'description' => 'Low-calorie healthy dinner: two farm-fresh boiled eggs on a bed of mixed salad greens, cucumbers, tomatoes, and balsamic reduction.',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '10 min', 'calories' => 310, 'difficulty' => 'Easy', 'servings' => '1 person', 'rating' => 4.7,
            ],
            [
                'name' => 'Grill Chicken with Vegetables',
                'type' => 'Dinner',
                'price' => 850.00,
                'description' => 'Rosemary and thyme grilled lean chicken breast served with steamed broccoli, roasted carrots, and black pepper jus.',
                'image_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '20 min', 'calories' => 520, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Grill Fish with Vegetables',
                'type' => 'Dinner',
                'price' => 820.00,
                'description' => 'Pan-grilled white fish fillet seasoned with sea salt and cracked pepper, served with butter-sautéed French beans and lemon butter sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '18 min', 'calories' => 460, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
            [
                'name' => 'Grill Beef with Vegetables',
                'type' => 'Dinner',
                'price' => 950.00,
                'description' => 'Grilled beef tenderloin cooked to tender perfection, served with herb-roasted baby potatoes, grilled asparagus, and mushroom sauce.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 640, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.9,
            ],
            [
                'name' => 'Grill pork with Vegetables',
                'type' => 'Dinner',
                'price' => 920.00,
                'description' => 'Juicy grilled pork chop glazed with apple cider sauce, served with roasted root vegetables and garlic butter green beans.',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
                'prep_time' => '22 min', 'calories' => 680, 'difficulty' => 'Medium', 'servings' => '1 person', 'rating' => 4.8,
            ],
        ];

        // Update existing dollar-priced items to Rs. if they are less than 100
        Food::where('price', '<', 100)->get()->each(function ($food) {
            $food->update([
                'price' => round($food->price * 40, -1) + 50,
            ]);
        });

        $countInserted = 0;
        $countUpdated = 0;

        foreach ($items as $data) {
            $food = Food::where('name', $data['name'])
                        ->where('type', $data['type'])
                        ->first();

            $data['available_days'] = $allDays;

            if ($food) {
                $food->update($data);
                $countUpdated++;
            } else {
                Food::create($data);
                $countInserted++;
            }
        }

        // Ensure all foods have basic ingredients for recipes & Flutter detailed view
        Food::with('ingredients')->get()->each(function ($food) {
            if ($food->ingredients->isEmpty()) {
                $defaultIngredients = [
                    ['name' => 'Spices & Herbs', 'icon_url' => 'assets/icons/sauce.png'],
                    ['name' => 'Coconut Milk', 'icon_url' => 'assets/icons/veggie.png'],
                    ['name' => 'Basmati Rice', 'icon_url' => 'assets/icons/rice.png'],
                    ['name' => 'Curry Leaves', 'icon_url' => 'assets/icons/veggie.png'],
                ];
                if (stripos($food->name, 'chicken') !== false) {
                    $defaultIngredients[0] = ['name' => 'Fresh Chicken', 'icon_url' => 'assets/icons/chicken.png'];
                } elseif (stripos($food->name, 'fish') !== false || stripos($food->name, 'malu') !== false) {
                    $defaultIngredients[0] = ['name' => 'Fresh Fish', 'icon_url' => 'assets/icons/fish.png'];
                } elseif (stripos($food->name, 'egg') !== false) {
                    $defaultIngredients[0] = ['name' => 'Farm Eggs', 'icon_url' => 'assets/icons/egg.png'];
                } elseif (stripos($food->name, 'beef') !== false) {
                    $defaultIngredients[0] = ['name' => 'Beef', 'icon_url' => 'assets/icons/meat.png'];
                } elseif (stripos($food->name, 'pork') !== false) {
                    $defaultIngredients[0] = ['name' => 'Pork', 'icon_url' => 'assets/icons/meat.png'];
                } elseif (stripos($food->name, 'prawn') !== false || stripos($food->name, 'seafood') !== false) {
                    $defaultIngredients[0] = ['name' => 'Sea Prawns', 'icon_url' => 'assets/icons/shrimp.png'];
                }

                foreach ($defaultIngredients as $ing) {
                    $food->ingredients()->create($ing);
                }
            }
        });

        $this->command->info("ExcelMenuSeeder completed! Inserted: {$countInserted}, Updated: {$countUpdated}, Total in DB: " . Food::count());
    }
}

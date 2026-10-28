<?php

namespace App\Console\Commands;

use App\Models\Food;
use App\Models\Ingredient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SyncMealDescriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:sync-descriptions {--file= : Path to the meal description Excel file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync food descriptions, calories, and ingredients from meal description Excel spreadsheet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->option('file') ?: 'E:/NerdTech Labs/Projects/The Menu/meal discription.xlsx';

        if (!file_exists($filePath)) {
            $this->error("Excel file not found at: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("Loading Excel file: {$filePath}");
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $allFoods = Food::all()->keyBy('id');
        $this->info("Found {$allFoods->count()} existing food records in database.");

        // Manual mapping for dishes with slight naming variations between Excel and DB
        $manualMapping = [
            3   => 14,  // White Rice and Curry with Dried sprats -> ID 14
            8   => 19,  // Red Rice and Curry with dried Sprats -> ID 19
            17  => 28,  // Breakfast: 2 Parotta with Red Chicken Curry -> ID 28
            19  => 30,  // String hoppers with curry and coconut sambal -> ID 30
            20  => 31,  // String hoppers with curry, coconut sambal and egg -> ID 31
            21  => 32,  // Tosts bread with Curry and coconut sambal -> ID 32
            43  => 63,  // Chicken Salad -> ID 63 (fixing 'Chicken Slad')
            72  => 93,  // Dinner: 2 Parotta with Red chicken Curry -> ID 93
            81  => 102, // Chicken soup with toasted breads -> ID 102 (fixing 'Chicken soup with toasted breds')
            82  => 103, // Seafood Soup with garlic breads -> ID 103 (fixing 'Seafood Soup with garlic breds')
            105 => 40,  // Veggie omelet with 3 eggs -> ID 40 (fixing 'Vega omelet with 3 eggs')
        ];

        // Specific copy-paste fixes for obvious description typos in raw Excel
        $descCorrections = [
            18  => "Crispy South Indian-style dosas served with warm sambar and fresh coconut chutney.",
            42  => "Sri Lankan red chicken curry served with soft chapathi, fresh vegetable salad, and 3 boiled eggs.",
            55  => "Sri Lankan style BBQ chicken served with savoury potato mixed rice and salad.",
            56  => "Sri Lankan style BBQ beef served with savoury potato mixed rice and salad.",
            81  => "Hearty chicken soup served with warm toasted bread.",
            92  => "Succulent BBQ chicken served with a crisp garden vegetable salad.",
            93  => "Grilled BBQ fish served with a crisp garden vegetable salad.",
            94  => "Tender BBQ beef served with a crisp garden vegetable salad.",
            95  => "Two boiled eggs served with a crisp garden vegetable salad.",
            101 => "Fresh avocado and seasoned chicken served between slices of soft bread.",
            103 => "Fresh avocado and crisp seasonal garden vegetables served between slices of soft bread.",
        ];

        $updates = [];
        $matchedFoodIds = [];

        foreach ($data as $rowIndex => $row) {
            if ($rowIndex <= 2) continue; // Skip headers
            $dish = trim((string)($row['A'] ?? ''));
            if ($dish === '') continue;

            $foodId = null;
            if (isset($manualMapping[$rowIndex])) {
                $foodId = $manualMapping[$rowIndex];
            } else {
                $cleanDish = $this->cleanStr($dish);
                foreach ($allFoods as $f) {
                    if (!isset($matchedFoodIds[$f->id]) && $this->cleanStr($f->name) === $cleanDish) {
                        $foodId = $f->id;
                        break;
                    }
                }
            }

            if (!$foodId || !isset($allFoods[$foodId])) {
                $this->warn("Unmatched Excel row {$rowIndex}: '{$dish}'");
                continue;
            }

            $matchedFoodIds[$foodId] = true;

            $desc = $descCorrections[$rowIndex] ?? trim((string)($row['D'] ?? ''));
            if ($desc === '') {
                $desc = "Freshly prepared {$dish} made with authentic Sri Lankan ingredients and traditional spices.";
            }

            $cal = $this->parseCalories($row['C'] ?? '');
            $rawIng = trim((string)($row['B'] ?? ''));
            $smartIngs = $this->extractSmartIngredients($dish, $rawIng);

            // Cleaned name
            $cleanName = $dish;
            if ($dish === 'Tosts bread with Curry and coconut sambal') {
                $cleanName = 'Toast bread with Curry and coconut sambal';
            } elseif ($dish === 'Kiri bath with Lunu miris') {
                $cleanName = 'Kiribath with Lunu miris';
            } elseif ($dish === 'Dose set') {
                $cleanName = 'Dosa set';
            } elseif ($dish === 'Sweet corn soup garlic breads') {
                $cleanName = 'Sweet corn soup with garlic breads';
            } elseif ($dish === '2 Parotta with Red chicken Curry' && $foodId === 93) {
                $cleanName = 'Parotta with Red chicken Curry';
            }

            $updates[$foodId] = [
                'id' => $foodId,
                'name' => $cleanName,
                'description' => $desc,
                'calories' => $cal ?? $allFoods[$foodId]->calories ?? 450,
                'ingredients' => $smartIngs,
            ];
        }

        // Duplicate meal-slot dishes in DB
        // Lunch counterpart of Row 5 (White Rice and Curry with only vegetable)
        if (isset($allFoods[41])) {
            $updates[41] = [
                'id' => 41,
                'name' => 'White Rice and Curry with only vegetable',
                'description' => trim((string)$data[5]['D']),
                'calories' => $this->parseCalories($data[5]['C']),
                'ingredients' => $this->extractSmartIngredients('White Rice and Curry with only vegetable', $data[5]['B']),
            ];
        }

        // Lunch counterpart of Row 6 (White Rice and Curry with fry egg)
        if (isset($allFoods[46])) {
            $updates[46] = [
                'id' => 46,
                'name' => 'White Rice and Curry with fry egg',
                'description' => trim((string)$data[6]['D']),
                'calories' => $this->parseCalories($data[6]['C']),
                'ingredients' => $this->extractSmartIngredients('White Rice and Curry with fry egg', $data[6]['B']),
            ];
        }

        // Lunch counterpart of Row 7 (White Rice and Curry with boil egg)
        if (isset($allFoods[47])) {
            $updates[47] = [
                'id' => 47,
                'name' => 'White Rice and Curry with boil egg',
                'description' => trim((string)$data[7]['D']),
                'calories' => $this->parseCalories($data[7]['C']),
                'ingredients' => $this->extractSmartIngredients('White Rice and Curry with boil egg', $data[7]['B']),
            ];
        }

        // Dinner counterpart of Row 41 (Special Sri lankan style Chicken fried rice)
        if (isset($allFoods[71])) {
            $updates[71] = [
                'id' => 71,
                'name' => 'Special Sri lankan style Chicken fried rice',
                'description' => trim((string)$data[41]['D']),
                'calories' => $this->parseCalories($data[41]['C']),
                'ingredients' => $this->extractSmartIngredients('Special Sri lankan style Chicken fried rice', $data[41]['B']),
            ];
        }

        // ID 108 (Spaghetti)
        if (isset($allFoods[108])) {
            $updates[108] = [
                'id' => 108,
                'name' => 'Spaghetti',
                'description' => "Al dente Italian spaghetti tossed in rich herb-infused slow-cooked tomato marinara sauce, garnished with parmesan.",
                'calories' => 560,
                'ingredients' => ['Italian Pasta', 'Curried Vegetables', 'Cheddar Cheese', 'Garlic & Ginger', 'Ceylon Spices'],
            ];
        }

        $this->info("Prepared " . count($updates) . " food updates. Starting database transaction...");

        DB::transaction(function () use ($updates) {
            $foodCount = 0;
            $ingCount = 0;

            foreach ($updates as $foodId => $updateData) {
                // 1. Update Food record
                Food::where('id', $foodId)->update([
                    'name' => $updateData['name'],
                    'description' => $updateData['description'],
                    'calories' => $updateData['calories'],
                ]);
                $foodCount++;

                // 2. Sync Ingredients
                Ingredient::where('food_id', $foodId)->delete();
                foreach ($updateData['ingredients'] as $ingName) {
                    Ingredient::create([
                        'food_id' => $foodId,
                        'name' => $ingName,
                        'icon_url' => $this->getIcon($ingName),
                    ]);
                    $ingCount++;
                }
            }

            $this->info("Successfully synchronized {$foodCount} food items and {$ingCount} ingredient records.");
        });

        $this->info("Menu descriptions, calories, and ingredients update completed successfully!");
        return Command::SUCCESS;
    }

    private function cleanStr($str)
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower($str));
    }

    private function parseCalories($calStr)
    {
        if (!$calStr) return 450;
        preg_match_all('/\d+/', $calStr, $matches);
        if (empty($matches[0])) return 450;
        $nums = array_map('intval', $matches[0]);
        if (count($nums) === 1) return $nums[0];
        return (int) round(($nums[0] + $nums[1]) / 2);
    }

    private function getIcon($name)
    {
        $lower = strtolower($name);
        if (str_contains($lower, 'chicken')) return 'assets/icons/chicken.png';
        if (str_contains($lower, 'prawn') || str_contains($lower, 'shrimp') || str_contains($lower, 'seafood')) return 'assets/icons/shrimp.png';
        if (str_contains($lower, 'beef') || str_contains($lower, 'pork') || str_contains($lower, 'meat')) return 'assets/icons/meat.png';
        if (str_contains($lower, 'fish') || str_contains($lower, 'sprat') || str_contains($lower, 'karawala') || str_contains($lower, 'abulthial') || str_contains($lower, 'abulthiyal')) return 'assets/icons/fish.png';
        if (str_contains($lower, 'egg') || str_contains($lower, 'omelet')) return 'assets/icons/egg.png';
        if (str_contains($lower, 'rice') || str_contains($lower, 'noodle') || str_contains($lower, 'pasta') || str_contains($lower, 'spaghetti') || str_contains($lower, 'flour') || str_contains($lower, 'bread') || str_contains($lower, 'porotta') || str_contains($lower, 'parotta') || str_contains($lower, 'roti') || str_contains($lower, 'grain') || str_contains($lower, 'wrap') || str_contains($lower, 'dosa') || str_contains($lower, 'hopper') || str_contains($lower, 'chapathi') || str_contains($lower, 'batter')) return 'assets/icons/rice.png';
        if (str_contains($lower, 'sauce') || str_contains($lower, 'oil') || str_contains($lower, 'milk') || str_contains($lower, 'dressing') || str_contains($lower, 'spices') || str_contains($lower, 'stock') || str_contains($lower, 'salt') || str_contains($lower, 'cream') || str_contains($lower, 'curry powder') || str_contains($lower, 'miris') || str_contains($lower, 'sambal') || str_contains($lower, 'chutney') || str_contains($lower, 'gravy')) return 'assets/icons/sauce.png';
        return 'assets/icons/veggie.png';
    }

    private function extractSmartIngredients($dishName, $rawIngredients)
    {
        $dishLower = strtolower($dishName);
        $ingLower = strtolower($rawIngredients);

        // 1. Proteins / Stars
        $stars = [];
        if (str_contains($dishLower, 'sprat') || str_contains($ingLower, 'sprat')) $stars[] = 'Dried Sprats';
        if (str_contains($dishLower, 'karawala') || str_contains($ingLower, 'karawala')) $stars[] = 'Katta Karawala';
        if (str_contains($dishLower, 'chicken') || str_contains($ingLower, 'chicken')) $stars[] = 'Fresh Chicken';
        if (str_contains($dishLower, 'beef') || str_contains($ingLower, 'beef')) $stars[] = 'Tender Beef';
        if (str_contains($dishLower, 'pork') || str_contains($ingLower, 'pork')) $stars[] = 'Succulent Pork';
        if (str_contains($dishLower, 'prawn') || str_contains($ingLower, 'prawn')) $stars[] = 'Fresh Prawns';
        if (str_contains($dishLower, 'seafood') || str_contains($ingLower, 'seafood')) $stars[] = 'Fresh Seafood';
        if (str_contains($dishLower, 'fish') || str_contains($dishLower, 'malu') || str_contains($ingLower, 'fresh fish')) $stars[] = 'Fresh Fish';
        if (str_contains($dishLower, 'abulthial') || str_contains($dishLower, 'abulthiyal')) $stars[] = 'Ambulthiyal Fish';
        if (str_contains($dishLower, 'egg') || str_contains($dishLower, 'omelet') || str_contains($ingLower, 'egg')) {
            if (str_contains($dishLower, 'boil') || str_contains($ingLower, 'boiled egg')) $stars[] = 'Boiled Eggs';
            elseif (str_contains($dishLower, 'fry') || str_contains($ingLower, 'fry egg')) $stars[] = 'Fried Egg';
            elseif (str_contains($dishLower, 'omelet')) $stars[] = 'Farm Eggs';
            else $stars[] = 'Farm Eggs';
        }
        if (str_contains($dishLower, 'paneer') || str_contains($ingLower, 'paneer')) $stars[] = 'Paneer';
        if (str_contains($dishLower, 'cheese') || (str_contains($ingLower, 'cheese') && !str_contains($ingLower, 'if listed'))) $stars[] = 'Cheddar Cheese';
        if (str_contains($dishLower, 'cashew') || str_contains($ingLower, 'cashew')) $stars[] = 'Cashew Nuts';
        if (str_contains($dishLower, 'chickpea') || str_contains($ingLower, 'chickpea')) $stars[] = 'Chickpeas';
        if (str_contains($dishLower, 'avocado') || str_contains($ingLower, 'avocado')) $stars[] = 'Ripe Avocado';
        if (str_contains($dishLower, 'sweet corn') || str_contains($ingLower, 'sweet corn')) $stars[] = 'Sweet Corn';

        // 2. Base Carbs
        $bases = [];
        if (str_contains($dishLower, 'red rice') || str_contains($ingLower, 'red rice')) $bases[] = 'Red Rice';
        elseif (str_contains($dishLower, 'white rice') || str_contains($ingLower, 'white rice')) $bases[] = 'White Rice';
        elseif (str_contains($dishLower, 'kiribath') || str_contains($dishLower, 'kiri bath')) $bases[] = 'Milk Rice (Kiribath)';
        elseif (str_contains($dishLower, 'biriyani') || str_contains($ingLower, 'biriyani')) $bases[] = 'Biriyani Rice';
        elseif (str_contains($dishLower, 'string hopper') || str_contains($ingLower, 'string hopper') || str_contains($ingLower, 'rice flour')) $bases[] = 'String Hoppers';
        elseif (str_contains($dishLower, 'parotta') || str_contains($dishLower, 'porotta') || str_contains($ingLower, 'parotta') || str_contains($ingLower, 'porotta')) $bases[] = 'Flaky Parotta';
        elseif (str_contains($dishLower, 'roti') || str_contains($ingLower, 'roti')) $bases[] = 'Pol Roti';
        elseif (str_contains($dishLower, 'chapathi') || str_contains($ingLower, 'chapathi')) $bases[] = 'Fresh Chapathi';
        elseif (str_contains($dishLower, 'dose') || str_contains($dishLower, 'dosa') || str_contains($ingLower, 'dosa')) $bases[] = 'Crispy Dosa';
        elseif (str_contains($dishLower, 'fried rice')) $bases[] = 'Fried Rice';
        elseif (str_contains($dishLower, 'noodles') || str_contains($ingLower, 'noodles')) $bases[] = 'Noodles';
        elseif (str_contains($dishLower, 'pasta') || str_contains($ingLower, 'pasta')) $bases[] = 'Italian Pasta';
        elseif (str_contains($dishLower, 'wrap') || str_contains($ingLower, 'wrap')) $bases[] = 'Tortilla Wrap';
        elseif (str_contains($dishLower, 'bread') || str_contains($dishLower, 'tost') || str_contains($ingLower, 'bread')) $bases[] = 'Toasted Bread';
        elseif (str_contains($dishLower, 'potato') || str_contains($ingLower, 'potato') || str_contains($ingLower, 'sweet potato')) {
            if (str_contains($dishLower, 'sweet potato') || str_contains($ingLower, 'sweet potato')) $bases[] = 'Sweet Potatoes';
            else $bases[] = 'Mashed Potatoes';
        }

        // 3. Key Sides / Sauces
        $sides = [];
        if (str_contains($dishLower, 'lunu miris') || str_contains($ingLower, 'lunu miris')) $sides[] = 'Lunu Miris';
        if (str_contains($dishLower, 'sambal') || str_contains($ingLower, 'coconut sambal') || str_contains($ingLower, 'sambal')) $sides[] = 'Coconut Sambal';
        if (str_contains($dishLower, 'sambar') || str_contains($ingLower, 'sambar')) $sides[] = 'Sambar';
        if (str_contains($dishLower, 'chutney') || str_contains($ingLower, 'chutney')) $sides[] = 'Coconut Chutney';
        if (str_contains($dishLower, 'salad') || str_contains($ingLower, 'lettuce') || str_contains($ingLower, 'cucumber')) $sides[] = 'Garden Salad';
        if (str_contains($dishLower, 'soup') || str_contains($ingLower, 'stock')) $sides[] = 'Aromatic Broth';
        if (str_contains($ingLower, 'vegetable curries') || str_contains($dishLower, 'vegetable') || str_contains($ingLower, 'seasonal vegetables')) $sides[] = 'Curried Vegetables';
        if (str_contains($ingLower, 'coconut milk') || str_contains($dishLower, 'kiri malu')) $sides[] = 'Coconut Milk';
        if (str_contains($ingLower, 'garlic')) $sides[] = 'Garlic & Ginger';
        if (str_contains($ingLower, 'curry leaves')) $sides[] = 'Curry Leaves';
        if (str_contains($ingLower, 'curry powder') || str_contains($ingLower, 'spices')) $sides[] = 'Ceylon Spices';

        // Combine up to 5 items
        $combined = [];
        foreach (array_merge($bases, $stars, $sides) as $item) {
            if (!in_array($item, $combined)) {
                $combined[] = $item;
            }
            if (count($combined) >= 5) break;
        }

        if (count($combined) < 3) {
            $combined[] = 'Spices & Herbs';
        }

        return $combined;
    }
}

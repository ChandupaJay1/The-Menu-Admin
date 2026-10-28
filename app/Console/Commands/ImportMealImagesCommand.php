<?php

namespace App\Console\Commands;

use App\Models\Food;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportMealImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:import-images 
                            {--source=E:/The monolith/The Menu : Path to source folder containing meal images}
                            {--max-size=1024 : Maximum dimension in pixels}
                            {--quality=88 : JPEG compression quality}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import, optimize, and map meal photos from local source directory to public storage and database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourceDir = $this->option('source');
        $maxDimension = (int) $this->option('max-size');
        $jpegQuality = (int) $this->option('quality');

        if (!is_dir($sourceDir)) {
            $this->error("Source directory not found: {$sourceDir}");
            return Command::FAILURE;
        }

        $destDir = storage_path('app/public/foods');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $this->info("Scanning images in: {$sourceDir}");

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir));
        $files = [];
        foreach ($it as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                $folder = basename($file->getPath());
                $filename = $file->getFilename();
                $nameOnly = pathinfo($filename, PATHINFO_FILENAME);
                $files[] = [
                    'fullpath' => $file->getPathname(),
                    'folder' => $folder,
                    'filename' => $filename,
                    'name_only' => $nameOnly,
                ];
            }
        }

        $this->info("Found " . count($files) . " image files.");

        $foods = Food::all()->keyBy('id');
        $this->info("Found " . $foods->count() . " food items in database.");

        // Manual overrides for slight spelling variations
        $overrides = [
            'Dose set' => 29, // Dosa set (Breakfast)
            'Tosts bread with Curry' => 32, // Toast bread with Curry and coconut sambal (Breakfast)
            'Special mix Grain rice with veggie curry' => 33, // Special mix grains rice with veggie curry (Breakfast)
            'Special mix grain Rice with Chicken Curry' => 34, // Special mix grains rice with chicken curry (Breakfast)
            'Chicken Slad' => 63, // Chicken Salad (Lunch)
            'Chicken soup with toasted bread' => 102, // Chicken soup with toasted breads (Dinner)
            'Seafood Soup with garlic bread' => 103, // Seafood Soup with garlic breads (Dinner)
            'Sweet corn soup garlic breads' => 104, // Sweet corn soup with garlic breads (Dinner)
        ];

        $matched = [];
        $unmatchedFiles = [];

        foreach ($files as $file) {
            $folderLower = strtolower($file['folder']);
            $folderType = null;
            if (str_contains($folderLower, 'breakfast')) $folderType = 'Breakfast';
            elseif (str_contains($folderLower, 'lunch')) $folderType = 'Lunch';
            elseif (str_contains($folderLower, 'dinner')) $folderType = 'Dinner';

            $foundId = null;

            // Check manual overrides
            foreach ($overrides as $k => $id) {
                if (stripos($file['name_only'], $k) !== false) {
                    $foundId = $id;
                    break;
                }
            }

            // Match with category filter
            if (!$foundId) {
                $fileNorm = $this->normalizeString($file['name_only']);
                foreach ($foods as $food) {
                    if ($folderType && $food->type !== $folderType) continue;
                    if ($this->normalizeString($food->name) === $fileNorm) {
                        $foundId = $food->id;
                        break;
                    }
                }
            }

            // Fallback match across all categories
            if (!$foundId) {
                $fileNorm = $this->normalizeString($file['name_only']);
                foreach ($foods as $food) {
                    if ($this->normalizeString($food->name) === $fileNorm) {
                        $foundId = $food->id;
                        break;
                    }
                }
            }

            if ($foundId && isset($foods[$foundId])) {
                $food = $foods[$foundId];
                $targetFilename = 'food_' . $food->id . '_' . $this->slugify($food->name) . '.jpg';
                $matched[$food->id] = [
                    'food_id' => $food->id,
                    'food_name' => $food->name,
                    'food_type' => $food->type,
                    'source_path' => $file['fullpath'],
                    'target_filename' => $targetFilename,
                    'target_path' => $destDir . DIRECTORY_SEPARATOR . $targetFilename,
                    'db_path' => 'storage/foods/' . $targetFilename,
                ];
            } else {
                $unmatchedFiles[] = $file;
            }
        }

        $this->info("Matched " . count($matched) . " out of " . $foods->count() . " foods.");
        if (!empty($unmatchedFiles)) {
            $this->warn("Unmatched files on disk: " . count($unmatchedFiles));
            foreach ($unmatchedFiles as $uf) {
                $this->line(" - [{$uf['folder']}] {$uf['filename']}");
            }
        }

        $this->info("Optimizing and copying images into storage/app/public/foods/...");

        $bar = $this->output->createProgressBar(count($matched));
        $bar->start();

        $originalBytesTotal = 0;
        $optimizedBytesTotal = 0;

        foreach ($matched as $item) {
            $srcPath = $item['source_path'];
            $destPath = $item['target_path'];

            $originalBytesTotal += filesize($srcPath);

            $this->optimizeAndSaveImage($srcPath, $destPath, $maxDimension, $jpegQuality);

            $optimizedBytesTotal += filesize($destPath);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $origMB = round($originalBytesTotal / (1024 * 1024), 2);
        $optMB = round($optimizedBytesTotal / (1024 * 1024), 2);
        $savedPct = round((1 - ($optimizedBytesTotal / max(1, $originalBytesTotal))) * 100, 1);

        $this->info("Image optimization complete: {$origMB} MB -> {$optMB} MB (saved {$savedPct}% bandwidth).");

        $this->info("Updating database image URLs...");
        DB::transaction(function () use ($matched) {
            foreach ($matched as $item) {
                Food::where('id', $item['food_id'])->update([
                    'image_url' => $item['db_path'],
                ]);
            }
        });

        $this->info("All " . count($matched) . " meal photos successfully mapped and connected to the database!");
        return Command::SUCCESS;
    }

    private function optimizeAndSaveImage(string $src, string $dest, int $maxDim, int $quality): void
    {
        $info = @getimagesize($src);
        if (!$info) {
            copy($src, $dest);
            return;
        }

        $mime = $info['mime'] ?? 'image/jpeg';
        switch ($mime) {
            case 'image/png':
                $img = @imagecreatefrompng($src);
                break;
            case 'image/webp':
                $img = @imagecreatefromwebp($src);
                break;
            default:
                $img = @imagecreatefromjpeg($src);
                break;
        }

        if (!$img) {
            copy($src, $dest);
            return;
        }

        $w = imagesx($img);
        $h = imagesy($img);

        if ($w > $maxDim || $h > $maxDim) {
            if ($w >= $h) {
                $newW = $maxDim;
                $newH = (int) round(($h / $w) * $maxDim);
            } else {
                $newH = $maxDim;
                $newW = (int) round(($w / $h) * $maxDim);
            }
            $target = imagecreatetruecolor($newW, $newH);

            // Handle transparency if PNG/WebP by filling white background for clean food display
            $white = imagecolorallocate($target, 255, 255, 255);
            imagefilledrectangle($target, 0, 0, $newW, $newH, $white);

            imagecopyresampled($target, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagejpeg($target, $dest, $quality);
            imagedestroy($target);
        } else {
            imagejpeg($img, $dest, $quality);
        }

        imagedestroy($img);
    }

    private function normalizeString(string $str): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower(trim($str)));
    }

    private function slugify(string $str): string
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9]+/', '_', $str);
        return trim($str, '_');
    }
}

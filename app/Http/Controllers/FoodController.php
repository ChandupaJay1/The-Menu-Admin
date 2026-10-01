<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource (API and Web Admin).
     */
    public function index(Request $request)
    {
        $query = Food::with('ingredients');

        // Filter by meal type (Breakfast, Lunch, Dinner)
        if ($request->filled('type') && strtolower($request->type) !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by available day (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
        if ($request->filled('day')) {
            $day = ucfirst(substr(trim($request->day), 0, 3));
            $query->where(function ($q) use ($day) {
                $q->whereJsonContains('available_days', $day)
                  ->orWhereNull('available_days');
            });
        }

        // Search query
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->query('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest('id');
        }

        // Mobile API response
        if ($request->is('api/*') || $request->wantsJson()) {
            $foods = $query->get();

            // Compute ETag for fast 304 cache validation and bandwidth reduction
            $lastUpdated = Food::max('updated_at') ?? '0';
            $etag = '"' . md5($lastUpdated . '_' . $foods->count()) . '"';

            $clientEtag = $request->header('If-None-Match');
            if ($clientEtag && trim($clientEtag) === $etag) {
                return response('', 304, [
                    'ETag' => $etag,
                    'Cache-Control' => 'public, max-age=60',
                ]);
            }

            return response()->json($foods)
                ->header('ETag', $etag)
                ->header('Cache-Control', 'public, max-age=60');
        }

        // Web Admin View
        $activeType = $request->query('type', 'all');
        $search = $request->query('search', '');
        
        $totalCount = Food::count();
        $breakfastCount = Food::where('type', 'Breakfast')->count();
        $lunchCount = Food::where('type', 'Lunch')->count();
        $dinnerCount = Food::where('type', 'Dinner')->count();
        $avgPrice = round((float) Food::avg('price'), 2);

        $foods = $query->paginate(24)->withQueryString();

        return view('foods.index', compact(
            'foods', 'totalCount', 'breakfastCount', 'lunchCount',
            'dinnerCount', 'avgPrice', 'activeType', 'search', 'sort'
        ));
    }

    /**
     * Store a newly created food item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Breakfast,Lunch,Dinner',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'prep_time' => 'nullable|string|max:50',
            'calories' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|string|max:50',
            'servings' => 'nullable|string|max:50',
            'rating' => 'nullable|numeric|between:0,5',
            'available_days' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|url|max:1000',
        ]);

        // Handle uploaded image file
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('foods', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        } elseif (empty($validated['image_url'])) {
            // Default placeholder if no image provided
            $validated['image_url'] = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80';
        }

        // Default available days to all 7 days if none selected
        if (empty($validated['available_days'])) {
            $validated['available_days'] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        }

        // Set default rating if not provided
        if (!isset($validated['rating'])) {
            $validated['rating'] = 4.8;
        }

        // Remove the uploaded file object from array before create
        unset($validated['image']);

        $food = Food::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Meal created successfully!', 'data' => $food], 201);
        }

        return redirect()->route('foods.index')->with('success', "Meal '{$food->name}' added successfully to the menu!");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $food = Food::with(['ingredients', 'ratings.user'])->findOrFail($id);

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($food);
        }

        return response()->json($food);
    }

    /**
     * Update the specified food item.
     */
    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Breakfast,Lunch,Dinner',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'prep_time' => 'nullable|string|max:50',
            'calories' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|string|max:50',
            'servings' => 'nullable|string|max:50',
            'rating' => 'nullable|numeric|between:0,5',
            'available_days' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|url|max:1000',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('foods', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        if (isset($validated['available_days']) && empty($validated['available_days'])) {
            $validated['available_days'] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        }

        unset($validated['image']);

        $food->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Meal updated successfully!', 'data' => $food]);
        }

        return redirect()->back()->with('success', "Meal '{$food->name}' updated successfully!");
    }

    /**
     * Remove the specified food item from storage.
     */
    public function destroy(Request $request, $id)
    {
        $food = Food::findOrFail($id);
        $name = $food->name;

        // Cascade delete relations
        $food->ingredients()->delete();
        $food->ratings()->delete();
        $food->offers()->delete();
        
        $food->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Meal '{$name}' deleted successfully!"]);
        }

        return redirect()->back()->with('success', "Meal '{$name}' deleted from menu successfully!");
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = \App\Models\Food::with('ingredients')->get();

        // The Dart model expects keys exactly as we defined them
        return response()->json($foods);
    }
}

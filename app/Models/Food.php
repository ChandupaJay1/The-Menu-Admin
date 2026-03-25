<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';

    protected $fillable = [
        'name', 'description', 'price', 'image_url', 'type',
        'available_days', 'prep_time', 'calories', 'difficulty',
        'servings', 'rating',
    ];

    protected $casts = [
        'available_days' => 'array',
        'price' => 'float',
        'rating' => 'float',
        'calories' => 'integer',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}

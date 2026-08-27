<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannedMeal extends Model
{
    protected $fillable = ['user_id', 'food_id', 'meal_type', 'date', 'total_price', 'selected_extras'];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'float',
        'selected_extras' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}

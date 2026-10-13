<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'food_id',
        'quantity',
        'price',
        'meal_type',
        'scheduled_date',
        'is_paused',
        'swapped_from_food_id',
    ];

    protected $casts = [
        'price' => 'float',
        'scheduled_date' => 'date:Y-m-d',
        'is_paused' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function swappedFromFood()
    {
        return $this->belongsTo(Food::class, 'swapped_from_food_id');
    }
}

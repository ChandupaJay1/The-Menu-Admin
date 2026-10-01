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
    ];

    protected $casts = [
        'price' => 'float',
        'scheduled_date' => 'date:Y-m-d',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}

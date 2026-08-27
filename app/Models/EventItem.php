<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventItem extends Model
{
    protected $fillable = ['event_id', 'date', 'food_id', 'meal_type', 'quantity'];

    protected $casts = [
        'date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}

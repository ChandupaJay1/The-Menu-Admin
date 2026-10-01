<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['user_id', 'name', 'start_date', 'end_date', 'total_cost', 'driver_id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_cost' => 'float',
    ];

    protected $appends = ['daily_menus'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function items()
    {
        return $this->hasMany(EventItem::class);
    }

    public function getDailyMenusAttribute()
    {
        if (!$this->relationLoaded('items')) {
            return (object)[];
        }

        $menus = [];
        foreach ($this->items as $item) {
            $dateKey = $item->date ? $item->date->format('Y-m-d') : null;
            if (!$dateKey) continue;
            if (!isset($menus[$dateKey])) {
                $menus[$dateKey] = [];
            }
            $menus[$dateKey][] = [
                'food' => $item->food,
                'meal_type' => $item->meal_type,
                'quantity' => (int)$item->quantity,
            ];
        }

        return $menus;
    }
}

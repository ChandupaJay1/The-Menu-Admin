<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'vehicle_type',
        'vehicle_number',
        'total_deliveries',
        'avatar_url',
    ];

    protected $casts = [
        'total_deliveries' => 'integer',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function assignedOrders()
    {
        return $this->hasMany(Order::class);
    }

    public function assignedEvents()
    {
        return $this->hasMany(Event::class);
    }
}

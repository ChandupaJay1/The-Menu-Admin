<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'vehicle_type',
        'vehicle_number',
        'total_deliveries',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'total_deliveries' => 'integer',
        'password' => 'hashed',
    ];

    protected $appends = [
        'is_online',
        'is_active',
    ];

    public function getIsOnlineAttribute(): bool
    {
        return $this->status !== 'offline';
    }

    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['available', 'busy', 'on_delivery']);
    }

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

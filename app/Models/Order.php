<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'total_amount',
        'user_id',
        'total_price',
        'status',
        'address',
        'payment_method',
        'driver_id',
    ];

    protected $casts = [
        'total_price' => 'float',
        'total_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
            if (empty($order->customer_name) && $order->user) {
                $order->customer_name = $order->user->name;
            }
            if (empty($order->customer_phone) && $order->user) {
                $order->customer_phone = $order->user->phone ?? '';
            }
            if (empty($order->delivery_address)) {
                $order->delivery_address = $order->address ?? '';
            }
            if (empty($order->total_amount)) {
                $order->total_amount = $order->total_price ?? 0.0;
            }
            if (empty($order->address)) {
                $order->address = $order->delivery_address ?? '';
            }
            if (empty($order->total_price)) {
                $order->total_price = $order->total_amount ?? 0.0;
            }
        });
    }

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
        return $this->hasMany(OrderItem::class);
    }
}

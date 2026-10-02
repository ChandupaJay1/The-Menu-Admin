<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'dinner_address',
        'total_amount',
        'user_id',
        'total_price',
        'status',
        'address',
        'payment_method',
        'payment_status',
        'payment_reference',
        'bank_receipt_url',
        'is_subscription',
        'coupon_code',
        'discount_amount',
        'driver_id',
    ];

    protected $casts = [
        'total_price' => 'float',
        'total_amount' => 'float',
        'discount_amount' => 'float',
        'is_subscription' => 'boolean',
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
            // Generate unique lifetime payment reference if not set
            if (empty($order->payment_reference)) {
                $userId = $order->user_id ?? 1;
                $order->payment_reference = self::generateReference($userId);
            }
            // If card payment, mark subscription and verified
            if ($order->payment_method === 'Visa / Mastercard' || $order->payment_method === 'Card') {
                $order->is_subscription = true;
                $order->payment_status = 'paid';
            } elseif (empty($order->payment_status)) {
                $order->payment_status = 'pending_verification';
            }
        });
    }

    public static function generateReference(int $userId): string
    {
        $shortHash = strtoupper(substr(md5(uniqid((string)$userId, true)), 0, 6));
        return "REF-U{$userId}-{$shortHash}";
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

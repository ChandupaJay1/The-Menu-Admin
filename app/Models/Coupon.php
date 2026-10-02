<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'description',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_order_amount' => 'float',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isValidFor(float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($subtotal < (float)$this->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Order minimum of Rs. " . number_format($this->min_order_amount, 2) . " required for this coupon.",
            ];
        }

        $discount = 0.0;
        if ($this->discount_type === 'percent') {
            $discount = round(($subtotal * ($this->discount_value / 100)), 2);
        } else {
            $discount = min($subtotal, (float)$this->discount_value);
        }

        return [
            'valid' => true,
            'discount' => $discount,
            'code' => $this->code,
            'type' => $this->discount_type,
            'value' => $this->discount_value,
            'description' => $this->description,
        ];
    }
}

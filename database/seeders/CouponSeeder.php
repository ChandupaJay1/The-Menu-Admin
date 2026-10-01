<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 500,
                'description' => '10% off for first order',
                'is_active' => true,
                'expires_at' => now()->addYear(),
            ],
            [
                'code' => 'SAVE500',
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'min_order_amount' => 3000,
                'description' => 'Rs. 500 flat discount on monthly plans',
                'is_active' => true,
                'expires_at' => now()->addYear(),
            ],
            [
                'code' => 'MENU20',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'min_order_amount' => 2000,
                'description' => 'Special 20% discount on 20+ day plans',
                'is_active' => true,
                'expires_at' => now()->addYear(),
            ],
            [
                'code' => 'VIP15',
                'discount_type' => 'percent',
                'discount_value' => 15,
                'min_order_amount' => 1500,
                'description' => '15% off for loyal customers',
                'is_active' => true,
                'expires_at' => now()->addYear(),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}

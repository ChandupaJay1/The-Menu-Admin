<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Food;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyOrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        $food = Food::first();
        if (!$food) {
            $food = Food::create([
                'name' => 'Dummy Food', 
                'price' => 500, 
                'type' => 'Lunch', 
                'description' => 'Test', 
                'is_available' => true
            ]);
        }

        $dates = [
            Carbon::now()->startOfDay(),
            Carbon::now()->addDay()->startOfDay()
        ];

        foreach ($dates as $date) {
            for ($i = 0; $i < 3; $i++) {
                $total_price = rand(500, 2000);
                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total_price' => $total_price,
                    'address' => '123 Dummy Street',
                    'payment_method' => 'Cash',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'food_id' => $food->id,
                    'quantity' => rand(1, 3),
                    'price' => $total_price,
                    'scheduled_date' => $date->copy()->addHours(rand(10, 20)),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}

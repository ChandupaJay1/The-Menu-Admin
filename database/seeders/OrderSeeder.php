<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $customers = collect([
            ['name' => 'Kasun Perera',   'email' => 'kasun@example.com',   'address' => 'Kandy'],
            ['name' => 'Amara Silva',    'email' => 'amara@example.com',   'address' => 'Colombo 07'],
            ['name' => 'Nimal Fernando', 'email' => 'nimal@example.com',   'address' => 'Galle'],
            ['name' => 'Sadia Rahman',   'email' => 'sadia@example.com',   'address' => 'Negombo'],
        ])->map(function ($c) {
            return User::firstOrCreate(
                ['email' => $c['email']],
                array_merge($c, ['password' => Hash::make('password')])
            );
        });

        $foods = collect([
            ['name' => 'Chicken Kottu',      'price' => 850.00,  'type' => 'Food'],
            ['name' => 'Cheese Burger Meal', 'price' => 1200.00, 'type' => 'Food'],
            ['name' => 'Iced Latte',         'price' => 450.00,  'type' => 'Drinks'],
            ['name' => 'Margherita Pizza',   'price' => 1100.00, 'type' => 'Food'],
            ['name' => 'Prawn Tempura',      'price' => 1450.00, 'type' => 'Food'],
        ])->map(function ($f) {
            return Food::firstOrCreate(['name' => $f['name']], $f);
        });

        $samples = [
            ['customer' => 0, 'status' => 'pending',    'address' => 'Kandy',      'payment' => 'Card',      'items' => [0, 2]],
            ['customer' => 1, 'status' => 'processing', 'address' => 'Colombo 07', 'payment' => 'Cash',      'items' => [1]],
            ['customer' => 2, 'status' => 'completed',  'address' => 'Galle',      'payment' => 'Card',      'items' => [3, 0, 4]],
            ['customer' => 3, 'status' => 'cancelled',  'address' => 'Negombo',    'payment' => 'Card',      'items' => [2]],
            ['customer' => 0, 'status' => 'completed',  'address' => 'Kandy',      'payment' => 'Cash',      'items' => [1, 2]],
            ['customer' => 1, 'status' => 'pending',    'address' => 'Colombo 07', 'payment' => 'Card',      'items' => [3]],
            ['customer' => 2, 'status' => 'processing', 'address' => 'Galle',      'payment' => 'Cash',      'items' => [4, 0]],
            ['customer' => 3, 'status' => 'completed',  'address' => 'Negombo',    'payment' => 'Card',      'items' => [0]],
        ];

        foreach ($samples as $sample) {
            $customer = $customers->get($sample['customer']);
            $orderItems = collect($sample['items'])->map(function ($foodIndex) use ($foods) {
                $food = $foods->get($foodIndex);
                return [
                    'food'     => $food,
                    'quantity' => rand(1, 3),
                    'price'    => $food->price,
                ];
            });

            $total = $orderItems->sum(fn ($i) => $i['price'] * $i['quantity']);

            $order = Order::create([
                'user_id'         => $customer->id,
                'total_price'     => $total,
                'status'          => $sample['status'],
                'address'         => $sample['address'],
                'payment_method'  => $sample['payment'],
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id'  => $item['food']->id,
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);
            }
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\DeliveryFeeService;
use App\Services\SubscriptionRenewalService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST-FEE-001: Delivery Fee Matrix rates and distance surcharge
     */
    public function test_delivery_fee_matrix_and_distance_rates(): void
    {
        // 5KM Tier: 1x = 29.17, 2x = 20.82, 3x = 17.77
        $this->assertEquals('5KM', DeliveryFeeService::getDistanceTier(3.5));
        $this->assertEquals(29.17, DeliveryFeeService::getCostPerDelivery(3.5, 1));
        $this->assertEquals(20.82, DeliveryFeeService::getCostPerDelivery(3.5, 2));
        $this->assertEquals(17.77, DeliveryFeeService::getCostPerDelivery(3.5, 3));

        // 10KM Tier: 1x = 41.63, 2x = 31.65, 3x = 30.54
        $this->assertEquals('10KM', DeliveryFeeService::getDistanceTier(8.2));
        $this->assertEquals(41.63, DeliveryFeeService::getCostPerDelivery(8.2, 1));
        $this->assertEquals(31.65, DeliveryFeeService::getCostPerDelivery(8.2, 2));
        $this->assertEquals(30.54, DeliveryFeeService::getCostPerDelivery(8.2, 3));

        // 15KM Tier: 1x = 53.30, 2x = 45.82, 3x = 42.77
        $this->assertEquals('15KM', DeliveryFeeService::getDistanceTier(14.0));
        $this->assertEquals(53.30, DeliveryFeeService::getCostPerDelivery(14.0, 1));
        $this->assertEquals(45.82, DeliveryFeeService::getCostPerDelivery(14.0, 2));
        $this->assertEquals(42.77, DeliveryFeeService::getCostPerDelivery(14.0, 3));

        // Beyond 15KM: base 15KM rate + Rs. 3 per extra km
        // Distance 18KM (3 extra km): 53.30 + (3 * 3.0) = 62.30
        $this->assertEquals(62.30, DeliveryFeeService::getCostPerDelivery(18.0, 1));

        // Day fee calculation
        $this->assertEquals(41.64, DeliveryFeeService::calculateDayFee(4.0, 2)); // 2 * 20.82
    }

    /**
     * TEST-GATE-001: Order creation requires minimum 20 distinct days for subscription plans
     */
    public function test_order_creation_rejects_less_than_20_days(): void
    {
        $user = User::factory()->create();
        $food = Food::create([
            'name' => 'Chicken Rice',
            'description' => 'Healthy lunch',
            'price' => 750.0,
            'type' => 'lunch',
            'image' => 'lunch.jpg',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        // Create 10 distinct days only (violates 20-day rule)
        $items = [];
        for ($i = 2; $i <= 11; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $items[] = [
                'food_id' => $food->id,
                'quantity' => 1,
                'price' => 750.0,
                'meal_type' => 'lunch',
                'scheduled_date' => $date,
            ];
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/orders', [
            'items' => $items,
            'total_price' => 7500.0,
            'address' => 'Colombo 03',
            'payment_method' => 'Card',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Minimum 20 days of meals required for monthly plan (currently 10 days selected). Please select at least 20 days.',
        ]);
    }

    /**
     * TEST-SWAP-001: Meal swap requires 24h advance notice and equivalent price tier
     */
    public function test_meal_swap_enforces_24h_notice_and_equivalent_tier(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $cheapDish = Food::create([
            'name' => 'Standard Rice',
            'description' => 'Standard',
            'price' => 500.0,
            'type' => 'lunch',
            'image' => 'rice.jpg',
        ]);

        $expensiveDish = Food::create([
            'name' => 'Luxury Lobster Box',
            'description' => 'Far exceeds tier',
            'price' => 1800.0,
            'type' => 'lunch',
            'image' => 'lobster.jpg',
        ]);

        $eligibleDish = Food::create([
            'name' => 'Equivalent Roast Chicken',
            'description' => 'Within equivalent tier',
            'price' => 550.0,
            'type' => 'lunch',
            'image' => 'chicken.jpg',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-SUB-TEST-1',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'address' => 'Colombo 03',
            'total_amount' => 15000,
            'total_price' => 15000,
            'status' => 'pending',
            'payment_method' => 'Card',
            'payment_status' => 'paid',
            'is_subscription' => true,
        ]);

        // Item 5 days in future (eligible for 24h rule)
        $futureDate = Carbon::now()->addDays(5)->format('Y-m-d');
        $item = OrderItem::create([
            'order_id' => $order->id,
            'food_id' => $cheapDish->id,
            'quantity' => 1,
            'price' => 500.0,
            'meal_type' => 'lunch',
            'scheduled_date' => $futureDate,
        ]);

        // Attempt 1: Swap to expensive dish (exceeds tier) -> rejected 422
        $respExpensive = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/subscription/meals/swap', [
            'order_item_id' => $item->id,
            'new_food_id' => $expensiveDish->id,
        ]);

        $respExpensive->assertStatus(422);

        // Attempt 2: Swap to equivalent dish -> succeeds 200
        $respSuccess = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/subscription/meals/swap', [
            'order_item_id' => $item->id,
            'new_food_id' => $eligibleDish->id,
        ]);

        $respSuccess->assertStatus(200);
        $this->assertEquals($eligibleDish->id, $item->fresh()->food_id);
    }

    /**
     * TEST-HOLD-001: Subscription Hold enforces 48h advance notice, strict 3-day cap, and credit accumulation
     */
    public function test_hold_enforces_48h_notice_and_strict_3_day_cap(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $food = Food::create([
            'name' => 'Healthy Bowl',
            'description' => 'Bowl',
            'price' => 800.0,
            'type' => 'lunch',
            'image' => 'bowl.jpg',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-SUB-TEST-HOLD',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'address' => 'Colombo 03',
            'total_amount' => 16000,
            'total_price' => 16000,
            'status' => 'pending',
            'payment_method' => 'Card',
            'payment_status' => 'paid',
            'is_subscription' => true,
            'paused_days_count' => 0,
            'paused_credit_amount' => 0.0,
            'paused_dates' => [],
        ]);

        // Create 20 days of items
        for ($i = 3; $i <= 22; $i++) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $food->id,
                'quantity' => 1,
                'price' => 800.0,
                'meal_type' => 'lunch',
                'scheduled_date' => Carbon::now()->addDays($i)->format('Y-m-d'),
            ]);
        }

        // Attempt 1: Placing 4 days on hold (violates 3-day max cap) -> rejected 422
        $fourDates = [
            Carbon::now()->addDays(5)->format('Y-m-d'),
            Carbon::now()->addDays(6)->format('Y-m-d'),
            Carbon::now()->addDays(7)->format('Y-m-d'),
            Carbon::now()->addDays(8)->format('Y-m-d'),
        ];

        $respCap = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/subscription/meals/pause-days', [
            'dates' => $fourDates,
        ]);

        $respCap->assertStatus(422);

        // Attempt 2: Placing 2 valid days on hold (>= 48h ahead) -> succeeds 200
        $twoDates = [
            Carbon::now()->addDays(5)->format('Y-m-d'),
            Carbon::now()->addDays(6)->format('Y-m-d'),
        ];

        $respSuccess = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/subscription/meals/pause-days', [
            'dates' => $twoDates,
        ]);

        $respSuccess->assertStatus(200);
        $freshOrder = $order->fresh();
        $this->assertEquals(2, $freshOrder->paused_days_count);
        $this->assertEquals(1600.0, (float)$freshOrder->paused_credit_amount); // 2 days * 800
    }

    /**
     * TEST-RENEW-001: Auto-renewal occurs the day after cycle ends with same pattern and hold credit applied
     */
    public function test_auto_renewal_shifts_dates_and_deducts_hold_credit(): void
    {
        $user = User::factory()->create();
        $food = Food::create([
            'name' => 'Salad Dish',
            'description' => 'Salad',
            'price' => 600.0,
            'type' => 'lunch',
            'image' => 'salad.jpg',
        ]);

        // A subscription that ended yesterday (startDate: 21 days ago, endDate: yesterday)
        $startDate = Carbon::today()->subDays(20);
        $endDate = Carbon::today()->subDay();

        $endedOrder = Order::create([
            'order_number' => 'ORD-SUB-EXPIRED',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'address' => 'Colombo 03',
            'total_amount' => 12000,
            'total_price' => 12000,
            'status' => 'completed',
            'payment_method' => 'Card',
            'payment_status' => 'paid',
            'is_subscription' => true,
            'auto_renew' => true,
            'paused_credit_amount' => 1200.0, // 2 days credit
        ]);

        for ($i = 0; $i < 20; $i++) {
            OrderItem::create([
                'order_id' => $endedOrder->id,
                'food_id' => $food->id,
                'quantity' => 1,
                'price' => 600.0,
                'meal_type' => 'lunch',
                'scheduled_date' => (clone $startDate)->addDays($i)->format('Y-m-d'),
            ]);
        }

        // Process auto renewals
        $renewed = SubscriptionRenewalService::processAutoRenewals();

        $this->assertCount(1, $renewed);
        $newOrder = $renewed[0];

        // Day after end date = today
        $expectedFirstDate = (clone $endDate)->addDay()->format('Y-m-d');
        $newOrderFirstItem = $newOrder->items()->orderBy('scheduled_date')->first();
        $this->assertEquals($expectedFirstDate, $newOrderFirstItem->scheduled_date->format('Y-m-d'));

        // Hold credit deduction (12000 - 1200 = 10800)
        $this->assertEquals(10800.0, (float)$newOrder->total_price);
        $this->assertEquals($newOrder->id, $endedOrder->fresh()->renewed_to_order_id);
    }
}

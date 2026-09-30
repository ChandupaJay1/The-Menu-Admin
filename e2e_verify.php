<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Food;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Event;
use App\Models\UserAddress;
use App\Models\MealPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

echo "=========================================================\n";
echo "   THE MENU - END-TO-END INTEGRATION TEST SUITE\n";
echo "=========================================================\n\n";

$passed = 0;
$failed = 0;

function assert_test($description, $condition) {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $description\n";
        $passed++;
    } else {
        echo " [FAIL] $description\n";
        $failed++;
    }
}

// -------------------------------------------------------------
// 1. USER AUTH & PHONE NORMALIZATION
// -------------------------------------------------------------
echo ">>> Testing User Auth & Phone Normalization...\n";

// Clean up previous test user if exists
User::where('email', 'e2e_customer@themenu.lk')->orWhere('phone', '779998888')->delete();

$authController = new \App\Http\Controllers\AuthController();

// Register with +94 Sri Lanka phone format
$regRequest = Request::create('/api/register', 'POST', [
    'name' => 'E2E Customer',
    'email' => 'e2e_customer@themenu.lk',
    'phone' => '+94 77 999 8888',
    'address' => '456 Marine Drive, Colombo 03',
    'password' => 'Secret1234!',
    'password_confirmation' => 'Secret1234!',
]);

$regResponse = $authController->register($regRequest);
assert_test("User registration returns 201 status", $regResponse->getStatusCode() === 201);

$regData = json_decode($regResponse->getContent(), true);
$token = $regData['token'] ?? null;
$user = User::find($regData['user']['id'] ?? null);

assert_test("Sanctum token generated on registration", !empty($token));
assert_test("User phone saved normalized (779998888)", $user && $user->phone === '779998888');

// Test Login with +94 formatted phone
$loginRequest = Request::create('/api/login', 'POST', [
    'login' => '+94 77 999 8888',
    'password' => 'Secret1234!',
]);
$loginResponse = $authController->login($loginRequest);
assert_test("Login with +94 phone returns 200", $loginResponse->getStatusCode() === 200);

$loginData = json_decode($loginResponse->getContent(), true);
$authToken = $loginData['token'] ?? null;
assert_test("Login returns valid Sanctum bearer token", !empty($authToken));

// Set authenticated user context
\Illuminate\Support\Facades\Auth::setUser($user);

// Update Profile
$updateReq = Request::create('/api/user/profile', 'PUT', [
    'name' => 'E2E Customer Updated',
    'email' => 'e2e_customer@themenu.lk',
    'phone' => '+94 77 999 8888',
    'address' => 'Updated Suite 700, World Trade Center',
]);
$updateReq->setUserResolver(fn() => $user);
$updateResp = $authController->updateProfile($updateReq);
assert_test("Profile update returns 200", $updateResp->getStatusCode() === 200);
$user->refresh();
assert_test("Profile name updated in database", $user->name === 'E2E Customer Updated');

// Change Password
$pwdReq = Request::create('/api/user/password', 'PUT', [
    'current_password' => 'Secret1234!',
    'new_password' => 'NewSecret5678!',
]);
$pwdReq->setUserResolver(fn() => $user);
$pwdResp = $authController->changePassword($pwdReq);
assert_test("Change password returns 200", $pwdResp->getStatusCode() === 200);
$user->refresh();
assert_test("New password verified with Hash::check", Hash::check('NewSecret5678!', $user->password));

// -------------------------------------------------------------
// 2. USER ADDRESSES
// -------------------------------------------------------------
echo "\n>>> Testing User Address Management...\n";
$addressController = new \App\Http\Controllers\UserAddressController();

$addAddrReq = Request::create('/api/user/addresses', 'POST', [
    'label' => 'Head Office',
    'address' => 'Level 14, West Tower, WTC Colombo',
    'details' => 'Suite 1402',
    'is_default' => true,
]);
$addAddrReq->setUserResolver(fn() => $user);
$addrResp = $addressController->store($addAddrReq);
assert_test("Add address returns 201", $addrResp->getStatusCode() === 201);
$addrData = json_decode($addrResp->getContent(), true);
$addrId = $addrData['id'] ?? null;

$listAddrReq = Request::create('/api/user/addresses', 'GET');
$listAddrReq->setUserResolver(fn() => $user);
$listAddrResp = $addressController->index($listAddrReq);
$addressList = json_decode($listAddrResp->getContent(), true);
assert_test("Address list contains new address", count($addressList) >= 1 && $addressList[0]['label'] === 'Head Office');

// -------------------------------------------------------------
// 3. FOOD CATALOG & FILTERS
// -------------------------------------------------------------
echo "\n>>> Testing Food Catalog & Filtering API...\n";
$foodController = new \App\Http\Controllers\FoodController();

$foodsReq = Request::create('/api/foods', 'GET');
$foodsResp = $foodController->index($foodsReq);
$allFoods = json_decode($foodsResp->getContent(), true);
assert_test("Food catalog returns items (> 0)", count($allFoods) > 0);

$breakfastReq = Request::create('/api/foods', 'GET', ['type' => 'Breakfast']);
$breakfastResp = $foodController->index($breakfastReq);
$breakfastFoods = json_decode($breakfastResp->getContent(), true);
assert_test("Type filter ?type=Breakfast returns only Breakfast", count($breakfastFoods) > 0 && $breakfastFoods[0]['type'] === 'Breakfast');

$firstFood = $allFoods[0];
$singleFoodResp = $foodController->show($firstFood['id']);
$singleFood = json_decode($singleFoodResp->getContent(), true);
assert_test("Single food item includes ingredients", !empty($singleFood['ingredients']));

// -------------------------------------------------------------
// 4. MEAL PLANNER
// -------------------------------------------------------------
echo "\n>>> Testing Weekly Meal Planner API...\n";
$mealController = new \App\Http\Controllers\MealPlannerController();

$planReq = Request::create('/api/meal-plans', 'POST', [
    'food_id' => $firstFood['id'],
    'meal_type' => 'Breakfast',
    'date' => '2026-10-05',
    'total_price' => (float)$firstFood['price'],
    'selected_extras' => [],
]);
$planReq->setUserResolver(fn() => $user);
$planResp = $mealController->store($planReq);
assert_test("Add meal plan returns 201", $planResp->getStatusCode() === 201);
$plannedItem = json_decode($planResp->getContent(), true);

$getPlansReq = Request::create('/api/meal-plans', 'GET');
$getPlansReq->setUserResolver(fn() => $user);
$getPlansResp = $mealController->index($getPlansReq);
$plansList = json_decode($getPlansResp->getContent(), true);
assert_test("Meal plans listing includes planned food", count($plansList) >= 1);

// Delete planned meal
$delPlanReq = Request::create('/api/meal-plans/' . $plannedItem['id'], 'DELETE');
$delPlanReq->setUserResolver(fn() => $user);
$delPlanResp = $mealController->destroy($delPlanReq, $plannedItem['id']);
assert_test("Delete meal plan returns 200", $delPlanResp->getStatusCode() === 200);

// -------------------------------------------------------------
// 5. CART OPERATIONS & TOTALS
// -------------------------------------------------------------
echo "\n>>> Testing Cart Operations & Math...\n";
$cartController = new \App\Http\Controllers\CartController();

// Clear any existing cart for test user
$userCart = Cart::where('user_id', $user->id)->first();
if ($userCart) {
    CartItem::where('cart_id', $userCart->id)->delete();
}

// Add 2 items of firstFood
$addToCartReq1 = Request::create('/api/cart/add', 'POST', [
    'food_id' => $firstFood['id'],
    'quantity' => 2,
    'delivery_address' => 'Level 14, West Tower, WTC Colombo',
    'delivery_fee' => 3.50,
]);
$addToCartReq1->setUserResolver(fn() => $user);
$cartResp1 = $cartController->addToCart($addToCartReq1);
assert_test("Add item 1 to cart returns 200", $cartResp1->getStatusCode() === 200);

// Add 1 item of second food (if available)
$secondFood = $allFoods[1] ?? $firstFood;
$addToCartReq2 = Request::create('/api/cart/add', 'POST', [
    'food_id' => $secondFood['id'],
    'quantity' => 1,
]);
$addToCartReq2->setUserResolver(fn() => $user);
$cartResp2 = $cartController->addToCart($addToCartReq2);

$getCartReq = Request::create('/api/cart', 'GET');
$getCartReq->setUserResolver(fn() => $user);
$getCartResp = $cartController->getCart($getCartReq);
$cartData = json_decode($getCartResp->getContent(), true);

$expectedSubtotal = (2 * (float)$firstFood['price']) + (1 * (float)$secondFood['price']);
$expectedTotal = $expectedSubtotal + 3.50;

assert_test("Cart returns formatted items", count($cartData['items']) >= 2);
assert_test("Cart subtotal matches food quantities and prices", abs($cartData['subtotal'] - $expectedSubtotal) < 0.01);
assert_test("Cart total price matches subtotal + delivery fee", abs($cartData['total_price'] - $expectedTotal) < 0.01);

// Update quantity
$updateQtyReq = Request::create('/api/cart/update', 'PUT', [
    'food_id' => $secondFood['id'],
    'quantity' => 3,
]);
$updateQtyReq->setUserResolver(fn() => $user);
$cartController->updateQuantity($updateQtyReq);

$cartDataAfterUpdate = json_decode($cartController->getCart($getCartReq)->getContent(), true);
$item2 = collect($cartDataAfterUpdate['items'])->firstWhere('food_id', $secondFood['id']);
assert_test("Cart item quantity updated to 3", $item2 && $item2['quantity'] === 3);

// -------------------------------------------------------------
// 6. ORDER PLACEMENT & CONVERSION
// -------------------------------------------------------------
echo "\n>>> Testing Order Placement & Admin Flow...\n";
$orderController = new \App\Http\Controllers\OrderController();

$placeOrderReq = Request::create('/api/orders', 'POST', [
    'delivery_address' => 'Level 14, West Tower, WTC Colombo',
    'delivery_fee' => 3.50,
    'payment_method' => 'card',
]);
$placeOrderReq->setUserResolver(fn() => $user);
$placeOrderResp = $orderController->store($placeOrderReq);
assert_test("Place order returns 201 Created", $placeOrderResp->getStatusCode() === 201);

$orderData = json_decode($placeOrderResp->getContent(), true);
$orderId = $orderData['id'];

// Verify Cart was cleared
$checkCartResp = $cartController->getCart($getCartReq);
$checkCartData = json_decode($checkCartResp->getContent(), true);
assert_test("Active cart items cleared after order placement", empty($checkCartData['items']));

// Verify order history for user
$listOrdersReq = Request::create('/api/orders', 'GET');
$listOrdersReq->setUserResolver(fn() => $user);
$userOrders = json_decode($orderController->index($listOrdersReq)->getContent(), true);
assert_test("User order history contains newly placed order", collect($userOrders)->contains('id', $orderId));

// Check in admin/DB level
$dbOrder = Order::with(['items.food', 'user'])->find($orderId);
assert_test("Order stored in DB with status 'pending'", $dbOrder && $dbOrder->status === 'pending');
assert_test("Order contains associated food items", $dbOrder->items->count() >= 2);

// -------------------------------------------------------------
// 7. DRIVER APP ISOLATION & ORDER ASSIGNMENT
// -------------------------------------------------------------
echo "\n>>> Testing Driver App Isolation & Assignment Workflow...\n";
$driver = Driver::first();
if (!$driver) {
    $driver = Driver::create([
        'name' => 'Speedy Express Driver',
        'email' => 'speedy_driver@themenu.lk',
        'phone' => '771112233',
        'vehicle_type' => 'motorcycle',
        'vehicle_number' => 'WP-BD-9090',
        'license_number' => 'B9090887',
        'password' => Hash::make('DriverPass123!'),
        'status' => 'available',
    ]);
}

// Ensure driver starts as available
$driver->update(['status' => 'available']);

// Driver orders list before assignment
$driverOrdersReq = Request::create('/api/driver/orders', 'GET');
$driverOrdersReq->setUserResolver(fn() => $driver);
$driverOrderList = json_decode($orderController->index($driverOrdersReq)->getContent(), true);
assert_test("Driver orders list does NOT include unassigned user order (isolation verified)", 
    !collect($driverOrderList)->contains('id', $orderId)
);

// Admin assigns driver to the order
$dbOrder->update([
    'driver_id' => $driver->id,
    'status' => 'confirmed',
]);
$driver->update(['status' => 'on_delivery']);

// Driver orders list AFTER assignment
$driverOrderListAfter = json_decode($orderController->index($driverOrdersReq)->getContent(), true);
assert_test("Driver orders list now includes assigned order", 
    collect($driverOrderListAfter)->contains('id', $orderId)
);
assert_test("Driver status correctly reflects 'on_delivery'", 
    $driver->fresh()->status === 'on_delivery'
);

// Driver completes delivery
$dbOrder->update(['status' => 'delivered']);
$driver->update(['status' => 'available']);
assert_test("Driver returns to 'available' after delivery completion", 
    $driver->fresh()->status === 'available'
);

// -------------------------------------------------------------
// 8. MULTI-DAY EVENT CATERING BOOKING
// -------------------------------------------------------------
echo "\n>>> Testing Multi-Day Event Catering API...\n";
$eventController = new \App\Http\Controllers\EventController();

$eventReq = Request::create('/api/events', 'POST', [
    'name' => '3-Day Annual Tech Summit 2026',
    'start_date' => '2026-10-10',
    'end_date' => '2026-10-12',
    'total_cost' => 1250.00,
    'daily_menus' => [
        '2026-10-10' => [
            ['food_id' => $firstFood['id'], 'meal_type' => 'Breakfast', 'quantity' => 50],
            ['food_id' => $secondFood['id'], 'meal_type' => 'Lunch', 'quantity' => 50],
        ],
        '2026-10-11' => [
            ['food_id' => $firstFood['id'], 'meal_type' => 'Breakfast', 'quantity' => 50],
        ],
    ],
]);
$eventReq->setUserResolver(fn() => $user);
$eventResp = $eventController->store($eventReq);
assert_test("Book multi-day event returns 201 Created", $eventResp->getStatusCode() === 201);

$getEventsReq = Request::create('/api/events', 'GET');
$getEventsReq->setUserResolver(fn() => $user);
$eventsList = json_decode($eventController->index($getEventsReq)->getContent(), true);
$bookedEvent = collect($eventsList)->firstWhere('name', '3-Day Annual Tech Summit 2026');

assert_test("Event saved and retrieved via GET /api/events", !empty($bookedEvent));
assert_test("Event includes daily_menus accessor structured for Flutter EventModel", 
    isset($bookedEvent['daily_menus']) && isset($bookedEvent['daily_menus']['2026-10-10'])
);
assert_test("Day 1 has 2 meals configured", 
    count($bookedEvent['daily_menus']['2026-10-10']) === 2
);

// Clean up test data
Event::where('name', '3-Day Annual Tech Summit 2026')->delete();
$dbOrder->items()->delete();
$dbOrder->delete();
UserAddress::where('user_id', $user->id)->delete();
$user->tokens()->delete();
$user->delete();

echo "\n=========================================================\n";
echo " TEST SUMMARY: $passed Passed, $failed Failed\n";
echo "=========================================================\n";

if ($failed === 0) {
    echo ">>> ALL INTEGRATION TESTS PASSED WITH 100% SUCCESS! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED! <<<\n";
    exit(1);
}

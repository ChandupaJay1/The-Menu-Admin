<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MealPlannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// User App Public Auth & Password Reset Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Public Menu Browsing
Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{id}', [FoodController::class, 'show']);

// Driver App Authentication Routes (Preserved & Untouched)
Route::post('/driver/register', [DriverAuthController::class, 'register']);
Route::post('/driver/login', [DriverAuthController::class, 'login']);
Route::post('/driver/forgot-password', [DriverAuthController::class, 'forgotPassword']);
Route::post('/driver/reset-password', [DriverAuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sanctum Bearer Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Driver Routes (Preserved & Untouched)
    Route::get('/driver/user', [DriverAuthController::class, 'user']);
    Route::get('/driver/profile', [DriverAuthController::class, 'user']);
    Route::get('/driver/status', [DriverAuthController::class, 'getStatus']);
    Route::match(['post', 'put', 'patch'], '/driver/status', [DriverAuthController::class, 'updateStatus']);
    Route::get('/driver/orders', [DriverAuthController::class, 'orders']);
    Route::match(['post', 'put', 'patch'], '/driver/orders/{id}/status', [DriverAuthController::class, 'updateOrderStatus']);

    // User Profile & Authentication
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Addresses
    Route::get('/addresses', [UserAddressController::class, 'index']);
    Route::post('/addresses', [UserAddressController::class, 'store']);
    Route::match(['put', 'patch'], '/addresses/{id}', [UserAddressController::class, 'update']);
    Route::delete('/addresses/{id}', [UserAddressController::class, 'destroy']);
    Route::match(['post', 'put'], '/addresses/{id}/default', [UserAddressController::class, 'setDefault']);
    Route::get('/user/addresses', [UserAddressController::class, 'index']);
    Route::post('/user/addresses', [UserAddressController::class, 'store']);

    // Cart Management
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::match(['post', 'put'], '/cart/update', [CartController::class, 'updateQuantity']);
    Route::match(['post', 'delete'], '/cart/remove', [CartController::class, 'remove']);
    Route::match(['post', 'delete'], '/cart/clear', [CartController::class, 'clear']);

    // Orders & Order Calendar
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/calendar', [OrderController::class, 'calendar']);
    Route::get('/user/order-calendar', [OrderController::class, 'calendar']);

    // Planned Meals
    Route::get('/planned-meals', [MealPlannerController::class, 'index']);
    Route::post('/planned-meals', [MealPlannerController::class, 'store']);
    Route::delete('/planned-meals/{id}', [MealPlannerController::class, 'destroy']);
    Route::get('/meal-plans', [MealPlannerController::class, 'index']);
    Route::post('/meal-plans', [MealPlannerController::class, 'store']);
    Route::delete('/meal-plans/{id}', [MealPlannerController::class, 'destroy']);
    Route::delete('/meal-plans', [MealPlannerController::class, 'clearAll']);
    Route::post('/meal-plans/clear', [MealPlannerController::class, 'clearAll']);

    // Ratings & Reviews
    Route::post('/foods/{id}/rate', [RatingController::class, 'store']);
    Route::get('/foods/{id}/ratings', [RatingController::class, 'index']);

    // Offers & Promos
    Route::get('/offers', [OfferController::class, 'apiIndex']);

    // In-App Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Events Catering
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
});

// Public Offers Endpoint (so non-logged in or guest users can also view promotional banners)
Route::get('/offers', [OfferController::class, 'apiIndex']);
Route::get('/foods/{id}/ratings', [RatingController::class, 'index']);


/*
|--------------------------------------------------------------------------
| Driver App API Routes (Imported)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Api\DriverAppAuthController;
use App\Http\Controllers\Api\DriverAppOrderController;

Route::prefix('driver-app-api')->group(function () {
    Route::post('/register', [DriverAppAuthController::class, 'register']);
    Route::post('/login', [DriverAppAuthController::class, 'login']);
    Route::post('/reset-password', [DriverAppAuthController::class, 'resetPassword']);
    Route::post('/reset', [DriverAppAuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [DriverAppAuthController::class, 'logout']);
        Route::get('/me',     [DriverAppAuthController::class, 'me']);
        Route::post('/change-password', [DriverAppAuthController::class, 'changePassword']);
        Route::post('/driver/status',   [DriverAppAuthController::class, 'updateDriverStatus']);

        // Orders
        Route::get('orders-latest', [DriverAppOrderController::class, 'latest']);
        Route::get('orders', [DriverAppOrderController::class, 'index']);
        Route::patch('orders/{id}/status', [DriverAppOrderController::class, 'updateStatus']);
    });

    Route::post('/driver/status', [DriverAppAuthController::class, 'updateDriverStatus']);
});

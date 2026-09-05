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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Driver App Authentication Routes
Route::post('/driver/register', [DriverAuthController::class, 'register']);
Route::post('/driver/login', [DriverAuthController::class, 'login']);
Route::post('/driver/forgot-password', [DriverAuthController::class, 'forgotPassword']);
Route::post('/driver/reset-password', [DriverAuthController::class, 'resetPassword']);

// Authenticated Routes (Sanctum Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    // Driver Routes
    Route::get('/driver/user', [DriverAuthController::class, 'user']);
    Route::get('/driver/profile', [DriverAuthController::class, 'user']);
    Route::get('/driver/status', [DriverAuthController::class, 'getStatus']);
    Route::match(['post', 'put', 'patch'], '/driver/status', [DriverAuthController::class, 'updateStatus']);
    Route::get('/driver/orders', [DriverAuthController::class, 'orders']);
    Route::match(['post', 'put', 'patch'], '/driver/orders/{id}/status', [DriverAuthController::class, 'updateOrderStatus']);

    // User & Shared Routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/foods', [FoodController::class, 'index']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/remove', [CartController::class, 'remove']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/planned-meals', [MealPlannerController::class, 'index']);
    Route::post('/planned-meals', [MealPlannerController::class, 'store']);

    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
});

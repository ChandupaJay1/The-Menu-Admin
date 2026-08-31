<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'index'])->name('login'); // They had AuthController::showLogin, but my PageController::index serves onboarding
    Route::post('/login', [AuthController::class, 'authenticate']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated application routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories', [PageController::class, 'categories'])->name('categories');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assignDriver');
    
    Route::get('/events', [EventController::class, 'indexWeb'])->name('events');
    Route::post('/events/{event}/assign-driver', [EventController::class, 'assignDriver'])->name('events.assignDriver');
    
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::patch('/drivers/{driver}/status', [DriverController::class, 'updateStatus'])->name('drivers.updateStatus');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    
    Route::get('/notifications', [PageController::class, 'notifications'])->name('notifications');
    Route::get('/category/{slug}', [PageController::class, 'category'])->name('category');
    Route::get('/bills', [PageController::class, 'bills'])->name('bills');
    Route::get('/messages', [PageController::class, 'messages'])->name('messages');
    Route::get('/settings/checkout', [PageController::class, 'checkoutSettings'])->name('settings.checkout');
    Route::get('/settings/security', [PageController::class, 'securitySettings'])->name('settings.security');

    Route::resource('users', UserController::class)->except(['show']);
});

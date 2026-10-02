<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FoodController;

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
    Route::get('/dashboard/sales-export', [PageController::class, 'exportSalesSummary'])->name('dashboard.salesExport');
    
    // Food & Menu Management (Full CRUD)
    Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');
    Route::post('/foods', [FoodController::class, 'store'])->name('foods.store');
    Route::get('/foods/{food}', [FoodController::class, 'show'])->name('foods.show');
    Route::put('/foods/{food}', [FoodController::class, 'update'])->name('foods.update');
    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])->name('foods.destroy');
    Route::get('/categories', [FoodController::class, 'index'])->name('categories');
    
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assignDriver');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verifyPayment');
    Route::post('/orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])->name('orders.rejectPayment');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    Route::get('/events', [EventController::class, 'indexWeb'])->name('events');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/assign-driver', [EventController::class, 'assignDriver'])->name('events.assignDriver');
    
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');
    Route::patch('/drivers/{driver}/status', [DriverController::class, 'updateStatus'])->name('drivers.updateStatus');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    
    Route::get('/notifications', [PageController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [PageController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/read', [PageController::class, 'markNotificationRead'])->name('notifications.markRead');
    Route::get('/category/{slug}', [PageController::class, 'category'])->name('category');
    Route::get('/bills', [PageController::class, 'bills'])->name('bills');
    Route::get('/messages', [PageController::class, 'messages'])->name('messages');
    Route::get('/settings/checkout', [PageController::class, 'checkoutSettings'])->name('settings.checkout');
    Route::get('/settings/security', [PageController::class, 'securitySettings'])->name('settings.security');

    // Promotional Offers Management
    Route::get('/offers', [\App\Http\Controllers\OfferController::class, 'index'])->name('offers');
    Route::post('/offers', [\App\Http\Controllers\OfferController::class, 'store'])->name('offers.store');
    Route::put('/offers/{offer}', [\App\Http\Controllers\OfferController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{offer}', [\App\Http\Controllers\OfferController::class, 'destroy'])->name('offers.destroy');
    Route::patch('/offers/{offer}/toggle', [\App\Http\Controllers\OfferController::class, 'toggle'])->name('offers.toggle');

    Route::resource('users', UserController::class)->except(['show']);
});

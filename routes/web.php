<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\WebAuthController;

Route::middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories', [PageController::class, 'categories'])->name('categories');
    Route::get('/category/{slug}', [PageController::class, 'category'])->name('category');
    Route::get('/bills', [PageController::class, 'bills'])->name('bills');
    Route::get('/messages', [PageController::class, 'messages'])->name('messages');
    Route::get('/settings/checkout', [PageController::class, 'checkoutSettings'])->name('settings.checkout');
    Route::get('/settings/security', [PageController::class, 'securitySettings'])->name('settings.security');

    Route::resource('users', UserController::class)->except(['show']);
});

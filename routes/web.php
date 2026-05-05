<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;

Route::get('/', [PageController::class, 'index'])->name('onboarding');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/categories', [PageController::class, 'categories'])->name('categories');
Route::get('/orders', [PageController::class, 'orders'])->name('orders');
Route::get('/drivers', [PageController::class, 'drivers'])->name('drivers');
Route::get('/notifications', [PageController::class, 'notifications'])->name('notifications');
Route::get('/category/{slug}', [PageController::class, 'category'])->name('category');
Route::get('/bills', [PageController::class, 'bills'])->name('bills');
Route::get('/messages', [PageController::class, 'messages'])->name('messages');
Route::get('/settings/checkout', [PageController::class, 'checkoutSettings'])->name('settings.checkout');
Route::get('/settings/security', [PageController::class, 'securitySettings'])->name('settings.security');

Route::resource('users', UserController::class)->except(['show']);

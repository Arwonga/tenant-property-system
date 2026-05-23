<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\DashboardController;

// 1. The default landing page
Route::get('/', function () {
    return view('welcome');
});

// 2. Your protected Tenant Portal routes
Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/maintenance', [DashboardController::class, 'storeTicket'])->name('maintenance.store');
    Route::post('/pay', [DashboardController::class, 'processPayment'])->name('payment.process');
});

// 3. The crucial line that loads /login and /register!
require __DIR__.'/auth.php';
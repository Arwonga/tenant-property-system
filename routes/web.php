<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Admin\PropertyController;

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

// The Admin Portal Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Route
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Property Management Routes
    Route::get('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
    Route::post('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'store'])->name('properties.store');
    
    // NEW: Unit Management Routes
    Route::get('/properties/{property}/units', [\App\Http\Controllers\Admin\UnitController::class, 'index'])->name('properties.units');
    Route::post('/properties/{property}/units', [\App\Http\Controllers\Admin\UnitController::class, 'store'])->name('units.store');

    // Property Management Routes
    Route::get('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
    Route::post('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'store'])->name('properties.store');
    
});
// The crucial line that loads /login and /register!
require __DIR__.'/auth.php';
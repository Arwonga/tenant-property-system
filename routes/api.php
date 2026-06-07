<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenantController; // Don't forget to import the new controller!

// The Public Mobile Login Bridge
Route::post('/login', [AuthController::class, 'login']);

// 🔒 PROTECTED MOBILE ROUTES (Require a valid token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Get the basic user profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Get the Tenant Dashboard Data
    Route::get('/tenant/dashboard', [TenantController::class, 'getDashboardData']);
    
});
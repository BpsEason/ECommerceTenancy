<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// **ENHANCEMENT: Public queue endpoint**
Route::get('/queue/public', [App\Http\Controllers\QueueController::class, 'publicQueue']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/auth/logout', [AuthController::class, 'logout']); // Assuming a logout function exists

    // Admin-only routes for tenant management, products, orders, etc.
    Route::middleware('restrict.by.role:admin')->group(function () {
        // Plugin management
        Route::post('/plugins/notify', [PluginController::class, 'notify']);
        Route::post('/plugins/install', [PluginController::class, 'install']);

        // Product management
        Route::apiResource('products', ProductController::class);

        // Order management
        Route::apiResource('orders', OrderController::class);

        // **ENHANCEMENT: Add routes for promotions, tables, and queue management with RBAC**
        Route::apiResource('promotions', PromotionController::class);
        Route::apiResource('tables', TableController::class);
        Route::post('/queue/advance', [App\Http\Controllers\QueueController::class, 'advanceQueue']);
        Route::post('/queue/add', [App\Http\Controllers\QueueController::class, 'addNumber']);
    });

    // Manager-only or multi-role routes
    Route::middleware('restrict.by.role:admin,manager')->group(function () {
        // Example: a manager can view products and orders
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/orders', [OrderController::class, 'index']);
    });
});

// Tenant subdomain routes (not used with local docker-compose setup)
Route::domain('{tenant}.' . env('APP_DOMAIN', 'localhost'))->group(function () {
    // These routes would typically be tenant-scoped
    Route::get('/products', [ProductController::class, 'index']);
});

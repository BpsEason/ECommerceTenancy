<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\SetTenantFromDomain;
use App\Http\Middleware\RecordMetrics;
use Prometheus\RenderTextFormat;

// Central routes for tenant management (e.g., on `localhost:8000`)
Route::middleware([RecordMetrics::class])->prefix('v1')->group(function () {
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::get('/tenants', [TenantController::class, 'index']);
});

// Tenant-specific routes (e.g., on `tenanta.localhost:8000`)
Route::middleware([SetTenantFromDomain::class, RecordMetrics::class])->prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']); // New
    Route::post('/payments', [PaymentController::class, 'process']);
});

// Route for Prometheus metrics exposition
Route::get('/metrics', function (\Prometheus\CollectorRegistry $registry) {
    $renderer = new \Prometheus\RenderTextFormat();
    $result = $renderer->render($registry->getMetricFamilySamples());
    return response($result, 200, ['Content-Type' => RenderTextFormat::CONTENT_TYPE]);
});

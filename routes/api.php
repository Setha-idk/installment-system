<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlanController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES (Accessible by everyone/guests)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

// 2. PROTECTED ROUTES (Requires authenticated user/merchant)
Route::middleware('auth:sanctum')->group(function () {
    
    // Management Routes (Store, Update, Destroy)
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('plans', PlanController::class);

    // IMAGE MANAGEMENT: ALL actions are protected (index, store, show, destroy)
    Route::apiResource('images', ImageController::class); // <--- Stays protected
});
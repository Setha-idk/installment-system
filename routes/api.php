<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);

Route::post('/products', [ProductController::class, 'store']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

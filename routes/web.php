<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('products');
});
Route::get('/product-detail', function () {
    // We are simply returning the view file.
    // Laravel will look in the 'resources/views' directory for 'product-detail.blade.php'
    return view('product-detail');
})->name('product.detail');
Route::get('/login', function () {
    return view('loginRegister');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/products', function () {
    return view('products');
});

Route::get('/login', function () {
    return view('loginRegister');
});

require __DIR__.'/auth.php';

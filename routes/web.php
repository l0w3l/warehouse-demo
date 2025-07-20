<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/products', function () {
    return Inertia::render('Products');
})->middleware(['auth', 'verified'])->name('products');

Route::get('/warehouses', function () {
    return Inertia::render('Warehouses');
})->middleware(['auth', 'verified'])->name('warehouses');

Route::get('/stock-history', function () {
    return Inertia::render('StockHistory');
})->middleware(['auth', 'verified'])->name('stock-history');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

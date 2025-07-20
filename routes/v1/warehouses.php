<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\WarehouseController;

Route::apiResource('warehouses', WarehouseController::class)
    ->only(['index']);

Route::prefix('/warehouses')->name('warehouses.')->group(function () {
    Route::get('/history', [WarehouseController::class, 'history'])->name('history');

    Route::get('/{warehouse}/products', [WarehouseController::class, 'products'])->name('products');
});

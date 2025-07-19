<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\OrderController;

Route::apiResource('orders', OrderController::class)
    ->only(['index', 'store', 'update']);

Route::prefix('/orders')->name('orders.')->group(function () {
    Route::get('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});

<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\OrderController;

Route::apiResource('orders', OrderController::class)
    ->only(['index', 'store', 'update']);

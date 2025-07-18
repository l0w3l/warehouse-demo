<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ProductController;

Route::apiResource('products', ProductController::class)
    ->only(['index']);

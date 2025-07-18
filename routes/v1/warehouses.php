<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\WarehouseController;

Route::apiResource('warehouses', WarehouseController::class)
    ->only(['index']);

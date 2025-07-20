<?php

declare(strict_types=1);

Route::group([], function () {
    require __DIR__.'/warehouses.php';
    require __DIR__.'/products.php';
    require __DIR__.'/orders.php';
});

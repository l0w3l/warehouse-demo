<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->name('api.v1.')->group(function () {
    require_once __DIR__.'/v1/index.php';
});

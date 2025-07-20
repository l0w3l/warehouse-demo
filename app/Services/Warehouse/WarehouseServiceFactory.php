<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class WarehouseServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): WarehouseServiceInterface
    {
        return App::make(WarehouseService::class, $params);
    }
}

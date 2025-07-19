<?php

declare(strict_types=1);

namespace App\Services\Order;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class OrderServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): OrderServiceInterface
    {
        return App::make(OrderService::class, $params);
    }
}

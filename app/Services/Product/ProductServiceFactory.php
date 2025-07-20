<?php

declare(strict_types=1);

namespace App\Services\Product;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class ProductServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): ProductServiceInterface
    {
        return App::make(ProductService::class, $params);
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class ProductRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): ProductRepositoryInterface
    {
        return new ProductRepository;
    }
}

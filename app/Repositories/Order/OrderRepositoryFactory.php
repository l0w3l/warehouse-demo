<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class OrderRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): OrderRepositoryInterface
    {
        return new OrderRepository;
    }
}

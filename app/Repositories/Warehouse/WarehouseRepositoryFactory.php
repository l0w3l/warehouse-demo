<?php

declare(strict_types=1);

namespace App\Repositories\Warehouse;

use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class WarehouseRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): WarehouseRepositoryInterface
    {
        return new WarehouseRepository;
    }
}

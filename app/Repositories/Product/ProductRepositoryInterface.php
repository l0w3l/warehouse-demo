<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Data\Repositories\Product\FullProductData;
use App\Data\Repositories\Product\Warehouse\WarehouseProductsData;
use App\Exceptions\Repositories\Warehouse\WarehouseNotFoundException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<FullProductData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;

    /**
     * @throws WarehouseNotFoundException
     */
    public function allProductsBy(int $warehouseId, int $offset = 0, int $limit = 10): WarehouseProductsData;
}

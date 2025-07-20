<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Data\Repositories\Product\FullProductData;
use App\Data\Repositories\Product\Warehouse\WarehouseProductsData;
use App\Data\Repositories\Warehouse\WarehouseData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface ProductServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<FullProductData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;

    public function allFor(WarehouseData|int $warehouseData, int $offset = 0, int $limit = 10): WarehouseProductsData;
}

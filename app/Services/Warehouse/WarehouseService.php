<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use App\Data\Repositories\Product\ProductData;
use App\Data\Repositories\Warehouse\WarehouseData;
use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class WarehouseService extends AbstractService implements WarehouseServiceInterface
{
    public function __construct(
        public WarehouseRepositoryInterface $repository,
    ) {}

    public function all(int $offset = 0, int $limit = 10): Collection
    {
        return $this->repository->all($offset, $limit);
    }

    public function takeProduct(int|WarehouseData $warehouseData, ProductData|int $productData, int $quantity): void
    {
        $warehouseId = $warehouseData?->id ?? $warehouseData;
        $productId = $productData?->id ?? $productData;

        $this->repository->incStock($warehouseId, $productId, $quantity);
    }

    public function sendProduct(int|WarehouseData $warehouseData, ProductData|int $productData, int $quantity): void
    {
        $warehouseId = $warehouseData?->id ?? $warehouseData;
        $productId = $productData?->id ?? $productData;

        $this->repository->decStock($warehouseId, $productId, $quantity);
    }
}

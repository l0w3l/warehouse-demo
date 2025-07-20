<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Data\Repositories\Product\Warehouse\WarehouseProductsData;
use App\Data\Repositories\Warehouse\WarehouseData;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class ProductService extends AbstractService implements ProductServiceInterface
{
    public function __construct(
        public ProductRepositoryInterface $productRepository
    ) {}

    public function all(int $offset = 0, int $limit = 10): Collection
    {
        return $this->productRepository->all($offset, $limit);
    }

    public function count(): int
    {
        return $this->productRepository->count();
    }

    public function allFor(int|WarehouseData $warehouseData, int $offset = 0, int $limit = 10): WarehouseProductsData
    {
        $warehouseData = $warehouseData->id ?? $warehouseData;

        return $this->productRepository->allProductsBy($warehouseData);
    }

    public function countFor(int|WarehouseData $warehouseData): int
    {
        $warehouseData = $warehouseData->id ?? $warehouseData;

        return $this->productRepository->countFor($warehouseData);
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories\Warehouse;

use App\Data\Repositories\Warehouse\WarehouseData;
use App\Exceptions\Repositories\Warehouse\WarehouseNotFoundException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface WarehouseRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<WarehouseData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;

    public function count(): int;

    /**
     * @throws WarehouseNotFoundException
     */
    public function findById(int $id): WarehouseData;

    public function decStock(int $warehouseId, int $productId, $quantity = 1): void;

    public function incStock(int $warehouseId, int $productId, $quantity = 1): void;
}

<?php

declare(strict_types=1);

namespace App\Repositories\Warehouse;

use App\Data\Repositories\Warehouse\WarehouseData;
use App\Exceptions\Repositories\Warehouse\NegativeStockException;
use App\Exceptions\Repositories\Warehouse\WarehouseNotFoundException;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class WarehouseRepository extends AbstractRepository implements WarehouseRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10): Collection
    {
        $warehouses = Warehouse::offset($offset)->limit($limit)->get();

        return WarehouseData::collect($warehouses);
    }

    public function findById(int $id): WarehouseData
    {
        $warehouse = Warehouse::find($id);

        if (! $warehouse) {
            throw new WarehouseNotFoundException('Warehouse not found');
        }

        return $warehouse;
    }

    public function decStock(int $warehouseId, int $productId, $quantity = 1): void
    {
        $stock = Stock::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)->first();

        if ($stock->stock < $quantity) {
            throw new NegativeStockException('Negative stock error');
        }

        Stock::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->decrement('stock', $quantity);
    }

    public function incStock(int $warehouseId, int $productId, $quantity = 1): void
    {
        Stock::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->increment('stock', $quantity);
    }
}

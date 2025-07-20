<?php

declare(strict_types=1);

namespace App\Services\StockHistory;

use App\Data\Repositories\StockHistory\StockHistoryItemData;
use App\Exceptions\Repositories\StockHistory\ProductForWarehouseNotFoundException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface StockHistoryServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<StockHistoryItemData
     */
    public function get(?int $warehouseId = null, int $offset = 0, int $limit = 10): Collection;

    public function count(): int;

    /**
     * @throws ProductForWarehouseNotFoundException
     */
    public function decAction(int $warehouse_id, int $product_id, int $quantity): StockHistoryItemData;

    /**
     * @throws ProductForWarehouseNotFoundException
     */
    public function incAction(int $warehouse_id, int $product_id, int $quantity): StockHistoryItemData;
}

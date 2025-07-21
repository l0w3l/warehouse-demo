<?php

declare(strict_types=1);

namespace App\Repositories\StockHistory;

use App\Data\Repositories\StockHistory\StockHistoryItemData;
use App\Exceptions\Repositories\StockHistory\ProductForWarehouseNotFoundException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface StockHistoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<StockHistoryItemData>
     */
    public function all(int $offset = 0, $limit = 10): Collection;

    public function count(): int;

    /**
     * @return Collection<StockHistoryItemData>
     */
    public function allFor(int $warehouseId, int $offset = 0, $limit = 10): Collection;

    /**
     * @throws ProductForWarehouseNotFoundException
     */
    public function create(int $warehouseId, int $productId, int $change): StockHistoryItemData;
}

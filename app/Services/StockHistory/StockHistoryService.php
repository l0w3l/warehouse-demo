<?php

declare(strict_types=1);

namespace App\Services\StockHistory;

use App\Data\Repositories\StockHistory\StockHistoryItemData;
use App\Repositories\StockHistory\StockHistoryRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class StockHistoryService extends AbstractService implements StockHistoryServiceInterface
{
    public function __construct(
        public StockHistoryRepositoryInterface $stockHistoryRepository,
    ) {}

    public function get(?int $warehouseId = null, int $offset = 0, int $limit = 10): Collection
    {
        if ($warehouseId !== null) {
            return $this->stockHistoryRepository->allFor($warehouseId, $offset, $limit);
        } else {
            return $this->stockHistoryRepository->all($offset, $limit);
        }
    }

    public function decAction(int $warehouse_id, int $product_id, int $quantity): StockHistoryItemData
    {
        return $this->incAction($warehouse_id, $product_id, -$quantity);
    }

    public function incAction(int $warehouse_id, int $product_id, int $quantity): StockHistoryItemData
    {
        return $this->stockHistoryRepository->create($warehouse_id, $product_id, $quantity);
    }
}

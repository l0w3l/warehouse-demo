<?php

declare(strict_types=1);

namespace App\Repositories\StockHistory;

use App\Data\Repositories\StockHistory\StockHistoryItemData;
use App\Exceptions\Repositories\StockHistory\ProductForWarehouseNotFoundException;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class StockHistoryRepository extends AbstractRepository implements StockHistoryRepositoryInterface
{
    public function all(int $offset = 0, $limit = 10): Collection
    {
        $stockHistory = StockHistory::with('stock.warehouse', 'stock.product')->offset($offset)->limit($limit)->latest('id')->get();

        return $this->collectStockHistory($stockHistory);
    }

    public function allFor(int $warehouseId, int $offset = 0, $limit = 10): Collection
    {
        $stockHistory = StockHistory::with('stock.warehouse', 'stock.product')
            ->where('warehouse_id', $warehouseId)
            ->offset($offset)->limit($limit)
            ->latest('id')->get();

        return $this->collectStockHistory($stockHistory);
    }

    public function create(int $warehouseId, int $productId, int $change): StockHistoryItemData
    {
        $stock = Stock::whereWarehouseId($warehouseId)->whereProductId($productId)->first();

        if ($stock === null) {
            throw new ProductForWarehouseNotFoundException('Product not found');
        }

        $stockHistory = StockHistory::create([
            'stock_id' => $stock->id,
            'change' => $change,
        ]);

        return $this->wrapStockHistory($stockHistory);
    }

    /**
     * @return Collection<StockHistoryItemData>
     */
    private function collectStockHistory(EloquentCollection $stockHistory): Collection
    {
        return StockHistoryItemData::collect(
            $stockHistory->map(fn (StockHistory $stockHistory) => $this->wrapStockHistory($stockHistory))
        );
    }

    private function wrapStockHistory(StockHistory $stockHistory): StockHistoryItemData
    {
        return StockHistoryItemData::from([
            'id' => $stockHistory->id,
            'change' => $stockHistory->change,
            'stock' => [
                'warehouse' => $stockHistory->stock->warehouse,
                'product' => $stockHistory->stock->product,
            ],
            'created_at' => $stockHistory->created_at,
            'updated_at' => $stockHistory->updated_at,
        ]);
    }
}

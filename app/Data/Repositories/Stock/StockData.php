<?php

namespace App\Data\Repositories\Stock;

use App\Data\Repositories\Product\ProductData;
use App\Data\Repositories\Warehouse\WarehouseData;
use Spatie\LaravelData\Data;

class StockData extends Data
{
    public function __construct(
        public WarehouseData $warehouse,
        public ProductData $product,
    ) {}
}

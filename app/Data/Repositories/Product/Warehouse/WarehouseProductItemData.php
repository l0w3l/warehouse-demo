<?php

namespace App\Data\Repositories\Product\Warehouse;

use Spatie\LaravelData\Data;

class WarehouseProductItemData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $stock,
    ) {}
}

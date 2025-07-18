<?php

namespace App\Data\Repositories\Product;

use Spatie\LaravelData\Data;

class ProductWarehouseData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $stock
    ) {}
}

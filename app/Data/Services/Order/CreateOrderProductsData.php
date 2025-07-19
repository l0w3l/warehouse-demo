<?php

namespace App\Data\Services\Order;

use Spatie\LaravelData\Data;

class CreateOrderProductsData extends Data
{
    public function __construct(
        public int $id,
        public int $quantity
    ) {}
}

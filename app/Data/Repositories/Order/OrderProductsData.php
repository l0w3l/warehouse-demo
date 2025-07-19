<?php

namespace App\Data\Repositories\Order;

use DateTimeImmutable;
use Spatie\LaravelData\Data;

class OrderProductsData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $quantity,

        public DateTimeImmutable $created_at,
        public DateTimeImmutable $updated_at,
    ) {}
}

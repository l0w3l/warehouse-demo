<?php

namespace App\Data\Repositories\Product;

use DateTimeImmutable;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,

        public DateTimeImmutable $created_at,
        public DateTimeImmutable $updated_at,
    ) {}
}

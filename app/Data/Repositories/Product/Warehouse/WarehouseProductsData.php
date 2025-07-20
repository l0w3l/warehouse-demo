<?php

namespace App\Data\Repositories\Product\Warehouse;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class WarehouseProductsData extends Data
{
    public function __construct(
        public int $id,
        public string $name,

        /**
         * @var WarehouseProductItemData[]
         */
        #[DataCollectionOf(WarehouseProductItemData::class)]
        public Collection $products,

        public \DateTimeImmutable $created_at,
        public \DateTimeImmutable $updated_at,
    ) {}
}

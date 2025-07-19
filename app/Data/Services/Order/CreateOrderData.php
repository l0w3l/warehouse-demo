<?php

namespace App\Data\Services\Order;

use DateTimeImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class CreateOrderData extends Data
{
    public function __construct(
        public string                         $customer,
        public int                            $warehouse_id,
        /**
         * @var Collection<CreateOrderProductsData>
         */
        #[DataCollectionOf(CreateOrderProductsData::class)]
        public Collection $products,
        public ?DateTimeImmutable             $completed_at = null,
    )
    {
    }
}

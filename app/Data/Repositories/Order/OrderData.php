<?php

namespace App\Data\Repositories\Order;

use App\Data\Repositories\Warehouse\WarehouseData;
use App\Enums\Models\Order\OrderStatusEnum;
use DateTimeImmutable;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public int $id,
        public string $customer,
        public float $total_amount,
        public int $total_quantity,
        public OrderStatusEnum $status,

        /**
         * @var OrderProductsData[]
         */
        public array $products,
        public WarehouseData $warehouse,

        public ?DateTimeImmutable $completed_at,
        public DateTimeImmutable $created_at,
        public DateTimeImmutable $updated_at,
    ) {}
}

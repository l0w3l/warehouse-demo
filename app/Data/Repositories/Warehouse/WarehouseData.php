<?php

namespace App\Data\Repositories\Warehouse;

use Spatie\LaravelData\Data;

class WarehouseData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public \DateTimeImmutable $created_at,
        public \DateTimeImmutable $updated_at,
    ) {}
}

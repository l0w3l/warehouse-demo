<?php

namespace App\Data\Repositories\StockHistory;

use App\Data\Repositories\Stock\StockData;
use Spatie\LaravelData\Data;

class StockHistoryItemData extends Data
{
    public function __construct(
        public string $id,
        public string $change,

        public StockData $stock,

        public \DateTimeImmutable $created_at,
        public \DateTimeImmutable $updated_at,
    ) {}
}

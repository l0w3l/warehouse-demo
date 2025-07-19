<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface OrderServiceInterface extends ServiceInterface
{
    public function all(int $offset = 0, int $limit = 10, OrderFiltersEnum|string|null $filter = null): Collection;
}

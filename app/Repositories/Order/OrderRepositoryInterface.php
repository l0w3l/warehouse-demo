<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10, ?OrderFiltersEnum $filtersEnum = null): Collection;
}

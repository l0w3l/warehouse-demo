<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class OrderService extends AbstractService implements OrderServiceInterface
{
    public function __construct(
        public OrderRepositoryInterface $repository,
    ) {}

    public function all(int $offset = 0, int $limit = 10, OrderFiltersEnum|string|null $filter = null): Collection
    {
        if (is_string($filter)) {
            $filter = OrderFiltersEnum::tryFrom($filter);
        }

        return $this->repository->all($offset, $limit, $filter);
    }
}

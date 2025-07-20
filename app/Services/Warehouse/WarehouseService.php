<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class WarehouseService extends AbstractService implements WarehouseServiceInterface
{
    public function __construct(
        public WarehouseRepositoryInterface $repository,
    ) {}

    public function all(int $offset = 0, int $limit = 10): Collection
    {
        return $this->repository->all($offset, $limit);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories\Warehouse;

use App\Data\Repositories\Warehouse\WarehouseData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface WarehouseRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<WarehouseData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;
}

<?php

declare(strict_types=1);

namespace App\Repositories\Warehouse;

use App\Data\Repositories\Warehouse\WarehouseData;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class WarehouseRepository extends AbstractRepository implements WarehouseRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10): Collection
    {
        $warehouses = Warehouse::offset($offset)->limit($limit)->get();

        return WarehouseData::collect($warehouses);
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use App\Data\Repositories\Warehouse\WarehouseData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface WarehouseServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<WarehouseData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;
}

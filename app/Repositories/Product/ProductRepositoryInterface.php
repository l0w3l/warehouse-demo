<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Data\Repositories\Product\FullProductData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<FullProductData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;

    /**
     * @return Collection<FullProductData>
     */
    public function allProductsBy(int $warehouseId): Collection;
}

<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Data\Repositories\Product\ProductData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<ProductData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;
}

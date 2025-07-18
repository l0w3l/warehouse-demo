<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Data\Repositories\Product\ProductData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface ProductServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<ProductData>
     */
    public function all(int $offset = 0, int $limit = 10): Collection;
}

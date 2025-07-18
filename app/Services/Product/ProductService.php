<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class ProductService extends AbstractService implements ProductServiceInterface
{
    public function __construct(
        public ProductRepositoryInterface $productRepository
    ) {}

    public function all(int $offset = 0, int $limit = 10): Collection
    {
        return $this->productRepository->all($offset, $limit);
    }
}

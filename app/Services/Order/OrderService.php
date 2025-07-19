<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Data\Repositories\Order\OrderData;
use App\Data\Repositories\Order\OrderProductsData;
use App\Data\Repositories\Product\ProductData;
use App\Data\Services\Order\CreateOrderData;
use App\Data\Services\Order\CreateOrderProductsData;
use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Exceptions\Repositories\DBTransactionException;
use App\Exceptions\Services\Order\CannotCreateOrderException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class OrderService extends AbstractService implements OrderServiceInterface
{
    public function __construct(
        public OrderRepositoryInterface $orderRepository,
        public WarehouseRepositoryInterface $warehouseRepository,
        public ProductRepositoryInterface $productRepository,
    ) {}

    public function all(int $offset = 0, int $limit = 10, OrderFiltersEnum|string|null $filter = null): Collection
    {
        if (is_string($filter)) {
            $filter = OrderFiltersEnum::tryFrom($filter);
        }

        return $this->orderRepository->all($offset, $limit, $filter);
    }

    public function create(CreateOrderData $orderInfo): OrderData
    {
        try {
            $productsByGivenWarehouse = $this->productRepository->allProductsBy($orderInfo->warehouse_id);

            $productsAndQuantityFiltered = (new Collection($orderInfo->products))
                ->filter(fn (CreateOrderProductsData $x) => $productsByGivenWarehouse->contains('id', '=', $x->id))
                ->toArray();

            return $this->orderRepository->create(
                $orderInfo->customer,
                $orderInfo->warehouse_id,
                $productsAndQuantityFiltered,
            );
        } catch (DBTransactionException $e) {
            throw new CannotCreateOrderException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

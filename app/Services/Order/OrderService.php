<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Data\Repositories\Order\OrderData;
use App\Data\Services\Order\CreateOrderData;
use App\Data\Services\Order\CreateOrderProductsData;
use App\Data\Services\Order\UpdateOrderData;
use App\Enums\Models\Order\OrderStatusEnum;
use App\Enums\Repositories\Order\OrderSortEnum;
use App\Exceptions\Repositories\DBTransactionException;
use App\Exceptions\Services\Order\CannotCreateOrderException;
use App\Exceptions\Services\Order\FailedOrderCompleteAction;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use App\Services\StockHistory\StockHistoryServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class OrderService extends AbstractService implements OrderServiceInterface
{
    public function __construct(
        public StockHistoryServiceInterface $stockHistoryService,

        public OrderRepositoryInterface $orderRepository,
        public WarehouseRepositoryInterface $warehouseRepository,
        public ProductRepositoryInterface $productRepository,
    ) {}

    public function all(int $offset = 0, int $limit = 10, OrderSortEnum|string|null $sort = null, OrderStatusEnum|string|null $filter = null): Collection
    {
        if (is_string($sort)) {
            $sort = OrderSortEnum::tryFrom($sort);
        }

        if (is_string($filter)) {
            $filter = OrderStatusEnum::tryFrom($filter);
        }

        return $this->orderRepository->all($offset, $limit, $sort, $filter);
    }

    public function count(string|OrderStatusEnum|null $filter = null): int
    {
        if (is_string($filter)) {
            $filter = OrderStatusEnum::tryFrom($filter);
        }

        return $this->orderRepository->count($filter);
    }

    public function create(CreateOrderData $createOrderData): OrderData
    {
        try {
            $productsByGivenWarehouse = $this->productRepository->allProductsBy($createOrderData->warehouse_id);

            $productsAndQuantityFiltered = (new Collection($createOrderData->products))
                ->filter(fn (CreateOrderProductsData $x) => $productsByGivenWarehouse->products->contains('id', '=', $x->id))
                ->toArray();

            return $this->orderRepository->create(
                $createOrderData->customer,
                $createOrderData->warehouse_id,
                $productsAndQuantityFiltered,
            );
        } catch (DBTransactionException $e) {
            throw new CannotCreateOrderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function update(OrderData|int $orderData, UpdateOrderData $updateOrderData): OrderData
    {
        try {
            $orderData = $orderData->id ?? $orderData;

            $productsByGivenWarehouse = $this->productRepository->allProductsBy($updateOrderData->warehouse_id);

            $productsAndQuantityFiltered = (new Collection($updateOrderData->products))
                ->filter(fn (CreateOrderProductsData $x) => $productsByGivenWarehouse->products->contains('id', '=', $x->id))
                ->toArray();

            return $this->orderRepository->update(
                $orderData,
                $updateOrderData->customer,
                $updateOrderData->warehouse_id,
                $productsAndQuantityFiltered,
            );
        } catch (DBTransactionException $e) {
            throw new CannotCreateOrderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function cancel(OrderData|int $orderData): OrderData
    {
        $orderData = $orderData->id ?? $orderData;

        return $this->orderRepository->setStatus($orderData, OrderStatusEnum::CANCELLED);
    }

    public function restore(OrderData|int $orderData): OrderData
    {
        $orderData = $orderData->id ?? $orderData;

        return $this->orderRepository->setStatus($orderData, OrderStatusEnum::ACTIVE);
    }

    public function complete(OrderData|int $orderData): OrderData
    {
        $orderData = $orderData->id ?? $orderData;

        try {
            return DB::transaction(function () use ($orderData) {
                $orderData = $this->orderRepository->setStatus($orderData, OrderStatusEnum::COMPLETED);

                foreach ($orderData->products as $product) {
                    $this->warehouseRepository->decStock(
                        $orderData->warehouse->id,
                        $product->id,
                        $product->quantity,
                    );
                    $this->stockHistoryService->decAction($orderData->warehouse->id, $product->id, $product->quantity);
                }

                return $orderData;
            });
        } catch (\Throwable $e) {

            throw new FailedOrderCompleteAction("Failed complete order action\n{$e->getMessage()}", previous: $e);
        }
    }
}

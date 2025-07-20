<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Data\Repositories\Order\OrderData;
use App\Enums\Models\Order\OrderStatusEnum;
use App\Enums\Repositories\Order\OrderSortEnum;
use App\Exceptions\Repositories\DBTransactionException;
use App\Exceptions\Repositories\Order\CanceledToCompleteStatusException;
use App\Exceptions\Repositories\Order\CannotUpdateNonActiveOrders;
use App\Exceptions\Repositories\Order\ChangeCompletedStatusException;
use App\Exceptions\Repositories\Order\OrderNotFoundException;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10, ?OrderSortEnum $sortEnum = null, ?OrderStatusEnum $filter = null): Collection
    {
        $ordersQuery = Order::with('warehouse', 'order_items');

        if ($sortEnum) {
            $ordersQuery = $sortEnum->resolve($ordersQuery);
        }
        if ($filter) {
            $ordersQuery = $ordersQuery->where('status', $filter);
        }

        $orders = $ordersQuery->offset($offset)->limit($limit)->get();

        return $this->collectOrders($orders);
    }

    public function count(?OrderStatusEnum $filter = null): int
    {
        if ($filter) {
            return Order::whereStatus($filter)->count();
        }
        return Order::count();
    }

    public function create(string $customer, int $warehouseId, array $products = []): OrderData
    {
        try {
            $order = DB::transaction(function () use ($customer, $warehouseId, $products) {
                $order = Order::create([
                    'customer' => $customer,
                    'warehouse_id' => $warehouseId,
                    'status' => ORderStatusEnum::ACTIVE,
                    'completed_at' => null,
                ]);

                $order->order_items()->createMany(
                    (new Collection($products))->map(fn ($product) => [
                        'order_id' => $order->id,
                        'product_id' => $product['id'],
                        'count' => $product['quantity'],
                    ])
                );

                return $order;
            });

            return $this->wrapOrder($order);
        } catch (\Throwable $e) {
            throw new DBTransactionException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function update(int $orderId, string $customer, int $warehouseId, array $products = []): OrderData
    {
        try {
            $order = DB::transaction(function () use ($orderId, $customer, $warehouseId, $products) {
                $order = Order::find($orderId) ?? throw new OrderNotFoundException("Order with id {$orderId} not found");

                if ($order->status !== OrderStatusEnum::ACTIVE) {
                    throw new CannotUpdateNonActiveOrders('Cannot update non active order');
                }

                $order->update([
                    'customer' => $customer,
                    'warehouse_id' => $warehouseId,
                    'completed_at' => null,
                ]);

                $order->order_items()->createMany(
                    (new Collection($products))->map(fn ($product) => [
                        'order_id' => $order->id,
                        'product_id' => $product['id'],
                        'count' => $product['quantity'],
                    ])
                );

                return $order;
            });

            return $this->wrapOrder($order);
        } catch (\Throwable $e) {
            throw new DBTransactionException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function setStatus(int $orderId, OrderStatusEnum $orderStatusEnum): OrderData
    {
        $order = Order::with('warehouse.stocks')->find($orderId) ?? throw new OrderNotFoundException("Order with id {$orderId} not found");

        if ($order->status === OrderStatusEnum::COMPLETED) {
            throw new ChangeCompletedStatusException('Cannot change completed order');
        }

        if ($order->status === OrderStatusEnum::CANCELLED && $orderStatusEnum === OrderStatusEnum::COMPLETED) {
            throw new CanceledToCompleteStatusException('Cannot complete canceled order');
        }

        if ($orderStatusEnum === OrderStatusEnum::COMPLETED) {
            $order->update([
                'status' => $orderStatusEnum,
                'completed_at' => now(),
            ]);
        } else {
            $order->update([
                'status' => $orderStatusEnum,
            ]);
        }

        return $this->wrapOrder($order);
    }

    /**
     * @param  EloquentCollection<Order>  $orders
     * @return Collection<OrderData>
     */
    private function collectOrders(EloquentCollection $orders): Collection
    {
        return OrderData::collect(
            $orders->map(function (Order $order) {
                return $this->wrapOrder($order);
            }),
        );
    }

    private function wrapOrder(Order $order): OrderData
    {
        return OrderData::from([
            'id' => $order->id,
            'customer' => $order->customer,
            'total_amount' => $order->total_price(),
            'total_quantity' => $order->total_quantity(),
            'status' => $order->status,
            'products' => $order->order_items->unique('product_id')->map(function (OrderItem $orderItem) use ($order) {
                return [
                    'id' => $orderItem->product->id,
                    'name' => $orderItem->product->name,
                    'price' => $orderItem->product->price,
                    'quantity' => $order->order_items->filter(fn (OrderItem $filtered) => $filtered->product_id === $orderItem->product_id)->count(),
                    'updated_at' => $orderItem->product->updated_at,
                    'created_at' => $orderItem->product->created_at,
                ];
            }),
            'warehouse' => $order->warehouse,
            'completed_at' => $order->completed_at,
            'updated_at' => $order->updated_at,
            'created_at' => $order->created_at,
        ]);
    }
}

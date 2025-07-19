<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Data\Repositories\Order\OrderData;
use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Enums\Models\Order\OrderStatusEnum;
use App\Exceptions\Repositories\DBTransactionException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10, ?OrderFiltersEnum $filtersEnum = null): Collection
    {
        $ordersQuery = Order::with('warehouse', 'order_items');

        if ($filtersEnum) {
            $ordersQuery = $filtersEnum->resolve($ordersQuery);
        }

        $orders = $ordersQuery->offset($offset)->limit($limit)->get();

        return $this->collectOrders($orders);
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

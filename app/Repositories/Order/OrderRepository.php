<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Data\Repositories\Order\OrderData;
use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
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

        return OrderData::collect(
            $orders->map(function (Order $order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->customer,
                    'total_amount' => $order->total_price(),
                    'total_quantity' => $order->total_quantity(),
                    'products' => $order->order_items->map(function (OrderItem $orderItem) {
                        return [
                            'id' => $orderItem->product->id,
                            'name' => $orderItem->product->name,
                            'price' => $orderItem->product->price,
                            'updated_at' => $orderItem->product->updated_at,
                            'created_at' => $orderItem->product->created_at,
                        ];
                    }),
                    'warehouse' => $order->warehouse,
                    'completed_at' => $order->updated_at,
                    'updated_at' => $order->updated_at,
                    'created_at' => $order->created_at,
                ];
            }),
        );
    }
}

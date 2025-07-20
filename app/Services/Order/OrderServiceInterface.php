<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Data\Repositories\Order\OrderData;
use App\Data\Services\Order\CreateOrderData;
use App\Data\Services\Order\UpdateOrderData;
use App\Enums\Models\Order\OrderStatusEnum;
use App\Enums\Repositories\Order\OrderSortEnum;
use App\Exceptions\Services\Order\CannotCreateOrderException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface OrderServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<OrderData>
     */
    public function all(int $offset = 0, int $limit = 10, OrderSortEnum|string|null $sort = null): Collection;

    public function count(OrderStatusEnum|string|null $filter): int;

    /**
     * @throws CannotCreateOrderException
     */
    public function create(CreateOrderData $createOrderData): OrderData;

    public function update(OrderData|int $orderData, UpdateOrderData $updateOrderData): OrderData;

    public function cancel(OrderData|int $orderData): OrderData;

    public function restore(OrderData|int $orderData): OrderData;

    public function complete(OrderData|int $orderData): OrderData;
}

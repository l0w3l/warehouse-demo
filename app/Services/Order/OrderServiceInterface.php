<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Data\Repositories\Order\OrderData;
use App\Data\Services\Order\CreateOrderData;
use App\Data\Services\Order\UpdateOrderData;
use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Exceptions\Services\Order\CannotCreateOrderException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface OrderServiceInterface extends ServiceInterface
{
    /**
     * @return Collection<OrderData>
     */
    public function all(int $offset = 0, int $limit = 10, OrderFiltersEnum|string|null $filter = null): Collection;

    /**
     * @throws CannotCreateOrderException
     */
    public function create(CreateOrderData $createOrderData): OrderData;

    public function update(OrderData|int $orderData, UpdateOrderData $updateOrderData): OrderData;

    public function cancel(OrderData|int $orderData): OrderData;

    public function restore(OrderData|int $orderData): OrderData;

    public function complete(OrderData|int $orderData): OrderData;
}

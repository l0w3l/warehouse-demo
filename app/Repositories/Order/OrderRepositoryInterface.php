<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Data\Repositories\Order\OrderData;
use App\Enums\Enums\Reposiitories\Order\OrderFiltersEnum;
use App\Enums\Models\Order\OrderStatusEnum;
use App\Exceptions\Repositories\DBTransactionException;
use App\Exceptions\Repositories\Order\ChangeCompletedStatusException;
use App\Exceptions\Repositories\Order\OrderNotFoundException;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<OrderData>
     */
    public function all(int $offset = 0, int $limit = 10, ?OrderFiltersEnum $filtersEnum = null): Collection;

    /**
     * @param array{
     *     id: int,
     *     quantity: int
     * } $products
     *
     * @throws DBTransactionException
     */
    public function create(string $customer, int $warehouseId, array $products = []): OrderData;

    /**
     * @param array{
     *     id: int,
     *     quantity: int
     * } $products
     *
     * @throws DBTransactionException
     */
    public function update(int $orderId, string $customer, int $warehouseId, array $products = []): OrderData;

    /**
     * @throws ChangeCompletedStatusException
     * @throws OrderNotFoundException
     */
    public function setStatus(int $orderId, OrderStatusEnum $orderStatusEnum): OrderData;
}

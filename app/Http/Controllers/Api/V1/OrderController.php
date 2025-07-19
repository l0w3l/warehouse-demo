<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\Services\Order\CreateOrderData;
use App\Data\Services\Order\UpdateOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Order\StoreOrderRequest;
use App\Http\Requests\V1\Order\UpdateOrderRequest;
use App\Http\Resources\V1\Order\OrderItemResource;
use App\Services\Order\OrderServiceInterface;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        public OrderServiceInterface $orderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $filter = $request->get('filter', null);

        $orders = $this->orderService->all($offset, $limit, $filter);

        return OrderItemResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $payload = $request->validated();
            $createOrderData = CreateOrderData::from($payload);

            $order = $this->orderService->create($createOrderData);

            return OrderItemResource::make($order);
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, int $order)
    {
        try {
            $payload = $request->validated();
            $updateOrderData = UpdateOrderData::from($payload);

            $order = $this->orderService->update($order, $updateOrderData);

            return OrderItemResource::make($order);
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function cancel(int $order)
    {
        try {
            $order = $this->orderService->cancel($order);

            return OrderItemResource::make($order);
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function restore(int $order)
    {
        try {
            $order = $this->orderService->restore($order);

            return OrderItemResource::make($order);
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function complete(int $order)
    {
        try {
            $order = $this->orderService->complete($order);

            return OrderItemResource::make($order);
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }
}

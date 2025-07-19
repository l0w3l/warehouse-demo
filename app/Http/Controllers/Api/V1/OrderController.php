<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\Services\Order\CreateOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Order\StoreOrderRequest;
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

            return OrderItemResource::make($this->orderService->create($createOrderData));
        } catch (Exception $e) {
            return $this->notFoundJson([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\StockHistory\StockHistoryResource;
use App\Http\Resources\V1\Warehouse\WarehouseItemResource;
use App\Http\Resources\V1\Warehouse\WarehouseProductsResource;
use App\Services\Product\ProductServiceInterface;
use App\Services\StockHistory\StockHistoryServiceInterface;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct(
        public WarehouseServiceInterface $warehouseService,
        public StockHistoryServiceInterface $stockHistoryService,
        public ProductServiceInterface $productService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);

        $warehouses = $this->warehouseService->all($offset, $limit);

        return WarehouseItemResource::collection($warehouses)
            ->additional(['count' => $this->warehouseService->count()]);
    }

    public function history(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $warehouseId = $request->get('warehouse_id', null);

        $stockHistory = $this->stockHistoryService->get($warehouseId, $offset, $limit);

        return StockHistoryResource::collection($stockHistory)
            ->additional(['count' => $this->stockHistoryService->count()]);
    }

    public function products(Request $request, int $warehouse)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);

        $products = $this->productService->allFor($warehouse, $offset, $limit);

        return WarehouseProductsResource::make($products)
            ->additional(['count' => $this->productService->countFor($warehouse)]);
    }
}

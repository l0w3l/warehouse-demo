<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Warehouse\WarehouseItemResource;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct(
        public WarehouseServiceInterface $warehouseService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);

        $warehouses = $this->warehouseService->all($offset, $limit);

        return WarehouseItemResource::collection($warehouses);
    }
}

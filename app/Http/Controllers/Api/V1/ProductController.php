<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductItemResource;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        public ProductServiceInterface $productService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);

        $products = $this->productService->all($offset, $limit);

        return ProductItemResource::collection($products)
            ->additional(['count' => $products->count()]);
    }
}

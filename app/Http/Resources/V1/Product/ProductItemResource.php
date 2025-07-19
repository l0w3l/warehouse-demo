<?php

namespace App\Http\Resources\V1\Product;

use App\Data\Repositories\Product\ProductData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductData
 */
class ProductItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'warehouses' => $this->warehouses,
        ];
    }
}

<?php

namespace App\Http\Resources\V1\Warehouse;

use App\Data\Repositories\Product\Warehouse\WarehouseProductsData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WarehouseProductsData
 */
class WarehouseProductsResource extends JsonResource
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
            'products' => $this->products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

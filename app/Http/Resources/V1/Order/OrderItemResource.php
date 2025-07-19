<?php

namespace App\Http\Resources\V1\Order;

use App\Data\Repositories\Order\OrderData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderData
 */
class OrderItemResource extends JsonResource
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
            'customer' => $this->customer,
            'total_amount' => $this->total_amount,
            'total_quantity' => $this->total_quantity,

            'products' => $this->products,
            'warehouse' => $this->warehouse,

            'completed_at' => $this->completed_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}

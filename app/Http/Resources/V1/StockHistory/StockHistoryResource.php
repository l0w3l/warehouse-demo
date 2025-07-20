<?php

namespace App\Http\Resources\V1\StockHistory;

use App\Data\Repositories\StockHistory\StockHistoryItemData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin StockHistoryItemData
 */
class StockHistoryResource extends JsonResource
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
            'change' => $this->change,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse $warehouse
 *
 * @method static \Database\Factories\StockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class Stock extends Model
{
    /** @use HasFactory<\Database\Factories\StockFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
    ];

    /**
     * @return BelongsTo<Product>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<Warehouse>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}

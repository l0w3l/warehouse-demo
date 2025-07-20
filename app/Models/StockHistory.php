<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $stock_id
 * @property int $change
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Stock $stock
 *
 * @method static \Database\Factories\StockHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory whereChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockHistory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StockHistory extends Model
{
    /** @use HasFactory<\Database\Factories\StockHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'change',
    ];

    /**
     * @return BelongsTo<Stock>
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}

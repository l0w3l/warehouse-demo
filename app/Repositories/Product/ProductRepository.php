<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Data\Repositories\Product\FullProductData;
use App\Data\Repositories\Product\Warehouse\WarehouseProductsData;
use App\Exceptions\Repositories\Warehouse\WarehouseNotFoundException;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10): Collection
    {
        $products = Product::with('stocks.warehouse')->offset($offset)->limit($limit)->get();

        return $this->collectProductData($products);
    }

    public function count(): int
    {
        return Product::count();
    }

    public function allProductsBy(int $warehouseId, int $offset = 0, int $limit = 10): WarehouseProductsData
    {
        $warehouse = Warehouse::find($warehouseId) ?? throw new WarehouseNotFoundException;

        $products = Product::with('stocks.warehouse')
            ->whereHas('stocks', fn (Builder $builder) => $builder->where('warehouse_id', $warehouseId))
            ->offset($offset)->limit($limit)->get();

        return $this->wrapToWarehouseProducts($warehouse, $products);
    }

    public function countFor(int $warehouseId): int
    {
        return Product::with('stocks.warehouse')
            ->whereHas('stocks', fn (Builder $builder) => $builder->where('warehouse_id', $warehouseId))
            ->count();
    }

    /**
     * @param  EloquentCollection<Product>  $products
     * @return Collection<FullProductData>
     */
    private function collectProductData(EloquentCollection $products): Collection
    {
        return FullProductData::collect(
            $products->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'warehouses' => $product->stocks->unique('warehouse_id')->sortBy('warehouse_id')->map(function (Stock $stock) {
                        return [
                            'id' => $stock->warehouse->id,
                            'name' => $stock->warehouse->name,
                            'stock' => $stock->stock,
                        ];
                    }),
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            })
        );
    }

    /**
     * @return WarehouseProductsData<Product>
     */
    private function wrapToWarehouseProducts(Warehouse $warehouse, EloquentCollection $products): WarehouseProductsData
    {
        return WarehouseProductsData::from([
            'id' => $warehouse->id,
            'name' => $warehouse->name,
            'products' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stocks->where('warehouse_id', $warehouse->id)->reduce(fn (int $carry, Stock $stock) => $carry + $stock->stock, 0),
            ]),
            'updated_at' => $warehouse->updated_at,
            'created_at' => $warehouse->created_at,
        ]);
    }
}

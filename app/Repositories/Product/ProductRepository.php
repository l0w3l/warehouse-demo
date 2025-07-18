<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Data\Repositories\Product\ProductData;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function all(int $offset = 0, int $limit = 10): Collection
    {
        $products = Product::with('stocks.warehouse')->offset($offset)->limit($limit)->get();

        return ProductData::collect(
            $products->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'warehouses' => $product->stocks->map(function (Stock $stock) {
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
}

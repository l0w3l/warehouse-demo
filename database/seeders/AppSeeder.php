<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    const PRODUCTS_COUNT = 10;
    const WAREHOUSE_COUNT = 5;
    const ORDERS_COUNT = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Collection<Product> $products */
        $products = Product::factory()->count(self::PRODUCTS_COUNT)->create();
        /** @var Collection<Warehouse> $warehouses */
        $warehouses = Warehouse::factory()->count(self::WAREHOUSE_COUNT)->create();

        foreach (range(0, self::ORDERS_COUNT) as $_) {
            /** @var Warehouse $warehouse */
            $warehouse = $warehouses->random();
            /** @var Collection<Product> $concreteProducts */
            $concreteProducts = new Collection(fake()->unique()->randomElements($products, fake()->numberBetween(1, $products->count() - 1)));

            foreach ($concreteProducts as $product) {
                Stock::factory()->create([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ]);
            }

            foreach (range(0, self::ORDERS_COUNT) as $_) {
                /** @var Product $product */
                $product = $concreteProducts->random();

                $order = Order::factory()->create([
                    'warehouse_id' => $warehouse->id,
                ]);

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}

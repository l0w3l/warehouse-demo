<?php

namespace Database\Seeders;

use App\Enums\Models\Order\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

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
        $time = now()->subYear();

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
                $order = Order::factory()->create([
                    'warehouse_id' => $warehouse->id,
                    'created_at' => $time->addDay()->toImmutable(),
                    'updated_at' => $time->addDay()->toImmutable(),
                ]);

                foreach (range(0, fake()->numberBetween(1, 10)) as $_) {
                    /** @var Product $product */
                    $product = $concreteProducts->random();

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);
                }

                if ($order->status === OrderStatusEnum::COMPLETED && $order->order_items()->exists()) {
                    $order->update([
                        'status' => OrderStatusEnum::ACTIVE,
                    ]);
                    try {
                        App::make(OrderServiceInterface::class)->complete($order->id);
                    } catch (\Throwable $th) {
                        //
                    }
                }

            }
        }
    }
}

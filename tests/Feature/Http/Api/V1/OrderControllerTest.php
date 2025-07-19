<?php

use App\Models\Stock;
use Database\Seeders\AppSeeder;

beforeEach(function () {
    $this->seed(AppSeeder::class);
});

test('order index test', function () {
    $response = $this->getJson(route('api.v1.orders.index'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'customer',
                    'total_amount',
                    'total_quantity',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity',
                            'updated_at',
                            'created_at',
                        ],
                    ],
                    'completed_at',
                    'updated_at',
                    'created_at',
                ],
            ],
        ]);
});

test('order store test', function () {
    $warehouse = Stock::first()->warehouse;

    $testRequest = [
        'customer' => 'test',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stock->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $response = $this->postJson(route('api.v1.orders.store'), $testRequest);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer',
                'total_amount',
                'total_quantity',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'quantity',
                        'updated_at',
                        'created_at',
                    ],
                ],
                'completed_at',
                'updated_at',
                'created_at',
            ],
        ]);
});

<?php

use App\Models\Order;
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

test('order update test', function () {
    $warehouse = Stock::first()->warehouse;

    $testRequest = [
        'customer' => 'test',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stock->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $creationResponse = $this->postJson(route('api.v1.orders.store'), $testRequest);

    $warehouse = Stock::latest()->first()->warehouse;

    $testRequest2 = [
        'customer' => 'test2',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stock->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $updateResponse = $this->putJson(route('api.v1.orders.update', [
        'order' => Order::latest('id')->first()->id,
    ]), $testRequest2);

    $updateResponse->assertOk()
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

    expect($updateResponse->json('data.id'))
        ->toEqual($creationResponse->json('data.id'))
        ->and($updateResponse->json('data.customer'))
        ->not()->toEqual($creationResponse->json('data.customer'));
});

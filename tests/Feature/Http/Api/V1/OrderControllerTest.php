<?php

use App\Enums\Models\Order\OrderStatusEnum;
use App\Models\Order;
use App\Models\Stock;
use App\Models\StockHistory;
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
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
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
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $creationResponse = $this->postJson(route('api.v1.orders.store'), $testRequest);

    $warehouse = Stock::latest()->first()->warehouse;

    $testRequest2 = [
        'customer' => 'test2',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
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

test('order cancel test', function () {
    $warehouse = Stock::first()->warehouse;

    $testRequest = [
        'customer' => 'test',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $creationResponse = $this->postJson(route('api.v1.orders.store'), $testRequest);

    $updateStatusResponse = $this->getJson(route('api.v1.orders.cancel', [
        'order' => Order::latest('id')->first()->id,
    ]));

    $updateStatusResponse->assertOk()
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

    expect($updateStatusResponse->json('data.status'))
        ->toEqual(OrderStatusEnum::CANCELLED->value)
        ->and($updateStatusResponse->json('data.status'))
        ->not()->toEqual($creationResponse->json('data.status'));
});

test('order restore test', function () {
    $warehouse = Stock::first()->warehouse;

    $testRequest = [
        'customer' => 'test',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $creationResponse = $this->postJson(route('api.v1.orders.store'), $testRequest);

    expect($creationResponse->json('data.status'))->toEqual(OrderStatusEnum::ACTIVE->value);

    $cancelResponse = $this->getJson(route('api.v1.orders.cancel', [
        'order' => Order::latest('id')->first()->id,
    ]));

    expect($cancelResponse->json('data.status'))->toEqual(OrderStatusEnum::CANCELLED->value);

    $updateStatusResponse = $this->getJson(route('api.v1.orders.restore', [
        'order' => Order::latest('id')->first()->id,
    ]));

    $updateStatusResponse->assertOk()
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

    expect($updateStatusResponse->json('data.status'))
        ->toEqual(OrderStatusEnum::ACTIVE->value)
        ->and($updateStatusResponse->json('data.status'))
        ->toEqual($creationResponse->json('data.status'));
});

test('order complete test', function () {
    $warehouse = Stock::first()->warehouse;

    $testRequest = [
        'customer' => 'test',
        'warehouse_id' => $warehouse->id,
        'products' => $warehouse->stocks->map(fn (Stock $stock) => [
            'id' => $stock->product_id,
            'quantity' => fake()->numberBetween(1, $stock->stock),
        ]),
    ];

    $creationResponse = $this->postJson(route('api.v1.orders.store'), $testRequest);

    $oldStock = Stock::where('warehouse_id', $warehouse->id)->get();

    expect($creationResponse->json('data.status'))->toEqual(OrderStatusEnum::ACTIVE->value)
        ->and(StockHistory::count())->toEqual(0);

    $completedResponse = $this->getJson(route('api.v1.orders.complete', [
        'order' => Order::latest('id')->first()->id,
    ]));

    $completedResponse->assertOk()
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

    expect($completedResponse->json('data.status'))->toEqual(OrderStatusEnum::COMPLETED->value)
        ->and(StockHistory::count())->toEqual(count($creationResponse->json('data.products')));

    $newStock = Stock::where('warehouse_id', $warehouse->id)->get();

    $this->assertNotEquals($oldStock->toArray(), $newStock->toArray());
});

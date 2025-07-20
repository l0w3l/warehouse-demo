<?php

use App\Models\Warehouse;
use Database\Seeders\AppSeeder;

beforeEach(function () {
    $this->seed(AppSeeder::class);
});

test('test warehouse index', function () {
    $response = $this->getJson(route('api.v1.warehouses.index'));

    $response->assertOk()
        ->assertJsonCount(
            Warehouse::count(), 'data'
        )->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
});

test('test warehouse products', function () {
    $warehouse = Warehouse::first();
    $response = $this->getJson(route('api.v1.warehouses.products', [
        'warehouse' => $warehouse->id,
    ]));

    $response->assertOk()
        ->assertJsonCount(
            $warehouse->stocks->unique('product_id')->count(), 'data.products'
        )->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'stock',
                    ],
                ],
                'created_at',
                'updated_at',
            ],
        ]);
});

<?php

use Database\Seeders\AppSeeder;

beforeEach(function () {
    $this->seed(AppSeeder::class);
});

test('product index test', function () {
    $response = $this->getJson(route('api.v1.products.index'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'warehouses' => [
                        '*' => [
                            'id',
                            'name',
                            'stock',
                        ],
                    ],
                ],
            ],
        ]);
});

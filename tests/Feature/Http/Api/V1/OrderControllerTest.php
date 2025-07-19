<?php

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

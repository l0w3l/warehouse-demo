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

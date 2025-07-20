<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Москва', 'Сочи', 'Калининград', 'Владивосток', 'Новосибирск', 'Екатеринбург'];

        return [
            'name' => 'Склад в '.$this->faker->unique()->randomElement($cities),
        ];
    }
}

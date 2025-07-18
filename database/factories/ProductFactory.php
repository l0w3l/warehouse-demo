<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Смартфон iPhone 15', 'Ноутбук MacBook Pro', 'Наушники AirPods Pro',
            'Планшет iPad Air', 'Умные часы Apple Watch', 'Фотоаппарат Canon EOS',
            'Телевизор Samsung QLED', 'Игровая консоль PlayStation 5',
            'Монитор Dell 27"', 'Клавиатура Logitech MX'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($products),
            'price' => $this->faker->randomFloat(2, 50,  200),
        ];
    }
}

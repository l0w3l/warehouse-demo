<?php

namespace Database\Factories;

use App\Enums\Models\Order\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer' => $this->faker->name,
            'status' => $this->faker->randomElement(OrderStatusEnum::toArray()),
            'completed_at' => $this->faker->randomElement([null, fake()->dateTime()]),
        ];
    }
}

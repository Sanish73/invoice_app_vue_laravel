<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

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
        return [
            'item_code' => 'IC-' . $this->faker->unique()->numberBetween(1000, 1500),
            'description' => 'Name of Product ' . $this->faker->unique()->numberBetween(10, 500),
            'unit_price' => $this->faker->numberBetween(100, 1000)
        ];
    }
}

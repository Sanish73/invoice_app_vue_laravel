<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_id' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'reference' => 'REF-'.$this->faker->optional()->word,
            'terms_and_conditions' => $this->faker->paragraph,
            'sub_total' => $this->faker->randomFloat(2, 100, 10000),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'total' => $this->faker->randomFloat(2, 0, 100)
        ];
    }
}

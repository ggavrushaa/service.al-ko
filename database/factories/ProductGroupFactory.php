<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code_1c' => $this->faker->unique()->numerify('1C###'),
            'name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

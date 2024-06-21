<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SymptomCodesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code_1c' => $this->faker->unique()->numerify('1C###'),
            'name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
            'parent_id' => $this->faker->numberBetween(1001, 1010),
            'is_deleted' => false,
            'is_folder' => false,
        ];
    }
}

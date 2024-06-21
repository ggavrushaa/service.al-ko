<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceWorks>
 */
class ServiceWorksFactory extends Factory
{

    public function definition(): array
    {
        return [
            'code_1c' => $this->faker->unique()->numerify('1C###'),
            'name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),  
            'product_group_id' => $this->faker->numberBetween(1001, 1010),
            'duration_minutes' => $this->faker->numberBetween(1, 1000),
            'duration_decimal' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

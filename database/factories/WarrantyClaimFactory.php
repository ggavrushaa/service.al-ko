<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WarrantyClaim>
 */
class WarrantyClaimFactory extends Factory
{

    public function definition(): array
    {
         $user = User::inRandomOrder()->first();

         $service_partner = DB::connection('mysql')->table('user_partners')->inRandomOrder()->first();
       
         $contract = Contract::where('order_type_id', 3)->inRandomOrder()->first();
 
         return [
             'code_1C' => $this->faker->unique()->numerify('1C###'),
             'number' => $this->faker->unique()->numerify('N###'),
             'product_article' => $this->faker->word(),
             'factory_number' => $this->faker->numerify('FN####'),
             'barcode' => $this->faker->ean13(),
             'client_name' => $this->faker->name(),
             'client_phone' => $this->faker->phoneNumber(),
             'product_name' => $this->faker->word(),
             'service_partner' => $service_partner ? $service_partner->id : null,
             'service_contract' => $contract ? $contract->id : null,
             'point_of_sale' => $service_partner ? $service_partner->id : null,
             'autor' => $user->id,
             'date' => $this->faker->dateTime(),
             'date_of_sale' => $this->faker->dateTimeBetween('-1 year', 'now'),
             'date_of_claim' => $this->faker->dateTimeBetween('-1 month', 'now'),
             'details' => $this->faker->paragraph(),
             'type_of_claim' => $this->faker->word(),
             'is_deleted' => false,
             'created_at' => now(),
             'updated_at' => now(),
         ];
    }
}

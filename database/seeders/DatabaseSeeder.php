<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // $this->call(WarrantyClaimSeeder::class);
        // $this->call(ProductGroupSeeder::class);
        // $this->call(ServiceWorksSeeder::class);
        // $this->call(DefectCodesSeeder::class);
        $this->call(SymptomCodesSeeder::class);
    }
}

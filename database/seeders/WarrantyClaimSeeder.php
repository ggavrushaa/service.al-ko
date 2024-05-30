<?php

namespace Database\Seeders;

use App\Models\WarrantyClaim;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarrantyClaimSeeder extends Seeder
{
    public function run(): void
    {
        WarrantyClaim::factory()->count(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\SymptomCodes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomCodesSeeder extends Seeder
{
    public function run(): void
    {
        SymptomCodes::factory()->count(10)->create();
    }
}

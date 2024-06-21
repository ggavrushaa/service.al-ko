<?php

namespace Database\Seeders;

use App\Models\DefectCodes;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefectCodesSeeder extends Seeder
{
    public function run(): void
    {
        DefectCodes::factory()->count(10)->create();
    }
}

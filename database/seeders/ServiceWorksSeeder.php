<?php

namespace Database\Seeders;

use App\Models\ServiceWorks;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceWorksSeeder extends Seeder
{
    public function run(): void
    {
        ServiceWorks::factory()->count(10)->create();
    }
}

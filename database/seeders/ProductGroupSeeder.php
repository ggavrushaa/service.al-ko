<?php

namespace Database\Seeders;

use App\Models\ProductGroup;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductGroupSeeder extends Seeder
{

    public function run(): void
    {
        ProductGroup::factory()->count(10)->create();
    }
}

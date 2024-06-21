<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_works_price', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->dateTime('date');
            $table->decimal('cost', 12, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_works_price');
    }
};

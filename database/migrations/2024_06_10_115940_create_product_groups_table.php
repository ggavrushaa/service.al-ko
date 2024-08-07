<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('code_1c', 40);
            $table->string('name', 200);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_groups');
    }
};

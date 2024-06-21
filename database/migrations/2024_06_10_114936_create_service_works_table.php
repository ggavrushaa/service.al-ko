<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('service_works', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('code_1C', 32);
            $table->string('name', 200);
            $table->foreignId('product_group_id')->constrained('product_groups')->onDelete('cascade');
            $table->decimal('duration_decimal', 12, 2);
            $table->bigInteger('duration_minutes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_works');
    }
};

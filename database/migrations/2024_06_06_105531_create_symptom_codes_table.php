<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('symptom_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code_1C');
            $table->string('name', 200);
            $table->bigInteger('parent_id')->nullable();
            $table->tinyInteger('is_folder')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->dateTime('created')->nullable();
            $table->dateTime('edited')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('symptom_codes');
    }
};

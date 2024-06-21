<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_conclusion_files', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->foreignId('technical_conclusion_id')->constrained('technical_conclusions')->onDelete('cascade');
            $table->string('path');
            $table->string('filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_conclusion_files');
    }
};

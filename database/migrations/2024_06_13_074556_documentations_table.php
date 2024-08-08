<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('name', 200);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
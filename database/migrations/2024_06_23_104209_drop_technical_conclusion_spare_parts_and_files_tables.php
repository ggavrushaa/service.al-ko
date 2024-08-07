<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('technical_conclusion_spare_parts');
        Schema::dropIfExists('technical_conclusion_files');
    }

    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            $table->string('code_1C', 255)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            //
        });
    }
};

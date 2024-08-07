<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('warranty_claims', function (Blueprint $table) {
            $table->integer('number')->unsigned()->nullable()->after('id');
            $table->unique('number');
        });
    }

    public function down(): void
    {
        Schema::table('warranty_claims', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->dropColumn('number'); 
        });
    }
};

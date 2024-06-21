<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('second_db')->table('technical_conclusions', function (Blueprint $table) {
            $table->string('deteails_reason', 500)->after('details');
        });
    }

    public function down(): void
    {
        Schema::connection('second_db')->table('technical_conclusions', function (Blueprint $table) {
            $table->dropColumn('deteails_reason');
        });
    }

};

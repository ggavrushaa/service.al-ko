<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warranty_claims', function (Blueprint $table) {
            $table->dropForeign('fk_service_partner');
            $table->dropForeign('fk_service_contract');
            $table->dropForeign('fk_point_of_sale');
            $table->dropForeign('fk_autor');
        });
    }

    public function down(): void
    {
        Schema::table('warranty_claims', function (Blueprint $table) {
            //
        });
    }
};

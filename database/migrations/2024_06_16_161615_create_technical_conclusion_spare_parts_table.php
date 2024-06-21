<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('technical_conclusion_spare_parts', function (Blueprint $table) {

            $table->foreignId('technical_conclusion_id')->constrained('technical_conclusions')->onDelete('cascade');
            $table->bigInteger('spare_part_id')->unsigned();
            $table->foreign('spare_part_id')->references('id')->on('warranty_claim_spareparts')->onDelete('cascade');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('second_db')->table('technical_conclusion_spare_parts', function (Blueprint $table) {
            $table->dropForeign(['technical_conclusion_id']);
            $table->dropForeign(['spare_part_id']);
        });

        Schema::connection('second_db')->dropIfExists('technical_conclusion_spare_parts');
    }
};

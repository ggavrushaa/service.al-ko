<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('second_db')->create('technical_conclusions', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('code_1C');
            $table->string('number');

            $table->bigInteger('parent_doc')->unsigned();
            $table->foreign('parent_doc')->references('id')->on('warranty_claims')->onDelete('cascade');
            
            $table->string('client_name', 200);
            $table->string('client_phone', 50);
            $table->string('product_article', 50);
            $table->string('factory_number', 50);
            $table->string('product_name', 200);
            $table->string('barcode');

            $table->integer('service_partner');
            $table->integer('service_contract');

            $table->dateTime('date_of_sale');
            $table->integer('point_of_sale');
            $table->dateTime('date_of_claim');
            $table->string('details', 500);
            $table->string('type_of_claim');
            $table->bigInteger('defect_code')->unsigned()->nullable();
            $table->bigInteger('symptom_code')->unsigned()->nullable();

            $table->string('resolution', 500);
            $table->integer('autor');
            
            $table->dateTime('date');
            $table->timestamps();
            
            $table->foreign('service_partner')->references('id')->on('alko_db.user_partners')->onDelete('cascade')->name('fk_service_partner_tc');
            $table->foreign('service_contract')->references('id')->on('alko_db.contracts')->onDelete('cascade')->name('fk_service_contract_tc');
            $table->foreign('point_of_sale')->references('id')->on('alko_db.user_partners')->onDelete('cascade')->name('fk_point_of_sale_tc');
            $table->foreign('autor')->references('id')->on('alko_db.users')->onDelete('cascade')->name('fk_autor_tc');

            $table->string('conclusion', 500);
            $table->string('appeal_type');
        });
    }
    public function down(): void
    {
        Schema::connection('second_db')->table('technical_conclusions', function (Blueprint $table) {
            $table->dropForeign(['fk_service_partner']);
            $table->dropForeign(['fk_service_contract']);
            $table->dropForeign(['fk_point_of_sale']);
            $table->dropForeign(['fk_autor']);

        });

        Schema::connection('second_db')->dropIfExists('technical_conclusions');
    }
};

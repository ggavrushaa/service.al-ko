<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTechnicalConclusionsTable extends Migration
{
    public function up()
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {

            $table->unsignedBigInteger('warranty_claim_id')->nullable()->after('id');
            $table->foreign('warranty_claim_id')->references('id')->on('warranty_claims')->onDelete('cascade');

            $table->dropColumn([
                'code_1C', 'number', 'client_name', 'client_phone', 
                'product_article', 'factory_number', 'product_name', 'barcode', 
                'service_partner', 'service_contract', 'point_of_sale', 'autor', 
                'date_of_sale', 'date_of_claim'
            ]);
        });
    }

    public function down()
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            $table->dropForeign(['warranty_claim_id']);
            $table->dropColumn('warranty_claim_id');

            $table->string('code_1C');
            $table->string('number');
            $table->string('client_name', 200);
            $table->string('client_phone', 50);
            $table->string('product_article', 50);
            $table->string('factory_number', 50);
            $table->string('product_name', 200);
            $table->string('barcode');
            $table->unsignedBigInteger('service_partner');
            $table->unsignedBigInteger('service_contract');
            $table->unsignedBigInteger('point_of_sale');
            $table->unsignedBigInteger('autor');
            $table->dateTime('date_of_sale');
            $table->dateTime('date_of_claim');
        });
    }
}

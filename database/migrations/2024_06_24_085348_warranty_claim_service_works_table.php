<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warranty_claim_service_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warranty_claim_id');
            $table->unsignedBigInteger('service_work_id');
            $table->timestamps();

            $table->foreign('warranty_claim_id')->references('id')->on('warranty_claims')->onDelete('cascade');
            $table->foreign('service_work_id')->references('id')->on('service_works')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warranty_claim_service_works');
    }

};

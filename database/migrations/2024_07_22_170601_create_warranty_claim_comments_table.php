<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claim_comments', function (Blueprint $table) {
            $table->id()->from(1001);
            
            $table->bigInteger('warranty_claim_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->text('comment', 500);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claim_comments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('warranty_claim_files', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->foreignId('warranty_claim_id')->constrained('warranty_claims')->onDelete('cascade');
            $table->string('path');
            $table->string('filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claim_files');
    }
};

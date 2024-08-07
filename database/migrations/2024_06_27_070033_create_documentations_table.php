<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('name', 200);
            $table->foreignId('doc_type_id')->constrained('document_types')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('product_groups')->onDelete('cascade');
            $table->dateTime('added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};

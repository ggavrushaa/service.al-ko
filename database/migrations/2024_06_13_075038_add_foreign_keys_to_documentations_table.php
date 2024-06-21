<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->foreignId('doc_type_id')->constrained('document_types')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('product_groups')->onDelete('cascade');
            $table->dateTime('added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->dropForeign(['doc_type_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn('doc_type_id');
            $table->dropColumn('category_id');
            $table->dropColumn('added');
        });
    }
};

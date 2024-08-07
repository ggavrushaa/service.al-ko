<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            $table->dropForeign(['parent_doc']);
            $table->dropForeign(['product_group_id']);

            $table->dropColumn([
                'parent_doc', 'product_group_id', 
                'deteails_reason', 'details', 
                'sender_name', 'sender_phone', 'receipt_number',
                'comment', 'comment_service', 'comment_part',
                'file_paths', 'status', 'type_of_claim',
            ]);
        });
    }
    public function down(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            Schema::table('technical_conclusions', function (Blueprint $table) {
                $table->bigInteger('parent_doc')->unsigned()->nullable();
                $table->foreign('parent_doc')->references('id')->on('warranty_claims')->onDelete('cascade');
    
                $table->string('details', 500)->nullable();
                $table->string('deteails_reason', 500)->nullable();
                $table->string('sender_name', 255)->nullable();
                $table->string('sender_phone', 50)->nullable();
                $table->string('receipt_number', 50)->nullable();
                $table->string('comment_service', 500)->nullable();
                $table->string('comment_part', 500)->nullable();
                $table->text('file_paths')->nullable();
                $table->bigInteger('product_group_id')->unsigned()->nullable();
                $table->string('status', 255)->default('Новий');
    
                $table->foreign('product_group_id')->references('id')->on('product_groups')->onDelete('cascade');
            });
        });
    }
};

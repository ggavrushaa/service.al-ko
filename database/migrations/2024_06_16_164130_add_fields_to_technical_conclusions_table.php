<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            $table->string('sender_name', 255)->nullable();
            $table->string('sender_phone', 50)->nullable();
            $table->string('receipt_number', 50)->nullable();
            $table->string('comment', 500)->nullable();
            $table->unsignedBigInteger('product_group_id')->nullable();
            $table->text('file_paths')->nullable();
            $table->string('comment_service', 500)->nullable();
            $table->string('comment_part', 500)->nullable();

            $table->foreign('product_group_id')->references('id')->on('product_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('technical_conclusions', function (Blueprint $table) {
            $table->dropForeign(['product_group_id']);
            $table->dropColumn([
                'sender_name',
                'sender_phone',
                'receipt_number',
                'comment',
                'product_group_id',
                'file_paths',
                'comment_service',
                'comment_part'
            ]);
        });
    }
};

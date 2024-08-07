<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolution_templates', function (Blueprint $table) {

            $table->id()->from(1001);

            $table->string('code_1C', 40)->unique();
            $table->string('name', 200);

            $table->bigInteger('parent_id');
            $table->tinyInteger('is_folder');
            $table->tinyInteger('is_deleted');

            $table->timestamps();
            $table->text('description')->nullable();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolution_templates');
    }
};

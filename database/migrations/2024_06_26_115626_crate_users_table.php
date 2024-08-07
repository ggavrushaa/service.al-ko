<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('password', 64);
            $table->string('email', 512);
            $table->boolean('need_notification')->default(0);
            $table->string('first_name_ru', 255)->nullable();
            $table->string('first_name_en', 255)->nullable();
            $table->string('first_name', 255);
            $table->string('middle_name', 255);
            $table->string('last_name', 255);
            $table->string('company_name', 255);
            $table->string('phone', 32);
            $table->text('comment')->nullable();
            $table->string('country_code', 32)->default('ua');
            $table->string('last_login_ip', 32);
            $table->dateTime('last_login_time')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('added_time')->useCurrent();
            $table->dateTime('upd_time')->nullable();
            $table->boolean('was_updated')->default(0);
            $table->string('code1c', 32)->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->string('link', 255)->nullable();
            $table->integer('sub_dealer')->nullable()->default(0);
            $table->integer('role_id')->nullable();
            $table->string('hash', 64)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->integer('edo_role_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

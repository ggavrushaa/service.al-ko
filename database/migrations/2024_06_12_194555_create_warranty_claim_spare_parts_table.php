`<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claim_spareparts', function (Blueprint $table) {
            $table->id()->from(1001);

            $table->foreignId('warranty_claim_id')->constrained('warranty_claims')->onDelete('cascade');
            $table->bigInteger('spare_parts')->unsigned();

            $table->integer('line_number');
            $table->integer('qty')->comment('Quantity');

            $table->decimal('price_without_vat', 12, 2);
            $table->decimal('amount_without_vat', 12, 2);
            $table->decimal('amount_vat', 12, 2);
            $table->decimal('amount_with_vat', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('second_db')->dropIfExists('warranty_claim_spare_parts');
    }
};

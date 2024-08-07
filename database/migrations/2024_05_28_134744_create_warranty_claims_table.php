<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('second_db')->create('warranty_claims', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->string('code_1C')->unique()->nullable();
            $table->string('number')->unique();

            $table->string('product_article');
            $table->string('factory_number');
            $table->string('barcode');

            $table->string('client_name');
            $table->string('client_phone');
            $table->string('product_name');

            $table->integer('service_partner');
            $table->integer('service_contract');
            $table->integer('point_of_sale')->nullable();
            $table->integer('autor');

            $table->dateTime('date');
            $table->dateTime('date_of_sale');
            $table->dateTime('date_of_claim')->comment('Дата звернення в СЦ');

            $table->text('details');
            $table->string('type_of_claim')->default('Гарантійний ремонт');
            $table->boolean('is_deleted');

            $table->timestamps();
        });

        DB::connection('second_db')->statement('
        ALTER TABLE warranty_claims
        ADD CONSTRAINT fk_service_partner
        FOREIGN KEY (service_partner) REFERENCES alko_db.user_partners(id)
        ON DELETE CASCADE
        ');

        DB::connection('second_db')->statement('
        ALTER TABLE warranty_claims
        ADD CONSTRAINT fk_service_contract
        FOREIGN KEY (service_contract) REFERENCES alko_db.contracts(id)
        ON DELETE CASCADE
        ');

        DB::connection('second_db')->statement('
        ALTER TABLE warranty_claims
        ADD CONSTRAINT fk_point_of_sale
        FOREIGN KEY (point_of_sale) REFERENCES alko_db.user_partners(id)
        ON DELETE CASCADE
        ');

        DB::connection('second_db')->statement('
        ALTER TABLE warranty_claims
        ADD CONSTRAINT fk_autor
        FOREIGN KEY (autor) REFERENCES alko_db.users(id)
        ON DELETE CASCADE
        ');

    }

    public function down()
    {
        Schema::connection('second_db')->table('warranty_claims', function (Blueprint $table) {
            $table->dropForeign('fk_service_partner');
            $table->dropForeign('fk_service_contract');
        });

        Schema::connection('second_db')->dropIfExists('warranty_claims');
    }
};

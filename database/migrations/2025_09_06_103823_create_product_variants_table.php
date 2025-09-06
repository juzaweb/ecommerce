<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'product_variants',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('sku_code', 100)->nullable()->index();
                $table->string('barcode', 100)->nullable()->index();
                $table->decimal('price', 15, 2)->nullable();
                $table->decimal('compare_price', 15, 2)->nullable();

                //Type: [default, downloadable, virtual]
                $table->string('type', 20)->default('default');
                $table->uuid('product_id')->index();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            }
        );

        Schema::create(
            'product_variant_translations',
            function (Blueprint $table) {
                $table->id();
                $table->uuid('product_variant_id')->index();
                $table->string('locale')->index();

                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unique(['product_variant_id', 'locale']);
                $table->foreign('product_variant_id')
                    ->references('id')
                    ->on('product_variants')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_translations');
        Schema::dropIfExists('product_variants');
    }
};

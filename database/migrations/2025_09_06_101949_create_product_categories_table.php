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
            'product_categories',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestamps();
            }
        );

        Schema::create(
            'product_category_translations',
            function (Blueprint $table) {
                $table->id();
                $table->uuid('product_category_id')->index();
                $table->string('locale')->index();
                $table->string('name');
                $table->string('slug', 190)->unique();
                $table->longText('description')->nullable();

                $table->unique(['product_category_id', 'locale']);
                $table->foreign('product_category_id')
                    ->references('id')
                    ->on('product_categories')
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
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
    }
};

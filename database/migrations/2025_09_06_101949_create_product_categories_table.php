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
                $table->uuid('parent_id')->nullable()->index();
                $table->timestamps();

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('product_categories')
                    ->onDelete('cascade');
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
                $table->text('description')->nullable();
                $table->timestamps();

                $table->unique(['product_category_id', 'locale']);
                $table->foreign('product_category_id')
                    ->references('id')
                    ->on('product_categories')
                    ->onDelete('cascade');
            }
        );

        Schema::create(
            'product_category',
            function (Blueprint $table) {
                $table->uuid('product_id');
                $table->uuid('category_id');

                $table->primary(['product_id', 'category_id']);
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
                $table->foreign('category_id')
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
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
    }
};

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
            'products',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('status', 20)->default('draft')->index();
                $table->timestamps();
                $table->softDeletes();
            }
        );

        Schema::create(
            'product_translations',
            function (Blueprint $table) {
                $table->id();
                $table->uuid('product_id')->index();
                $table->string('locale')->index();
                $table->string('name');
                $table->string('slug', 190)->unique();
                $table->longText('content')->nullable();
                $table->timestamps();

                $table->unique(['product_id', 'locale']);
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
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
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
    }
};

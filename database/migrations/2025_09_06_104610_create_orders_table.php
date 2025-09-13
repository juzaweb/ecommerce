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
            'orders',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('code', 15)->unique();
                $table->string('name', 150);
                $table->string('phone', 50)->nullable();
                $table->string('email', 150)->nullable();
                $table->text('address')->nullable();
                $table->string('country_code', 15)->nullable();
                $table->integer('quantity');
                $table->decimal('total_price', 20, 2);
                $table->decimal('total', 20, 2);
                $table->decimal('discount', 20, 2)->default(0);
                $table->string('discount_codes', 150)->nullable();
                $table->string('discount_target_type', 50)->nullable();
                $table->unsignedBigInteger('payment_method_id')->nullable()->index();
                $table->string('payment_method_name', 250);
                $table->text('notes')->nullable();
                $table->boolean('other_address')->default(0);
                $table->string('payment_status', 10)
                    ->default('pending')->comment('pending');
                $table->string('delivery_status', 10)
                    ->default('pending')->comment('pending');
                $table->uuid('user_id')->index();
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            }
        );

        Schema::create(
            'order_items',
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->decimal('price', 15);
                $table->decimal('line_price', 15);
                $table->integer('quantity');
                $table->decimal('compare_price', 15)->nullable();
                $table->string('sku_code', 100)->nullable()->index();
                $table->string('barcode', 100)->nullable()->index();
                $table->uuid('order_id')->index();
                $table->uuid('product_id')->nullable()->index();
                $table->uuid('variant_id')->nullable()->index();
                $table->timestamps();

                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('set null');

                $table->foreign('variant_id')
                    ->references('id')
                    ->on('product_variants')
                    ->onDelete('set null');
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

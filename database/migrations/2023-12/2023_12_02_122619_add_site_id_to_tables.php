<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'carts',
            function (Blueprint $table) {
                $table->unsignedBigInteger('site_id')->default(0)->index();
                $table->dropUnique(['code']);
                $table->unique(['code', 'site_id']);
            }
        );

        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->unsignedBigInteger('site_id')->default(0)->index();
                $table->dropUnique(['code']);
                $table->unique(['code', 'site_id']);
            }
        );

        Schema::table(
            'product_variants',
            function (Blueprint $table) {
                $table->unsignedBigInteger('site_id')->default(0)->index();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'carts',
            function (Blueprint $table) {
                $table->dropUnique(['code', 'site_id']);
                $table->dropColumn(['site_id']);
            }
        );

        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropUnique(['code', 'site_id']);
                $table->dropColumn(['site_id']);
            }
        );

        Schema::table(
            'product_variants',
            function (Blueprint $table) {
                $table->dropColumn(['site_id']);
            }
        );
    }
};

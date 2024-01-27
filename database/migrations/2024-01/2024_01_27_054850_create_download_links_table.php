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
        Schema::create(
            'ecom_download_links',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid()->unique();
                $table->string('name');
                $table->string('url');
                $table->unsignedBigInteger('site_id')->default(0)->index();
                $table->timestamps();

                $table->foreignId('product_id')
                    ->constrained('posts')
                    ->onDelete('cascade');

                $table->foreignId('variant_id')
                    ->constrained('product_variants')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('ecom_download_links');
    }
};

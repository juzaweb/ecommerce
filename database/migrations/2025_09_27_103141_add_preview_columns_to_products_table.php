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
        Schema::table('products', function (Blueprint $table) {
            $table->string('preview_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('main_locale', 5)->index()->default('en');
            $table->boolean('download_after_order')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['preview_url', 'video_url']);
            $table->dropColumn(['main_locale']);
            $table->dropColumn(['download_after_order']);
        });
    }
};

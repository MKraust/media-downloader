<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteFavoriteMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('favorite_media');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('favorite_media', function (Blueprint $table) {
            $table->id();
            $table->string('url', 512)->unique();
            $table->string('tracker_id');
            $table->string('title', 512);
            $table->string('original_title', 512)->nullable();
            $table->string('image_url', 1024);
            $table->timestamps();
        });
    }
}

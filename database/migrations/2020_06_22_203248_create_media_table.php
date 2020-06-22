<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('favorite_media');
        Schema::create('media', function (Blueprint $table) {
            $table->string('id', 512)->primary()->unique();
            $table->string('url', 512);
            $table->string('tracker_id');
            $table->string('title', 512);
            $table->string('original_title', 512)->nullable();
            $table->string('poster', 1024);
            $table->string('series_count')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}

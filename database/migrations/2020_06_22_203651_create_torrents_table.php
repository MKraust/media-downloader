<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torrents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 512);
            $table->string('url', 512);
            $table->string('content_type');
            $table->string('voice_acting')->nullable();
            $table->string('quality')->nullable();
            $table->string('size')->nullable();
            $table->integer('size_int')->nullable();
            $table->integer('downloads')->nullable();
            $table->string('season')->nullable();
            $table->timestamps();

            $table->string('media_id', 512);
            $table->foreign('media_id')->references('id')->on('media')->cascadeOnDelete();

            $table->unique(['media_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('torrents');
    }
}

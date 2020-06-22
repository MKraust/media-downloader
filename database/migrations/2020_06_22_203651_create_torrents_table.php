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
            $table->string('url', 400);
            $table->string('content_type');
            $table->string('voice_acting')->nullable();
            $table->string('quality')->nullable();
            $table->string('size')->nullable();
            $table->unsignedBigInteger('size_int')->nullable();
            $table->unsignedBigInteger('downloads')->nullable();
            $table->string('season')->nullable();
            $table->timestamps();

            $table->string('media_id', 300);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('torrents');
        Schema::enableForeignKeyConstraints();
    }
}

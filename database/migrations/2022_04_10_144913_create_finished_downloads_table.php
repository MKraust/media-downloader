<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishedDownloadsTable extends Migration
{
    public function up()
    {
        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->dropColumn('is_finished');
        });

        Schema::create('finished_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torrent_id')->references('id')->on('torrents')->cascadeOnDelete();
            $table->dateTime('finished_at');
            $table->string('path', 1024);
        });
    }

    public function down()
    {
        Schema::dropIfExists('finished_downloads');

        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->boolean('is_finished')->default(false);
        });
    }
}

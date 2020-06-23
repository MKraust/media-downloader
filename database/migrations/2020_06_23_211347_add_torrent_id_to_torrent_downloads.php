<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTorrentIdToTorrentDownloads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->foreignId('torrent_id')->unique();
            $table->foreign('torrent_id')->references('id')->on('torrents')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('torrent_downloads', function (Blueprint $table) {
            $table->dropForeign('torrent_id');
            $table->dropColumn('torrent_id');
            $table->string('name', 1024);
        });
    }
}

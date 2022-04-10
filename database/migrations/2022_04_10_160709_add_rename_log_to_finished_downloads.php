<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenameLogToFinishedDownloads extends Migration
{
    public function up()
    {
        Schema::table('finished_downloads', function (Blueprint $table) {
            $table->json('meta');
        });
    }

    public function down()
    {
        Schema::table('finished_downloads', function (Blueprint $table) {
            $table->dropColumn('meta');
        });
    }
}

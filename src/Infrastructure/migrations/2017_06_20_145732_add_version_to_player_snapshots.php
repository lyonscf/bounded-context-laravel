<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersionToPlayerSnapshots extends Migration
{
    public function up()
    {
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->renameColumn('version', 'update_count');
        });
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->integer('version')->default(1);
        });
    }

    public function down()
    {
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->dropColumn('version');
        });
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->renameColumn('update_count', 'version');
        });
    }
}

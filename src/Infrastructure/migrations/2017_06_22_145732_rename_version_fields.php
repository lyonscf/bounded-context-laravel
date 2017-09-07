<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameVersionFields extends Migration
{
    public function up()
    {
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->dropColumn('update_count');
        });
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->integer('player_version')->default(1);
        });
    }

    public function down()
    {
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->integer('update_count')->default(1);
        });
        Schema::table('player_snapshots', function (Blueprint $table) {
            $table->dropColumn('player_version');
        });
    }
}

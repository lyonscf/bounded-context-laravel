<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlayerSnapshots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('version')->default(0);
            $table->string('last_id', 36)->default('00000000-0000-0000-0000-000000000000');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('player_snapshots');
    }
}

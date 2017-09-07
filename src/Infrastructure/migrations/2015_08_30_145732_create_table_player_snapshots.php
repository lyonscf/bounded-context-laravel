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
            $table->string('class_name', 256);
            $table->integer('version')->default(0);
            $table->string('last_id', 36)->default('00000000-0000-0000-0000-000000000000');
            $table->dateTime('occurred_at');
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

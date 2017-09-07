<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSnapshotsAggregateState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TABLE `snapshots_aggregate_state` (
                `aggregate_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                `aggregate_type` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
                `occurred_at` datetime NOT NULL,
                `version` int(11) NOT NULL DEFAULT '0',
                `state` text COLLATE utf8_unicode_ci NOT NULL,
                UNIQUE KEY `snapshots_aggregate_state_id_unique` (`aggregate_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('snapshots_aggregate_state');
    }
}

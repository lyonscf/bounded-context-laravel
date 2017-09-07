<?php namespace BoundedContext\Contracts\Schema;

use BoundedContext\Contracts\Core\Versionable;

interface Factory extends Versionable
{
    /**
     * Runs an upgraded Schema.
     *
     * @throws \Exception
     * @return void
     */

    public function schema();
}

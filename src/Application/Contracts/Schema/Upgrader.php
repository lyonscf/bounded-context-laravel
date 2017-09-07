<?php namespace BoundedContext\Contracts\Schema;

use BoundedContext\Contracts\Core\Versionable;
use EventSourced\ValueObject\ValueObject\Integer as Version;

interface Upgrader extends Versionable
{
    /**
     * Runs the latest version of the upgrader.
     *
     * @return Version
     */

    public function latest_version();

    /**
     * Runs the upgrader.
     *
     * @throws \Exception
     * @return void
     */

    public function run();

    /**
     * Returns the upgraded Schema.
     *
     * @return Schema
     */
    public function schema();
}

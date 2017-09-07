<?php namespace BoundedContext\Contracts\Core;

use EventSourced\ValueObject\ValueObject\Integer as Version;

interface Versionable
{
    /**
     * Gets the current version of the Versionable Object.
     *
     * @return Version
     */
    public function version();
}

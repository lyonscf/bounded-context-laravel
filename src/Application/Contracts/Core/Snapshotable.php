<?php namespace BoundedContext\Contracts\Core;

use BoundedContext\Contracts\Snapshot\Snapshot;

interface Snapshotable
{
    /**
     * Returns the current Snapshot of the Snapshotable object.
     *
     * @return Snapshot
     */

    public function snapshot();
}

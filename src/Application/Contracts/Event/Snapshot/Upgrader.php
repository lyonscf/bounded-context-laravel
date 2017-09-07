<?php namespace BoundedContext\Contracts\Event\Snapshot;

interface Upgrader {

    /**
     * Upgrades a snapshot, if required, to a newer version, and returns a Snapshot.
     *
     * @param Snapshot $snapshot
     * @return Snapshot
     */

    public function snapshot(Snapshot $snapshot);
}

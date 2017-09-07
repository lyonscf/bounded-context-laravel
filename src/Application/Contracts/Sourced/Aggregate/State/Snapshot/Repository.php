<?php namespace BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

interface Repository
{
    /**
     * Returns the Snapshot by an Identifier.
     *
     * @return Snapshot
     */

    public function ids(Identifier $aggregate_id, Identifier $aggregate_type);

    /**
     * Saves a Snapshot.
     *
     * @return void
     */

    public function save(Snapshot $snapshot);
}

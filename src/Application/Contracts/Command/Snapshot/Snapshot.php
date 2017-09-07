<?php namespace BoundedContext\Contracts\Command\Snapshot;

use BoundedContext\Contracts\Schema\Schema;
use BoundedContext\Contracts\Snapshot\Snapshot as SnapshotContract;
use EventSourced\ValueObject\Contracts\ValueObject\ValueObject;

interface Snapshot extends SnapshotContract
{
    /**
     * Gets the type id of the Event.
     *
     * @return ValueObject
     */
    public function type();
    
    /**
     * Gets the current Schema.
     *
     * @return Schema
     */
    public function schema();
}

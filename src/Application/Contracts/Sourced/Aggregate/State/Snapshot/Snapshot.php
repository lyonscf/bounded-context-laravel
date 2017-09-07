<?php namespace BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Schema\Schema;
use EventSourced\ValueObject\Contracts\ValueObject\ValueObject;

interface Snapshot extends \BoundedContext\Contracts\Snapshot\Snapshot
{
    /**
     * @return Identifier
     */
    public function aggregate_id();
    
    /**
     * @return ValueObject
     */
    public function aggregate_type();
    
    
    /**
     * Gets the schema for the current Snapshot.
     *
     * @return Schema
     */
    public function schema();
}

<?php namespace BoundedContext\Contracts\Command;

use BoundedContext\Contracts\Core\Identifiable;
use EventSourced\ValueObject\Contracts\ValueObject;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

interface Command extends Identifiable, ValueObject
{
    /**
     * @return Identifier
     */
    public function aggregate_id();
}

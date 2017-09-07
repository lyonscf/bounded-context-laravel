<?php namespace BoundedContext\Contracts\Sourced\Aggregate\Type;

use BoundedContext\Contracts\Command\Command;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

interface Factory
{
    /**
     * Returns the Type of an aggregate based on one of its commands
     *
     * @param Command $command
     * @return Identifier
     */
    public function command(Command $command);
    
    /**
     * Returns the Type of an aggregate based on its class path
     * 
     * @param string $aggregate_class
     * @return Identifier
     */
    public function aggregate_class($aggregate_class);
}

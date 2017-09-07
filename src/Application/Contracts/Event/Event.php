<?php namespace BoundedContext\Contracts\Event;

use BoundedContext\Contracts\Core\Identifiable;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\Contracts\ValueObject;

/**
 * An event that has been returned to the application layer
 * must have all these properties
 */
interface Event extends Identifiable, ValueObject
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
     * @return Identifier
     */
    public function command_id();
    
    /**
     * @return ValueObject
     */
    public function values();
}

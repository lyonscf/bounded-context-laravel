<?php namespace BoundedContext\Contracts\Sourced\Aggregate\State;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Core\Versionable;
use BoundedContext\Contracts\Event\DomainEvent;
use BoundedContext\Contracts\Projection\Queryable;

interface State extends Versionable
{
    /**
     * @return Identifier
     */
    public function aggregate_id();
    
    /**
     * @return Identifier
     */
    public function aggregate_type();
    
    /**
     * @return Queryable
     */
    public function queryable();

    /**
     * Applies an Event to the State.
     *
     * @param Event $event
     * @throws \Exception
     * @return void
     */
    public function apply(DomainEvent $event);
}

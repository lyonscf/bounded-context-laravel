<?php namespace BoundedContext\Laravel\Version;

use BoundedContext\Contracts\Event\DomainEvent;
use BoundedContext\Contracts\Command\Command;
use EventSourced\ValueObject\ValueObject\Integer;

class Factory implements \BoundedContext\Contracts\Version\Factory
{
    public function event(DomainEvent $event)
    {
        return $event->version();
    }

    public function command(Command $command)
    {
        return new Integer(1);
    }
}

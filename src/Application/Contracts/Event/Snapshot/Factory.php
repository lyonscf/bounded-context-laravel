<?php namespace BoundedContext\Contracts\Event\Snapshot;

use BoundedContext\Contracts\Event\Event;
use BoundedContext\Contracts\Schema\Schema;

interface Factory
{
    /**
     * Returns a new Snapshot from an Event.
     *
     * @param Event $event
     * @return Snapshot $snapshot
     */

    public function event(Event $event);
}

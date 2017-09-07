<?php namespace BoundedContext\Contracts\Event\Snapshot;

use BoundedContext\Contracts\Event\Event;

interface Transformer
{
    public function fromEvent(Event $event);

    public function toPopo($snapshot);

    public function fromPopo($popo);
}
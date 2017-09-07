<?php namespace BoundedContext\Laravel\Event\Snapshot;

use BoundedContext\Contracts\Event\Event;
use BoundedContext\Contracts\Event\Snapshot\Transformer as SnapshotTransformer;

class Factory implements \BoundedContext\Contracts\Event\Snapshot\Factory
{
    protected $snapshot_transformer;
    
    public function __construct(SnapshotTransformer $snapshot_transformer)
    {
        $this->snapshot_transformer = $snapshot_transformer;
    }

    public function event(Event $event)
    {
        return $this->snapshot_transformer->fromEvent($event);
    }
}

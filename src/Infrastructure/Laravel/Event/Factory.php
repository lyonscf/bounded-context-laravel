<?php namespace BoundedContext\Laravel\Event;

use BoundedContext\Contracts\Event\Snapshot\Snapshot;
use BoundedContext\Contracts\Event\Snapshot\Upgrader;
use EventSourced\ValueObject\Deserializer\Deserializer;
use BoundedContext\Map\Map;

class Factory implements \BoundedContext\Contracts\Event\Factory
{
    private $event_map;
    private $deserializer;
    private $snapshot_upgrader;

    public function __construct(
        Map $event_map,
        Deserializer $deserializer,
        Upgrader $snapshot_upgrader
    )
    {
        $this->event_map = $event_map;
        $this->deserializer = $deserializer;
        $this->snapshot_upgrader = $snapshot_upgrader;
    }

    public function snapshot(Snapshot $snapshot)
    {
        $upgraded_snapshot = $this->snapshot_upgrader->snapshot($snapshot);

        $event_class = $snapshot->type()->to_event_class();

        return $this->deserializer->deserialize(
            $event_class,
            $upgraded_snapshot->schema()->data_tree()
        );
    }
}

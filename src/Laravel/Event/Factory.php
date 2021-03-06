<?php namespace BoundedContext\Laravel\Event;

use BoundedContext\Contracts\Event\Snapshot\Snapshot;
use BoundedContext\Contracts\Event\Snapshot\Upgrader;
use BoundedContext\Laravel\Serializer\Deserializer;
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
        $snapshot = $this->snapshot_upgrader->snapshot($snapshot);

        $event_class = $this->event_map->get_class(
            $snapshot->type_id()
        );

        return $this->deserializer->deserialize(
            $event_class,
            $snapshot->schema()->serialize()
        );
    }
}

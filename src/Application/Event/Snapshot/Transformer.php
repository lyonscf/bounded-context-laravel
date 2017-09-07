<?php namespace BoundedContext\Event\Snapshot;

use BoundedContext\Contracts\Event\Event;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Generator\Identifier;
use BoundedContext\Contracts\Version\Factory as EventVersionFactory;
use BoundedContext\Event\Type as EventType;
use BoundedContext\Event\AggregateType;
use BoundedContext\Schema\Schema as ConcreteSchema;
use EventSourced\ValueObject\Serializer\Serializer;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;
use EventSourced\ValueObject\ValueObject\Uuid;
use EventSourced\ValueObject\ValueObject\DateTime;

class Transformer implements \BoundedContext\Contracts\Event\Snapshot\Transformer
{
    protected $identifier_generator;
    protected $datetime_generator;
    protected $event_version_factory;
    protected $map;
    protected $serializer;

    public function __construct(
        Identifier $identifier_generator,
        DateTimeGenerator $datetime_generator,
        EventVersionFactory $event_version_factory,
        Serializer $serializer
    )
    {
        $this->identifier_generator = $identifier_generator;
        $this->datetime_generator = $datetime_generator;
        $this->event_version_factory = $event_version_factory;
        $this->serializer = $serializer;
    }

    public function fromEvent(Event $event)
    {
        $serialized = $this->serializer->serialize($event->values());
        $domain_event = $event->values();
        $event_type = EventType::from_event($event->values());
        $aggregate_type = $event->aggregate_type();

        return new Snapshot(
            $event->id(),
            $this->event_version_factory->event($domain_event),
            $this->datetime_generator->now(),
            $event_type,
            $event->command_id(),
            $event->aggregate_id(),
            $aggregate_type,
            new ConcreteSchema($serialized)
        );
    }

    public function toPopo($snapshot)
    {
        return (object)[
            'id' => $snapshot->id()->value(),
            'type' => $snapshot->type()->value(),
            'aggregate_id' => $snapshot->aggregate_id()->value(),
            'aggregate_type' => $snapshot->aggregate_type()->value(),
            'command_id' => $snapshot->command_id()->value(),
            'version' => $snapshot->version()->value(),
            'occurred_at' => $snapshot->occurred_at()->value(),
            'event' => $snapshot->schema()->data_tree()
        ];
    }

    public function fromPopo($popo)
    {
        return new Snapshot(
            new Uuid($popo->id),
            new Integer_($popo->version),
            new DateTime($popo->occurred_at),
            new EventType($popo->type),
            new Uuid($popo->command_id),
            new Uuid($popo->aggregate_id),
            new AggregateType($popo->aggregate_type),
            new ConcreteSchema((array)$popo->event)
        );
    }
}
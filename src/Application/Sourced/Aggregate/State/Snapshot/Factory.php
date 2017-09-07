<?php namespace BoundedContext\Sourced\Aggregate\State\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Contracts\Sourced\Aggregate\State\State;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use EventSourced\ValueObject\Contracts\Serializer;
use BoundedContext\Schema\Schema;
use EventSourced\ValueObject\ValueObject\Integer;
use BoundedContext\Event\AggregateType;

class Factory implements \BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Factory
{
    private $identifier_generator;
    private $datetime_generator;
    private $serializer;

    public function __construct(
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator,
        Serializer $serializer
    )
    {
        $this->identifier_generator = $identifier_generator;
        $this->datetime_generator = $datetime_generator;
        $this->serializer = $serializer;
    }

    public function create(Identifier $aggregate_id, Identifier $aggregate_type)
    {
        return new Snapshot(
            $aggregate_id,
            $aggregate_type,
            new Integer(0),
            $this->datetime_generator->now(),
            new Schema()
        );
    }

    public function tree(array $tree)
    {
        return new Snapshot(
            $this->identifier_generator->string($tree['aggregate_id']),
            new AggregateType($tree['aggregate_type']),
            new Integer($tree['version']),
            $this->datetime_generator->string($tree['occurred_at']),
            new Schema($tree['state'])
        );
    }

    public function state(State $state)
    {
        $serialized = $this->serializer->serialize($state->queryable());
        
        return new Snapshot(
            $state->aggregate_id(),
            $state->aggregate_type(),
            $state->version(),
            $this->datetime_generator->now(),
            new Schema($serialized)
        );
    }
}

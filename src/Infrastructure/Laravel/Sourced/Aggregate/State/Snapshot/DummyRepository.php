<?php namespace BoundedContext\Laravel\Sourced\Aggregate\State\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot;

/**
 * Dummy implementation, created to make aggregates snapshots easier to disable
 */
class DummyRepository implements \BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Repository
{
    protected $state_snapshot_factory;

    public function __construct(Snapshot\Factory $state_snapshot_factory)
    {
        $this->state_snapshot_factory = $state_snapshot_factory;
    }

    public function ids(Identifier $aggregate_id, Identifier $aggregate_type)
    {
        return $this->state_snapshot_factory->create($aggregate_id, $aggregate_type);
    }

    public function save(Snapshot\Snapshot $snapshot)
    {

    }
}

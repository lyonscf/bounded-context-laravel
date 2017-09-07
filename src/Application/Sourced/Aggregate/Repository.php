<?php namespace BoundedContext\Sourced\Aggregate;

use BoundedContext\Contracts\Command\Command;

use BoundedContext\Contracts\Event\Factory as EventFactory;
use BoundedContext\Contracts\Sourced\Log;
use BoundedContext\Contracts\Sourced\Log\Event as EventLog;

use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;
use BoundedContext\Contracts\Sourced\Aggregate\Factory as AggregateFactory;
use BoundedContext\Contracts\Sourced\Aggregate\Type\Factory as AggregateTypeFactory;
use BoundedContext\Contracts\Sourced\Aggregate\Stream\Builder as AggregateStreamBuilder;

use BoundedContext\Contracts\Sourced\Aggregate\State\Factory as StateFactory;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Factory as StateSnapshotFactory;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Repository as StateSnapshotRepository;
use EventSourced\ValueObject\ValueObject\Uuid;

class Repository implements \BoundedContext\Contracts\Sourced\Aggregate\Repository
{
    private $state_snapshot_repository;
    private $state_snapshot_factory;
    private $state_factory;

    private $aggregate_factory;
    private $aggregate_type_factory;
    private $aggregate_stream_builder;

    private $event_factory;
    private $event_log;
    
    const SNAPSHOT_AT = 1000;

    public function __construct(
        StateSnapshotRepository $state_snapshot_repository,
        StateSnapshotFactory $state_snapshot_factory,
        StateFactory $state_factory,
        AggregateFactory $aggregate_factory,
        AggregateTypeFactory $aggregate_type_factory,
        AggregateStreamBuilder $aggregate_stream_builder,
        EventFactory $event_factory,
        EventLog $event_log
    )
    {
        $this->state_snapshot_repository = $state_snapshot_repository;
        $this->state_snapshot_factory = $state_snapshot_factory;
        $this->state_factory = $state_factory;

        $this->aggregate_factory = $aggregate_factory;
        $this->aggregate_type_factory = $aggregate_type_factory;

        $this->aggregate_stream_builder = $aggregate_stream_builder;

        $this->event_factory = $event_factory;
        $this->event_log = $event_log;
    }

    public function by(Command $command)
    {
        $state = $this->state_factory
            ->with($command)
            ->snapshot( $this->snapshot($command) );

        $event_snapshot_stream = $this->aggregate_stream_builder
            ->ids($state->aggregate_id(), $state->aggregate_type())
            ->after($state->version())
            ->stream();

        foreach ($event_snapshot_stream as $event_snapshot) {
            $event = $this->event_factory->snapshot($event_snapshot);
            $state->apply($event);
        }

        return $this->aggregate_factory->state($state);
    }

    public function fetch($aggregate_class, Uuid $id)
    {
        $state_snapshot = $this->state_snapshot_repository->ids(
            $id,
            $this->aggregate_type_factory->aggregate_class($aggregate_class)
        );

        $state = $this->state_factory
            ->aggregateClass($aggregate_class)
            ->snapshot( $state_snapshot );

        $event_snapshot_stream = $this->aggregate_stream_builder
            ->ids($state->aggregate_id(), $state->aggregate_type())
            ->after($state->version())
            ->stream();

        foreach ($event_snapshot_stream as $event_snapshot) {
            $event = $this->event_factory->snapshot($event_snapshot);
            $state->apply($event);
        }

        return $this->aggregate_factory->state($state);
    }
    
    private function snapshot(Command $command)
    {
        return $this->state_snapshot_repository->ids(
            $command->aggregate_id(),
            $this->aggregate_type_factory->command($command)
        );
    }

    public function save(Aggregate $aggregate)
    {
        if ($this->should_snapshot($aggregate)) {
            $state_snapshot = $this->state_snapshot_factory->state($aggregate->state());
            $this->state_snapshot_repository->save($state_snapshot);
        }
        $this->event_log->append_aggregate_events($aggregate);
        $aggregate->flush();
    }
    
    private function should_snapshot(Aggregate $aggregate)
    {
        $new_version = $aggregate->state()->version();
        $old_version = $new_version->subtract($aggregate->changes()->count());
        
        return (floor($old_version->value()/self::SNAPSHOT_AT) < floor($new_version->value()/self::SNAPSHOT_AT));
    }

    /**
     * @return Log\Event
     */
    public function event_log()
    {
        return $this->event_log;
    }
}

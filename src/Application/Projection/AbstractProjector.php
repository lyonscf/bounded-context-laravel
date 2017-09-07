<?php namespace BoundedContext\Projection;

use BoundedContext\Contracts\Player\Snapshot\Snapshot;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Event\Factory as EventFactory;
use BoundedContext\Contracts\Event\Snapshot\Snapshot as EventSnapshot;
use BoundedContext\Contracts\Sourced\Log\Event as EventLog;
use BoundedContext\Contracts\Projection\Projection;
use BoundedContext\Player\AbstractPlayer;

abstract class AbstractProjector extends AbstractPlayer implements \BoundedContext\Contracts\Projection\Projector
{
    protected $projection;

    public function __construct(
        Projection $projection,
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator,
        EventLog $log,
        Snapshot $snapshot
    )
    {
        parent::__construct(
            $identifier_generator,
            $datetime_generator,
            $log,
            $snapshot
        );

        $this->projection = $projection;
    }

    public function reset()
    {
        parent::reset();

        $this->projection->reset();
    }

    protected function mutate(EventSnapshot $snapshot)
    {
        $handler_name = $this->get_handler_name($snapshot);
    
        $this->$handler_name(
            $this->projection,
            $snapshot->schema(),
            $snapshot
        );
    }

    public function projection()
    {
        return $this->projection;
    }

    public function queryable()
    {
        return $this->projection->queryable();
    }
}

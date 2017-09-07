<?php namespace BoundedContext\Workflow;

use BoundedContext\Contracts\Event\Event;
use BoundedContext\Contracts\Event\Factory as EventFactory;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Bus\Dispatcher;
use BoundedContext\Contracts\Sourced\Log\Event as EventLog;
use BoundedContext\Contracts\Player\Snapshot\Snapshot;

use BoundedContext\Player\AbstractPlayer;

class AbstractWorkflow extends AbstractPlayer
{
    protected $bus;

    public function __construct(
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator,
        EventFactory $event_factory,
        EventLog $log,
        Dispatcher $bus,
        Snapshot $snapshot
    )
    {
        parent::__construct(
            $identifier_generator,
            $datetime_generator,
            $event_factory,
            $log,
            $snapshot
        );

        $this->bus = $bus;
    }

    protected function mutate(Event $event, Snapshot $snapshot)
    {
        $handler = $this->get_handler_name($event);

        $this->$handler(
            $this->bus,
            $event,
            $snapshot
        );
    }
}

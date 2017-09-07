<?php namespace BoundedContext\Contracts\Sourced\Aggregate;

use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Sourced\Log;
use EventSourced\ValueObject\ValueObject\Uuid;

interface Repository {

    /**
     * Returns an Aggregate for a Command.
     *
     * @param Command $command
     * @return Aggregate
     */
    public function by(Command $command);

    /**
     * Returns an Aggregate for a Command.
     *
     * @param string $aggregate_class
     * @param Uuid $id
     * @return Aggregate
     */
    public function fetch($aggregate_class, Uuid $id);

    /**
     * Saves an Aggregate to the Repository.
     *
     * @param Aggregate $aggregate
     * @return void
     */
    public function save(Aggregate $aggregate);

    /**
     * @return Log\Event
     */
    public function event_log();
}

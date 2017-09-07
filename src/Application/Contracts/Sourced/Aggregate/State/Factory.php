<?php namespace BoundedContext\Contracts\Sourced\Aggregate\State;

use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Snapshot;

interface Factory
{
    /**
     * Decorator.
     * Sets the Command associated with the State. Must be called before `snapshot`.
     *
     * @param Command $command
     * @return Factory
     */
    public function with(Command $command);

    /**
     * Sets the aggregate class associated with the State. Must be called before `snapshot`.
     *
     * @param string $class
     * @return Factory
     */
    public function aggregateClass($class);

    /**
     * Returns a State from a Snapshot.
     *
     * @param Snapshot $snapshot
     * @return State
     */
    public function snapshot(Snapshot $snapshot);
}

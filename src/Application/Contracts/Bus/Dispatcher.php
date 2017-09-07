<?php namespace BoundedContext\Contracts\Bus;

use BoundedContext\Contracts\Collection\Collection;
use BoundedContext\Contracts\Command\Command;

interface Dispatcher
{
    /**
     * Dispatches a Command to the bus.
     *
     * @param Command $command
     */
    public function dispatch(Command $command);

    /**
     * Dispatches a Collection of Commands to the bus.
     *
     * @param Collection $commands
     */
    public function dispatch_collection(Collection $commands);
}

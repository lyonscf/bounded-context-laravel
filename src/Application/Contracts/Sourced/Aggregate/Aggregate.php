<?php namespace BoundedContext\Contracts\Sourced\Aggregate;

use BoundedContext\Contracts\Collection\Collection;
use BoundedContext\Contracts\Command\Handler;
use BoundedContext\Contracts\Sourced\Aggregate\State\State;

interface Aggregate extends Handler
{
    /**
     * Gets the Collection of Events applied to the Aggregate.
     *
     * @return Collection
     */
    public function changes();

    /**
     * Flush any changes applied to the Aggregate.
     *
     * @return void
     */
    public function flush();

    /**
     * Get the current state of the Aggregate.
     *
     * @return State
     */
    public function state();
}

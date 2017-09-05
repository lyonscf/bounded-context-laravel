<?php namespace BoundedContext\Contracts\Sourced\Log;

use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;
use BoundedContext\Contracts\Sourced\Stream\Builder;
use BoundedContext\Contracts\Core\Resetable;

interface Event extends Resetable
{
    /**
     * Appends a Collection of Events to the end of the Log.
     *
     * @param Aggregate $aggregate
     * @return void
     */
    public function append_aggregate_events(Aggregate $aggregate);
    
    /**
     * Returns a new Stream Builder for the Log.
     *
     * @return Builder
     */
    public function builder();

    /**
     * Return the dto events that were appended via this logs instance
     *
     * @return stdClass[]
     */
    public function get_appended_events();

    /**
     * Returns latest event from event log
     *
     * @return mixed
     */
    public function get_last_event();
}

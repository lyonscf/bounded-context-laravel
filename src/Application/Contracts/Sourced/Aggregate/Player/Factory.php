<?php namespace BoundedContext\Contracts\Sourced\Aggregate\Player;

use BoundedContext\Contracts\Player\Player;
use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;

interface Factory
{
    /**
     * Returns the Player for an Aggregate.
     *
     * @param Aggregate $aggregate
     * @return Player
     */

    public function aggregate(Aggregate $aggregate);
}

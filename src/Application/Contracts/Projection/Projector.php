<?php namespace BoundedContext\Contracts\Projection;

use BoundedContext\Contracts\Player\Player;

interface Projector extends Player
{
    /**
     * Returns the current Projection used by the Projector.
     *
     * @return Projection
     */

    public function projection();

    /**
     * Returns the current Queryable used by the Projector.
     *
     * @return Queryable
     */

    public function queryable();
}

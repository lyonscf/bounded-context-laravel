<?php namespace BoundedContext\Contracts\Projection;

use BoundedContext\Contracts\Core\Resetable;

interface Projection extends Resetable
{
    /**
     * Returns the current Queryable used by the Projector.
     *
     * @return Queryable
     */

    public function queryable();
}
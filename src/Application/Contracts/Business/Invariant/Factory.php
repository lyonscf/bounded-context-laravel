<?php namespace BoundedContext\Contracts\Business\Invariant;


use BoundedContext\Contracts\Projection\Queryable;

interface Factory
{
    /**
     * Sets a specific Queryable to be used with the Invariant.
     *
     * @return Factory
     */

    public function with(Queryable $queryable);

    /**
     * Returns an instance of an Invariant.
     *
     * @throws Exception
     * @return Invariant
     */

    public function by_class($class);
}

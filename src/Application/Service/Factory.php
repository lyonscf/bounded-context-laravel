<?php namespace BoundedContext\Contracts\Service;

interface Factory
{
    /**
     * Returns an instance of an Invariant.
     *
     * @throws Exception
     * @return Service
     */

    public function by_class($class, $params = []);
}

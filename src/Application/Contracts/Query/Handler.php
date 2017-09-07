<?php namespace BoundedContext\Contracts\Query;

interface Handler
{
    /**
     * Handles a Query and returns a Collection of results.
     *
     * @param Query $query
     * @throws \Exception
     * @return Result
     */

    public function handle(Query $query);
}

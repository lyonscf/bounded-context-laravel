<?php namespace BoundedContext\Contracts\Query;

use BoundedContext\Contracts\Command\Command;
use EventSourced\ValueObject\Contracts\ValueObject\Key;
use EventSourced\ValueObject\ValueObject\Integer;

interface Query extends Command
{
    /**
     * Sets a limit on the number of results.
     *
     * @param Integer $limit
     * @return Query
     */

    public function limit(Integer $limit);

    /**
     * Returns the Collection of Results.
     *
     * @param Integer $page
     * @return Query
     */

    public function order(Key $key);

    /**
     * Sets the result to be ordered descending.
     *
     * @return Query
     */

    public function descending();
}

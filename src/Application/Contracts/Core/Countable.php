<?php namespace BoundedContext\Contracts\Core;

use EventSourced\ValueObject\ValueObject\Integer;

interface Countable
{
    /**
     * Returns the number of elements in the Countable object.
     *
     * @return Integer
     */

    public function count();
}

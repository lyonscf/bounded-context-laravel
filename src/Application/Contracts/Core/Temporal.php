<?php namespace BoundedContext\Contracts\Core;

use EventSourced\ValueObject\Contracts\ValueObject\DateTime;

interface Temporal
{
    /**
     * Get the current DateTime of the Temporal object.
     *
     * @return DateTime
     */

    public function occurred_at();
}

<?php namespace BoundedContext\Contracts\Generator;

use EventSourced\ValueObject\Contracts\ValueObject\DateTime as DateTimeVO;

interface DateTime extends ValueObject
{
    /**
     * Generates a new DateTime at the current time.
     *
     * @return DateTimeVO
     */
    public function now();

    /**
     * Generates a new DateTime from a string.
     *
     * @param string $date_time
     * @return DateTimeVO
     */
    public function string($date_time);
}

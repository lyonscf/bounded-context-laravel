<?php namespace BoundedContext\Contracts\Core;

use BoundedContext\Contracts\Collection\Collection;

interface Collector
{
    /**
     * Returns the collection of the Collector object.
     *
     * @return Collection
     */

    public function collection();
}

<?php namespace BoundedContext\Contracts\Query;

use BoundedContext\Collection\Collection;

interface Result
{
    /**
     * Returns the Collection of Results.
     *
     * @return Collection
     */

    public function collection();

    /**
     * Returns the number of Results.
     *
     * @return Integer
     */

    public function count();

    /**
     * Returns the current page number.
     *
     * @return Integer
     */

    public function page();
}

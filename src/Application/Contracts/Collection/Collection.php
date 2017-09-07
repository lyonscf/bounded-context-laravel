<?php namespace BoundedContext\Contracts\Collection;

use Iterator;
use BoundedContext\Contracts\Core\Countable;
use BoundedContext\Contracts\Core\Resetable;

interface Collection extends Iterator, Resetable, Countable
{
    /**
     * Returns whether or not the Collection is empty.
     *
     * @return boolean
     */

    public function is_empty();

    /**
     * Appends a Collectable object to the end of the current Collection.
     *
     * @param Collectable $collectable
     * @return void
     */

    public function append($collectable);

    /**
     * Appends a Collection to the end of the current Collection.
     *
     * @param Collection $collection
     * @return void
     */
    public function append_collection(Collection $collection);

}

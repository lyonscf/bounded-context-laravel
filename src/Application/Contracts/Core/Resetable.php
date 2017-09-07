<?php namespace BoundedContext\Contracts\Core;

use BoundedContext\Contracts\Generator;

interface Resetable
{
    /**
     * Resets the state of the Resettable object.
     *
     * @return void
     */

    public function reset();
}
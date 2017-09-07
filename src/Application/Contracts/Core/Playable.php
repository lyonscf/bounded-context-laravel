<?php namespace BoundedContext\Contracts\Core;

interface Playable
{
    /**
     * Plays the Playable object.
     *
     * @param int $limit
     * @return void
     */

    public function play($limit = 1000);
}

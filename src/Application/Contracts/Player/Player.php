<?php namespace BoundedContext\Contracts\Player;

use BoundedContext\Contracts\Core\Playable;
use BoundedContext\Contracts\Core\Resetable;
use BoundedContext\Player\Snapshot\Snapshot;

interface Player extends Resetable, Playable
{
    /**
     * Returns the current Snapshot of the Player.
     *
     * @return Snapshot
     */

    public function snapshot();

    /**
     * @return int
     */
    public static function version();
}

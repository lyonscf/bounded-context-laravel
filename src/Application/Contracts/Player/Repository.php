<?php namespace BoundedContext\Contracts\Player;

use BoundedContext\Player\Snapshot\ClassName;

interface Repository
{
    /**
     * @param ClassName $class_name
     * @return Player
     */
    public function get(ClassName $class_name);

    /**
     * @param Player $player
     * @return void
     */
    public function save(Player $player);

    public function hasVersionChanged(ClassName $class_name);
}

<?php namespace BoundedContext\Contracts\Player\Snapshot;

use BoundedContext\Player\Snapshot\ClassName;

interface Repository
{
    /**
     * @param ClassName $class_name
     * @return Snapshot
     */
    public function get(ClassName $class_name);

    /**
     * @param Snapshot $snapshot
     * @return void
     */
    public function create(Snapshot $player);

    /**
     * @param Snapshot $snapshot
     * @return void
     */
    public function save(Snapshot $player);
}

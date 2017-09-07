<?php namespace BoundedContext\Sourced\Stream;

class DefaultUpgrader implements Upgrader
{
    public function upgrade($popo_snapshot): array
    {
        return [$popo_snapshot];
    }
}
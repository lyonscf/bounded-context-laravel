<?php namespace BoundedContext\Sourced\Stream;

interface Upgrader
{
    public function upgrade($popo_snapshot): array;
}
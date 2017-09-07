<?php namespace BoundedContext\Sourced\Aggregate\State;

use BoundedContext\Contracts\Projection\Projection;
use EventSourced\ValueObject\ValueObject\Type\AbstractComposite;

abstract class AbstractProjection extends AbstractComposite implements Projection
{
    protected $root_entity;
        
    public function reset()
    {
        throw new \Exception("Resetting a State Projection is not supported in this version.");
    }

    public function queryable()
    {
        return $this->root_entity;
    }
}

<?php namespace BoundedContext\Sourced\Aggregate;

use BoundedContext\Contracts\Business\Invariant\Factory;
use BoundedContext\Contracts\Sourced\Aggregate\State\State;

class Check
{
    protected $invariant_factory;
    protected $state;

    public function __construct(Factory $invariant_factory, State $state)
    {
        $this->invariant_factory = $invariant_factory;
        $this->state = $state;
    }

    public function that($class)
    {
        return $this->invariant_factory
            ->with($this->state->queryable())
            ->by_class($class);
    }
}

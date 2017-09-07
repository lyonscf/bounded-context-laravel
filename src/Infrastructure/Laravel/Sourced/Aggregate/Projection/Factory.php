<?php namespace BoundedContext\Laravel\Sourced\Aggregate\Projection;

class Factory implements \BoundedContext\Sourced\Aggregate\Projection\Factory
{
    public function fromInterface($projection_interface)
    {
        return app($projection_interface);
    }
}
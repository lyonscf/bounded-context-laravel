<?php namespace BoundedContext\Sourced\Aggregate\Projection;

interface Factory
{
    public function fromInterface($projection_interface);
}
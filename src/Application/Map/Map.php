<?php

namespace BoundedContext\Map;

use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

class Map
{
    private $id_map;
    private $class_map;
    private $generator;

    public function __construct(array $class_map = [], IdentifierGenerator $generator)
    {
        foreach($class_map as $id => $class)
        {
            $this->id_map[$id] = $class;
            $this->class_map[$class] = $id;
        }

        $this->generator = $generator;
    }

    public function get_class(Identifier $id)
    {
        return $this->id_map[$id->value()];
    }

    public function get_id($class)
    {
        return $this->generator->string(
            $this->class_map[get_class($class)]
        );
    }
}
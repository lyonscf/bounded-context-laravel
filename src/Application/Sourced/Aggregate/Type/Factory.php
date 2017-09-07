<?php namespace BoundedContext\Sourced\Aggregate\Type;

use BoundedContext\Contracts\Command\Command;
use BoundedContext\Sourced\Aggregate\ClassFactory;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Event\AggregateType;

class Factory implements \BoundedContext\Contracts\Sourced\Aggregate\Type\Factory
{
    private $class_factory;
    private $identifier_generator;
    
    public function __construct(
        ClassFactory $class_factory, 
        IdentifierGenerator $identifier_generator)
    {
        $this->class_factory = $class_factory;
        $this->identifier_generator = $identifier_generator;
    }
    
    /**
     * @param Command $command
     * @return Identifier
     */
    public function command(Command $command)
    {
        $aggregate_class = $this->class_factory->command($command);
        return $this->aggregate_class($aggregate_class);
    }

    /**
     * @param string $aggregate_class
     * @return AggregateType
     * @throws \Exception
     */
    public function aggregate_class($aggregate_class) 
    {
        return AggregateType::from_class_string($aggregate_class);
    }
}

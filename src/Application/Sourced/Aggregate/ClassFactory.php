<?php namespace BoundedContext\Sourced\Aggregate;

use BoundedContext\Contracts\Sourced\Aggregate\State\State;
use BoundedContext\Contracts\Command\Command;

class ClassFactory 
{    
    public function state(State $state)
    {
        return $this->class_name($state, "State");
    }
    
    public function command(Command $command)
    {
        return $this->class_name($command, "Command");
    }
    
    private function class_name($object, $object_name)
    {
        $state_class = get_class($object);

        $state_prefix = substr(
            $state_class,
            0,
            strpos($state_class, "$object_name")
        );

        return $state_prefix . "Aggregate";
    }
}

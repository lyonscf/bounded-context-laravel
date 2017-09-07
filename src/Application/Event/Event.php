<?php namespace BoundedContext\Event;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Type\AbstractComposite;
use EventSourced\ValueObject\ValueObject\Type\AbstractEntity;

class Event extends AbstractEntity implements \BoundedContext\Contracts\Event\Event
{
    protected $aggregate_id;
    protected $command_id;
    protected $aggregate_type;
    protected $values;
        
    public function __construct(
        Identifier $id, 
        Identifier $command_id,
        AggregateType $aggregate_type,
        Identifier $aggregate_id, 
        AbstractComposite $values)
    {
        parent::__construct($id);
        $this->aggregate_id = $aggregate_id;
        $this->aggregate_type = $aggregate_type;
        $this->command_id = $command_id;
        $this->values = $values;
    }
    
    public function aggregate_id()
    {
        return $this->aggregate_id;
    }
    
    public function command_id()
    {
        return $this->command_id;
    }

    public function values()
    {
        return $this->values;
    }

    public function aggregate_type()
    {
        return $this->aggregate_type;
    }
}

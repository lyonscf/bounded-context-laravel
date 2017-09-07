<?php namespace BoundedContext\Command;

use BoundedContext\Contracts\Command\Command;
use EventSourced\ValueObject\ValueObject\Type\AbstractEntity;
use EventSourced\ValueObject\ValueObject\Uuid;

class AbstractCommand extends AbstractEntity implements Command
{
    protected $aggregate_id;
    
    public function __construct(Uuid $id, Uuid $aggregate_id)
    {
        $this->aggregate_id = $aggregate_id;
        parent::__construct($id);
    }
    
    public function aggregate_type()
    {
        return $this->id;
    }
    
    public function aggregate_id()
    {
        return $this->aggregate_id;
    }
}

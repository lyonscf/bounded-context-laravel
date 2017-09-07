<?php namespace BoundedContext\Event\Snapshot;

use BoundedContext\Contracts\Schema\Schema;
use EventSourced\ValueObject\Contracts\ValueObject\DateTime;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Snapshot\AbstractSnapshot;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;
use BoundedContext\Event\Type as EventType;

class Snapshot extends AbstractSnapshot implements \BoundedContext\Contracts\Event\Snapshot\Snapshot
{
    protected $id;
    protected $type;
    protected $command_id;
    protected $aggregate_id;
    protected $aggregate_type;
    protected $event;

    public function __construct(
        Identifier $id,
        Integer_ $version,
        DateTime $occurred_at,
        EventType $type,
        Identifier $command_id,
        Identifier $aggregate_id,
        Identifier $aggregate_type,
        Schema $event
    )
    {
        parent::__construct($version, $occurred_at);
        $this->id = $id;
        $this->type = $type;
        $this->command_id = $command_id;
        $this->aggregate_id =  $aggregate_id;
        $this->aggregate_type = $aggregate_type;
        $this->event = $event;
    }
    
    public function id()
    {
        return $this->id;
    }

    public function aggregate_id()
    {
        return $this->aggregate_id;
    }

    public function type()
    {
        return $this->type;
    }

    public function aggregate_type()
    {
        return $this->aggregate_type;
    }
    
    public function command_id()
    {
        return $this->command_id;
    }

    public function schema()
    {
        return $this->event;
    }
}

<?php namespace BoundedContext\Command\Snapshot;

use BoundedContext\Contracts\Schema\Schema;
use EventSourced\ValueObject\Contracts\ValueObject\DateTime;
use BoundedContext\Snapshot\AbstractSnapshot;
use BoundedContext\Event\Type;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

class Snapshot extends AbstractSnapshot implements \BoundedContext\Contracts\Command\Snapshot\Snapshot
{
    protected $type_id;
    protected $type;
    protected $aggregate_type;
    protected $event;

    public function __construct(
        Integer_ $version,
        DateTime $occurred_at,
        Type $type,
        Schema $event
    )
    {
        parent::__construct($version, $occurred_at);
        $this->type = $type;
        $this->event = $event;
    }

    public function type()
    {
        return $this->type;
    }

    public function schema()
    {
        return $this->event;
    }
}

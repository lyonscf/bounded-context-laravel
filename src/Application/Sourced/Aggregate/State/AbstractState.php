<?php namespace BoundedContext\Sourced\Aggregate\State;

use BoundedContext\Contracts\Event\DomainEvent;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Projection\Projection;
use BoundedContext\Contracts\Sourced\Aggregate\State\State;
use BoundedContext\Event\Applying;
use EventSourced\ValueObject\ValueObject\Type\AbstractComposite;
use EventSourced\ValueObject\ValueObject\Integer as Version;

abstract class AbstractState extends AbstractComposite implements State
{
    use Applying;

    protected $aggregate_id;
    protected $aggregate_type;

    public function __construct(
        Identifier $aggregate_id,
        Identifier $aggregate_type,
        Version $version,
        Projection $projection
    )
    {
        $this->aggregate_id = $aggregate_id;
        $this->aggregate_type = $aggregate_type;
        $this->version = $version;
        $this->projection = $projection;
    }

    public function aggregate_id()
    {
        return $this->aggregate_id;
    }
    
    public function aggregate_type()
    {
        return $this->aggregate_type;
    }

    public function version()
    {
        return $this->version;
    }

    public function apply(DomainEvent $event)
    {
        $this->mutate($event);
    }

    public function queryable()
    {
        return $this->projection;
    }
}

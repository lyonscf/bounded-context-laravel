<?php namespace BoundedContext\Sourced\Aggregate;

use BoundedContext\Contracts\Business\Invariant\Factory;
use BoundedContext\Contracts\Sourced\Aggregate\Type\Factory as TypeFactory;
use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Generator;
use BoundedContext\Contracts\Sourced\Aggregate\State\State;
use BoundedContext\Command\Handling;
use BoundedContext\Collection\Collection;
use BoundedContext\Event\Event;
use EventSourced\ValueObject\ValueObject\Uuid;

abstract class AbstractAggregate implements \BoundedContext\Contracts\Sourced\Aggregate\Aggregate
{
    use Handling;

    protected $current_command;
    protected $state;
    protected $changes;

    protected $type;
    protected $check;
    protected $id_generator;
    protected $projection_factory;

    public function __construct(
        TypeFactory $type_factory,
        Factory $invariant_factory, 
        Generator\Identifier $id_generator,
        Projection\Factory $projection_factory,
        State $state
    )
    {
        $this->state = $state;
        $this->changes = new Collection();
        
        $this->type = $type_factory->aggregate_class(get_called_class());
        $this->check = new Check($invariant_factory, $state);
        $this->id_generator = $id_generator;
        $this->projection_factory = $projection_factory;
    }

    public function handle(Command $command)
    {
        $this->current_command = $command;
        $this->mutate($command);
    }

    protected function apply($domain_event, ...$parameters)
    {
        if (is_string($domain_event)) {
            $domain_event = $this->make_domain_event($domain_event, $parameters);
        }
        $this->state->apply($domain_event);
        $loggable_event = $this->make_loggable_event($domain_event);
        $this->changes->append($loggable_event);
    }

    private function make_domain_event($event_class, $parameters)
    {
        $aggregate_id_and_parameters = array_merge([$this->state()->aggregate_id()], $parameters);
        return new $event_class(...$aggregate_id_and_parameters);
    }
    
    private function make_loggable_event($domain_event)
    {
        return new Event(
            $this->id_generator->generate(),
            Uuid::generate(),
            $this->state->aggregate_type(),
            $this->state->aggregate_id(), 
            $domain_event
        );
    }

    public function state()
    {
        return $this->state;
    }

    public function projection()
    {
        return $this->state->queryable();
    }

    public function changes()
    {
        return $this->changes;
    }
    
    public function flush()
    {
        $this->changes = new Collection();
    }

    protected function domainProjection($projection_interface)
    {
        return $this->projection_factory->fromInterface($projection_interface);
    }

    protected function id()
    {
        return $this->state->aggregate_id();
    }
}

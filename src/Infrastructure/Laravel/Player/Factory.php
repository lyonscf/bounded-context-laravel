<?php namespace BoundedContext\Laravel\Player;

use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Contracts\Player\Snapshot\Snapshot;
use BoundedContext\Player\Snapshot\Snapshot as PlayerSnapshot;
use BoundedContext\Contracts\Projection\Projection;
use BoundedContext\Contracts\Sourced\Log\Event as EventLog;
use App;

class Factory implements \BoundedContext\Contracts\Player\Factory
{
    private $event_log;
    private $datetime_generator;
    private $identifier_generator;

    public function __construct(
        EventLog $event_log,
        DateTimeGenerator $datetime_generator,
        IdentifierGenerator $identifier_generator
    )
    {
        $this->event_log = $event_log;
        $this->datetime_generator = $datetime_generator;
        $this->identifier_generator = $identifier_generator;
    }

    public function snapshot(Snapshot $snapshot)
    {
        $player_class = $snapshot->class_name()->value();

        $reflection = new \ReflectionClass($player_class);
        $parameters = $reflection->getConstructor()->getParameters();

        $args = [];
        
        foreach ($parameters as $parameter) {
            $parameter_name = $parameter->getName();
            $parameter_contract = $parameter->getClass()->name;

            if ($parameter_contract === Projection::class) {
                $args[$parameter_name] = App::make(
                    $this->get_implementation_by_interface($player_class)
                );
            } elseif ($parameter_contract === Snapshot::class) {
                $args[$parameter_name] = $snapshot;
            } elseif ($parameter_contract === EventLog::class) {
                $args[$parameter_name] = $this->event_log;
            } else {
                $args[$parameter_name] = App::make($parameter_contract);
            }
        }

        return $reflection->newInstanceArgs($args);
    }

    private function get_implementation_by_interface($interface_class)
    {
        return str_replace('Projector', 'Projection', $interface_class);
    }

    public function make($class_name)
    {
        $snapshot = PlayerSnapshot::make($class_name, $this->identifier_generator, $this->datetime_generator);
        $player = $this->snapshot($snapshot);
        $player->reset();
        return $player;
    }
}

<?php namespace BoundedContext\Laravel\Bus;

use BoundedContext\Contracts\Collection\Collection;
use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Sourced\Log\Command as CommandLog;
use BoundedContext\Contracts\Sourced\Aggregate\Repository as AggregateRepository;
use BoundedContext\Laravel\Player\Collection\Builder as PlayerBuilder;
use DB;

class Dispatcher implements \BoundedContext\Contracts\Bus\Dispatcher
{
    private $command_log;
    private $aggregate_repository;
    private $player_builder;

    public function __construct(
        AggregateRepository $aggregate_repository,
        CommandLog $command_log,
        PlayerBuilder $player_builder
    )
    {
        $this->aggregate_repository = $aggregate_repository;
        $this->command_log = $command_log;
        $this->player_builder = $player_builder;
    }

    protected function run(Command $command)
    {
        $aggregate = $this->aggregate_repository->by($command);

        $aggregate->handle($command);

        $this->aggregate_repository->save($aggregate);

        $this->command_log->append($command);
        
        $this->player_builder->domain()->get()->play();
    }
    
    public function dispatch(Command $command)
    {
        $this->run($command);
    }

    public function dispatch_collection(Collection $commands)
    {
        foreach ($commands as $command) {
            $this->run($command);
        }
    }
}

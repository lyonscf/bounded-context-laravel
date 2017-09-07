<?php namespace BoundedContext\Laravel\Sourced\Aggregate\Player;

use BoundedContext\Collection\Collection;
use BoundedContext\Contracts\Player\Repository as PlayerRepository;
use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;
use BoundedContext\Player\Collection\Player;

class Factory
{
    protected $player_repository;

    public function __construct(PlayerRepository $player_repository)
    {
        $this->player_repository = $player_repository;
    }

    public function aggregate(Aggregate $aggregate)
    {

        return new Player(
            $this->player_repository,
            new Collection()
        );
    }
}

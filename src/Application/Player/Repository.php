<?php namespace BoundedContext\Player;

use BoundedContext\Contracts\Player\Player;
use BoundedContext\Contracts\Player\Snapshot\Repository as SnapshotRepository;
use BoundedContext\Contracts\Player\Factory as PlayerFactory;
use BoundedContext\Player\Snapshot\ClassName;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

class Repository implements \BoundedContext\Contracts\Player\Repository
{
    private $player_factory;
    private $snapshot_repository;

    public function __construct(
        PlayerFactory $player_factory,
        SnapshotRepository $snapshot_repository
    )
    {
        $this->player_factory = $player_factory;
        $this->snapshot_repository = $snapshot_repository;
    }

    public function get(ClassName $class_name)
    {
        $snapshot = $this->snapshot_repository->get($class_name);

        if (!$snapshot) {
            $player = $this->player_factory->make($class_name);
            $this->snapshot_repository->create($player->snapshot());
            return $player;
        }
        return $this->player_factory->snapshot($snapshot);
    }

    public function save(Player $player)
    {
        $this->snapshot_repository->save($player->snapshot());
    }

    public function hasVersionChanged(ClassName $class_name)
    {
        $snapshot = $this->snapshot_repository->get($class_name);

         // by default, the player version should be 0, not 1
         // for already existing players at version 1, we will have a snapshot that will tell us the version is 1 and this method will return false
         // for new players without a snapshot the static method ::version() will return 1 so this method returns true
         $active_version = new Integer_(0);
         if ($snapshot) {
             $active_version = $snapshot->playerVersion();
         }

        $player_class = $class_name->value();

        return $active_version->value() != $player_class::version();
    }
}

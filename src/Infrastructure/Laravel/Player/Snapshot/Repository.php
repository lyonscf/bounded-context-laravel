<?php namespace BoundedContext\Laravel\Player\Snapshot;

use BoundedContext\Contracts\Player\Snapshot\Snapshot;
use BoundedContext\Player\Snapshot\Factory;
use EventSourced\ValueObject\Serializer\Serializer;
use DB;
use BoundedContext\Player\Snapshot\ClassName;

class Repository implements \BoundedContext\Contracts\Player\Snapshot\Repository
{
    private $connection;
    private $table = 'player_snapshots';
    private $serializer;
    private $snapshot_factory;

    public function __construct(
        Factory $snapshot_factory,
        Serializer $serializer
    )
    {
        $this->connection = DB::connection();
        $this->snapshot_factory = $snapshot_factory;
        $this->serializer = $serializer;
    }

    protected function query()
    {
        return $this->connection->table($this->table);
    }

    public function get(ClassName $class_name)
    {
        $player_class = $class_name->value();

        $row = $this->query()
            ->where('class_name', $class_name->value())
            ->where('player_version', $player_class::version())
            ->first();

        if (!$row) {
            return null;
        }

        $row_array = (array) $row;
        return $this->snapshot_factory->make($row_array);
    }

    public function create(Snapshot $snapshot)
    {
        $this->query()
            ->insert(
                $this->serializer->serialize($snapshot)
            );
    }

    public function save(Snapshot $snapshot)
    {
        $this->query()
            ->where('class_name', $snapshot->class_name()->value())
            ->where('player_version', $snapshot->playerVersion()->value())
            ->update(
                $this->serializer->serialize($snapshot)
            );
    }
}

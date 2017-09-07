<?php namespace BoundedContext\Laravel\Sourced\Aggregate\State\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Factory as StateSnapshotFactory;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Snapshot;
use BoundedContext\Laravel\Illuminate\Projection\AbstractQueryable;
use EventSourced\ValueObject\Serializer\Serializer;

class Repository extends AbstractQueryable implements \BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Repository
{
    protected $state_snapshot_factory;
    protected $table = 'snapshots_aggregate_state';
    protected $serializer;
    
    public function __construct(
        StateSnapshotFactory $state_snapshot_factory,
        Serializer $serializer)
    {
        parent::__construct();

        $this->state_snapshot_factory = $state_snapshot_factory;
        $this->serializer = $serializer;
    }

    public function ids(Identifier $aggregate_id, Identifier $aggregate_type)
    {
        $snapshot_row = $this->query()
            ->where('aggregate_id', $aggregate_id->value())
            ->where('aggregate_type', $aggregate_type->value())
            ->first()
        ;

        $snapshot_array = (array) $snapshot_row;

        if (!$snapshot_array) {
            return $this->state_snapshot_factory->create($aggregate_id, $aggregate_type);
        }

        $snapshot_array['state'] = json_decode($snapshot_array['state'], true);
        
        return $this->state_snapshot_factory->tree(
            $snapshot_array
        );
    }

    public function save(Snapshot $snapshot)
    {   
        $encoded_state = json_encode($snapshot->schema()->data_tree());

        $this->query()->getConnection()->statement(
            'INSERT INTO ' . $this->table .
            ' (aggregate_id, aggregate_type, occurred_at, version, state) ' .
            'VALUES( '
            . '\'' . $snapshot->aggregate_id()->value() . '\','
            . '\'' . $snapshot->aggregate_type()->value() . '\','
            . '\'' . $snapshot->occurred_at()->value() . '\','
            . '\'' . $snapshot->version()->value() . '\','
            . '\'' . $encoded_state . '\'' .
            ') ' .
            'ON DUPLICATE KEY UPDATE ' .
            'occurred_at = \'' . $snapshot->occurred_at()->value() . '\', ' .
            'version = \'' . $snapshot->version()->value() . '\', ' .
            'state = \'' . $encoded_state . '\''
        );
    }
}

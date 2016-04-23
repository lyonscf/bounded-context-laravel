<?php namespace BoundedContext\Laravel\Illuminate\Log;

use BoundedContext\Contracts\Event\Snapshot\Factory;
use Illuminate\Database\DatabaseManager;
use BoundedContext\Sourced\Stream\Builder;
use BoundedContext\Laravel\Illuminate\BinaryString;
use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;
use EventSourced\ValueObject\ValueObject\Integer;
use EventSourced\ValueObject\ValueObject\Uuid;

class Event implements \BoundedContext\Contracts\Sourced\Log\Event
{
    private $connection;
    private $snapshot_factory;
    private $binary_string_factory;
    private $table = "event_log";
    private $stream_builder;
    
    public function __construct(
        Factory $snapshot_factory, 
        DatabaseManager $db_manager, 
        Builder $stream_builder,
        BinaryString\Factory $binary_string_factory
    )
    {
        $this->snapshot_factory = $snapshot_factory;
        $this->connection = $db_manager->connection();
        $this->binary_string_factory = $binary_string_factory;
        $this->stream_builder = $stream_builder;
    }
        
    public function append_aggregate_events(Aggregate $aggregate)
    {
        $events = $aggregate->changes();
        if (count($events) == 0) {
            return;
        }
        
        $state = $aggregate->state();

        $binary_aggregate_id = $this->binary_string_factory->uuid($state->aggregate_id());
        $binary_aggregate_type_id = $this->binary_string_factory->uuid($state->aggregate_type_id());
        
        $loaded_version = $state->version()->subtract($events->count());

        $this->lock_rows($state->aggregate_id(), $state->aggregate_type_id());
        
        $log_version = $this->log_version($binary_aggregate_id, $binary_aggregate_type_id);
        
        if (!$loaded_version->equals($log_version)) {
            $this->unlock_rows($state->aggregate_id(), $state->aggregate_type_id());
            throw new \Exception("Aggregate has already been updated during this transation by another thread.");
        }
        
        $inserts = [];
        foreach ($events as $event) {
            $snapshot = $this->snapshot_factory->event($event);
            $encoded_snapshot = $this->encode_snapshot($snapshot);
            $inserts[] = [
                'id' => $this->binary_string_factory->uuid($snapshot->id()),
                'aggregate_id' => $binary_aggregate_id,
                'aggregate_type_id' => $binary_aggregate_type_id,
                'snapshot' => $encoded_snapshot
            ];
        }
        $this->connection->table($this->table)->insert($inserts);
        
        $this->unlock_rows($state->aggregate_id(), $state->aggregate_type_id());
    }
    
    private function encode_snapshot(\BoundedContext\Contracts\Event\Snapshot\Snapshot $snapshot)
    {
        return json_encode([
            'id' => $snapshot->id()->value(),
            'type_id' => $snapshot->type_id()->value(),
            'version' => $snapshot->version()->value(),
            'occurred_at' => $snapshot->occurred_at()->value(),
            'event' => $snapshot->schema()->data_tree()
        ]);
    }
    
    private function log_version($binary_aggregate_id, $binary_aggregate_type_id)
    {
        $query = $this->connection
            ->table($this->table)
            ->selectRaw("COUNT(*) as version")
            ->where("aggregate_id", $binary_aggregate_id)
            ->where("aggregate_type_id", $binary_aggregate_type_id);
                
        $row = $query->first();
        
        return new Integer($row->version);
    }
    
    private function lock_rows(Uuid $id, Uuid $type_id)
    {
        $this->connection->raw(
            "SELECT GET_LOCK(:lockid, 1)", 
            ['lockid'=> $this->lock_id($id, $type_id)]
        );
    }
    
    private function unlock_rows(Uuid $id, Uuid $type_id)
    {
        $this->connection->raw(
            "SELECT RELEASE_LOCK(:lockid)", 
            ['lockid'=> $this->lock_id($id, $type_id)]
        );
    }
    
    private function lock_id(Uuid $id, Uuid $type_id)
    {
        return $id->value().'-'.$type_id->value();
    }

    public function builder()
    {
        return $this->stream_builder;
    }

    public function reset()
    {
        $this->connection
            ->table($this->table)
            ->delete();
    }
}
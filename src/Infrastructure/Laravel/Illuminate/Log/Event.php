<?php namespace BoundedContext\Laravel\Illuminate\Log;

use BoundedContext\Contracts\Event\Snapshot\Factory;
use BoundedContext\Contracts\Event\Snapshot\Transformer;
use BoundedContext\Laravel\Serializer\ErrorAwareJsonSerializer;
use BoundedContext\Laravel\Sourced\Aggregate\Locker;
use Illuminate\Database\DatabaseManager;
use BoundedContext\Sourced\Stream\Builder;
use BoundedContext\Laravel\Illuminate\BinaryString;
use BoundedContext\Contracts\Sourced\Aggregate\Aggregate;

class Event implements \BoundedContext\Contracts\Sourced\Log\Event
{
    private $connection;
    private $binary_string_factory;
    private $stream_builder;
    private $log_table;
    private $json_serializer;
    private $locker;
    private $snapshot_transformer;

    public function __construct(
        Factory $snapshot_factory,
        DatabaseManager $db_manager,
        Builder $stream_builder,
        BinaryString\Factory $binary_string_factory,
        ErrorAwareJsonSerializer $json_serializer,
        Locker $locker,
        Transformer $snapshot_transformer
    )
    {
        $this->connection = $db_manager->connection();
        $this->binary_string_factory = $binary_string_factory;
        $this->stream_builder = $stream_builder;
        $this->log_table = config('logs.event_log.table_name');
        $this->json_serializer = $json_serializer;
        $this->locker = $locker;
        $this->snapshot_transformer = $snapshot_transformer;
    }

    public function append_aggregate_events(Aggregate $aggregate)
    {
        if (count($aggregate->changes()->count()) == 0) {
            return;
        }

        $state = $aggregate->state();

        $this->store_events($aggregate);

        $this->locker->unlock($state->aggregate_id(), $state->aggregate_type());
    }

    protected static $appended_events = [];

    private function store_events(Aggregate $aggregate)
    {
        $events = $aggregate->changes();
        $state = $aggregate->state();

        $binary_aggregate_id = $this->binary_string_factory->uuid($state->aggregate_id());

        $inserts = [];
        foreach ($events as $event) {
            $snapshot = $this->snapshot_transformer->fromEvent($event);
            $popo_snapshot = $this->snapshot_transformer->toPopo($snapshot);
            $inserts[] = [
                'id' => $this->binary_string_factory->uuid($event->id()),
                'aggregate_id' => $binary_aggregate_id,
                'aggregate_type' => $state->aggregate_type()->value(),
                'snapshot' => $this->json_serializer->serialize($popo_snapshot)
            ];
            static::$appended_events[] = $popo_snapshot;
        }
        $this->connection->table($this->log_table)->insert($inserts);
    }

    public function builder()
    {
        return $this->stream_builder;
    }

    public function reset()
    {
        $this->connection
            ->table($this->log_table)
            ->delete();
    }

    public function get_appended_events()
    {
        $events = static::$appended_events;
        static::$appended_events = [];
        return $events;
    }

    /**
     * Returns latest event from event log
     *
     * @return mixed
     */
    public function get_last_event()
    {
        $event = $this->connection->table($this->log_table)
            ->orderBy('order', 'DESC')
            ->first();

        if (!$event) {
            return null;
        }

        return $this->snapshot_transformer->fromPopo($event);
    }
}

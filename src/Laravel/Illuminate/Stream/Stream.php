<?php namespace BoundedContext\Laravel\Illuminate\Stream;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Sourced\Stream\AbstractStream;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

use BoundedContext\Laravel\Event\Snapshot\Factory as EventSnapshotFactory;
use Illuminate\Database\ConnectionInterface;
use BoundedContext\Laravel\Illuminate\BinaryString;

class Stream extends AbstractStream implements \BoundedContext\Contracts\Sourced\Stream\Stream
{
    private $connection;
    private $binary_string_factory;

    private $starting_id;
    private $order_offset;

    public function __construct(
        ConnectionInterface $connection,
        EventSnapshotFactory $event_snapshot_factory,
        BinaryString\Factory $binary_string_factory,
        Identifier $starting_id,
        Integer_  $limit,
        Integer_ $chunk_size
    )
    {
        $this->connection = $connection;
        $this->binary_string_factory = $binary_string_factory;

        $this->starting_id = $starting_id;  
        
        $this->reset();
        
        parent::__construct(
            $event_snapshot_factory,
            $limit,
            $chunk_size
        );
    }

    public function reset()
    {
        if ($this->starting_id->is_null()) {
            $this->order_offset = -1;
        } else {
            $this->order_offset = null;
        }
        
        parent::reset();
    }

    protected function get_next_chunk()
    {
        $query = $this->connection
            ->table($this->log_table)
            ->select("snapshot", "order")
            ->orderBy("order")
            ->limit($this->chunk_size->value());
        
        if (is_null($this->order_offset)) {
            $query = $query->whereRaw("
                `order` >
                    (
                        SELECT `order` FROM `$this->log_table`
                        WHERE id = '".$this->binary_string_factory->uuid($this->starting_id)."'
                    )
                "
            );
        } else {
            $query = $query->where("order", ">", $this->order_offset);
        }

        $rows = $query->get();
        
        return $rows;
    }
    
    protected function set_offset(array $event_snapshot_rows)
    {
        if (count($event_snapshot_rows)) {
            $last_element_index = count($event_snapshot_rows) - 1;
            $last_in_collection = $event_snapshot_rows[$last_element_index];
            $this->order_offset = $last_in_collection->order;
        }        
    }
}

<?php namespace BoundedContext\Sourced\Stream;

use BoundedContext\Collection\Collection;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

abstract class AbstractStream
{
    protected $limit;
    protected $chunk_size;

    /**
     * @var Integer_
     */
    protected $streamed_count;

    /**
     * @var Collection
     */
    protected $event_snapshots;

    public function __construct(
        Integer_ $limit,
        Integer_ $chunk_size
    )
    {
        $this->limit = $limit;
        $this->chunk_size = $chunk_size;

        $this->reset();
        $this->fetch();
    }

    protected function reset()
    {
        $this->streamed_count = new Integer_(0);
        $this->event_snapshots = new Collection();
    }

    /**
     * Fetches the next set of event snapshot schemas.
     * @return void
     */
    protected function fetch()
    {
        $this->event_snapshots = new Collection();

        $event_snapshot_schemas = $this->get_next_chunk();

        foreach ($event_snapshot_schemas as $event_snapshot_schema) {
            $snapshot_popo = json_decode($event_snapshot_schema->snapshot);
            $this->event_snapshots->append($snapshot_popo);
        }

        $this->set_offset($event_snapshot_schemas);
    }

    abstract protected function get_next_chunk();

    abstract protected function set_offset(array $event_snapshot_rows);

    protected function is_unlimited()
    {
        return ($this->limit->equals(new Integer_(0)));
    }

    public function current()
    {
        return $this->event_snapshots->current();
    }

    public function next()
    {
        $this->event_snapshots->next();

        if(!$this->event_snapshots->valid())
        {
            $this->fetch();
        }

        $this->streamed_count = $this->streamed_count->increment();
    }

    public function key()
    {
        return $this->event_snapshots->key();
    }

    public function valid()
    {
        if(
            $this->streamed_count->equals($this->limit) &&
            !$this->is_unlimited()
        )
        {
            return false;
        }

        return $this->event_snapshots->valid();
    }

    public function rewind()
    {
        $this->event_snapshots->rewind();
    }
}

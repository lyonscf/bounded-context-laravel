<?php namespace BoundedContext\Sourced\Stream;

use BoundedContext\Contracts\Sourced\Stream\Stream;
use BoundedContext\Contracts\Event\Snapshot;

class SnapshotStream implements Stream
{
    private $snapshot_transformer;
    private $stream;

    public function __construct(Snapshot\Transformer $snapshot_transformer, Stream $stream)
    {
        $this->snapshot_transformer = $snapshot_transformer;
        $this->stream = $stream;
    }

    public function current()
    {
        $popo = $this->stream->current();

        if (!$popo) {
            return null;
        }

        return $this->popo_to_event_snapshot($popo);
    }

    private function popo_to_event_snapshot($popo)
    {
        return $this->snapshot_transformer->fromPopo($popo);
    }

    public function next()
    {
        $this->stream->next();
    }

    public function key()
    {
        return $this->stream->key();
    }

    public function valid()
    {
        return $this->stream->valid();
    }

    public function rewind()
    {
        //$this->stream->rewind();
    }
}
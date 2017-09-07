<?php namespace BoundedContext\Laravel\Illuminate\Stream;

use BoundedContext\Contracts\Event\Snapshot;
use BoundedContext\Laravel\Illuminate\Stream\Stream as LogStream;
use BoundedContext\Laravel\Illuminate\BinaryString;
use BoundedContext\Sourced\Stream\SnapshotStream;
use BoundedContext\Sourced\Stream\UpgradedStream;
use BoundedContext\Sourced\Stream\Upgrader as StreamUpgrader;
use DB;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

class Factory implements \BoundedContext\Contracts\Sourced\Stream\Factory
{
    private $binary_string_factory;
    private $stream_upgrader;
    private $snapshot_transformer;

    public function __construct(
        BinaryString\Factory $binary_string_factory,
        StreamUpgrader $stream_upgrader,
        Snapshot\Transformer $snapshot_transformer
    )
    {
        $this->binary_string_factory = $binary_string_factory;
        $this->stream_upgrader = $stream_upgrader;
        $this->snapshot_transformer = $snapshot_transformer;
    }

    public function create(
        Identifier $starting_id,
        Integer_ $limit,
        Integer_ $chunk_size
    )
    {
        $log_stream = new LogStream(
            DB::connection(),
            $this->binary_string_factory,
            $starting_id,
            $limit,
            $chunk_size
        );
        $upgraded_stream = new UpgradedStream($log_stream, $this->stream_upgrader);

        return new SnapshotStream($this->snapshot_transformer, $upgraded_stream);
    }
}

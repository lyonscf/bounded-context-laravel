<?php namespace BoundedContext\Laravel\Sourced\Aggregate\Stream;

use BoundedContext\Sourced\Stream\SnapshotStream;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;
use BoundedContext\Laravel\Sourced\Aggregate\Stream\Stream as AggregateStream;
use BoundedContext\Sourced\Stream\UpgradedStream;
use App;
use DB;

class Factory implements \BoundedContext\Contracts\Sourced\Aggregate\Stream\Factory
{
    public function create(
        Identifier $aggregate_id,
        Identifier $aggregate_type,
        Integer_ $starting_offset,
        Integer_ $limit,
        Integer_ $chunk_size
    )
    {
        // TODO: All dependencies should be injected, rather than using App::make, it's horrible and inconsistent

        $aggregate_stream = new AggregateStream(
            DB::connection(),
            App::make(\BoundedContext\Laravel\Illuminate\BinaryString\Factory::class),
            $aggregate_id,
            $aggregate_type,
            $starting_offset,
            $limit,
            $chunk_size
        );

        $upgraded_stream = App::make(UpgradedStream::class, [
            $aggregate_stream,
            App::make(\BoundedContext\Sourced\Stream\Upgrader::class)
        ]);

        $snapshot_transformer = App::make(\BoundedContext\Contracts\Event\Snapshot\Transformer::class);

        return new SnapshotStream($snapshot_transformer, $upgraded_stream);
    }
}

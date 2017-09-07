<?php namespace BoundedContext\Contracts\Sourced\Aggregate\Stream;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Sourced\Stream\Stream;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

interface Factory
{
    /**
     * Creates a new Stream.
     *
     * @param Identifier $aggregate_id
     * @param Identifier $aggregate_type
     * @param Integer_ $starting_offset
     * @param Integer_ $limit
     * @param Integer_ $chunk_size
     *
     * @return Stream
     */
    public function create(
        Identifier $aggregate_id,
        Identifier $aggregate_type,
        Integer_ $starting_offset,
        Integer_$limit,
        Integer_ $chunk_size
    );
}

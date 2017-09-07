<?php namespace BoundedContext\Contracts\Sourced\Stream;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

interface Factory
{
    /**
     * Creates a new Stream.
     *
     * @param Identifier $starting_id
     * @param Integer_ $limit
     * @param Integer_ $chunk_size
     *
     * @return Stream
     */
    public function create(
        Identifier $starting_id,
        Integer_ $limit,
        Integer_ $chunk_size
    );
}

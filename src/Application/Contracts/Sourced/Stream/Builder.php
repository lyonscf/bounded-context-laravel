<?php namespace BoundedContext\Contracts\Sourced\Stream;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

interface Builder
{
    /**
     * Sets the Identifier to stream after.
     *
     * @param Identifier $starting_id
     * @return Builder
     */
    public function after(Identifier $starting_id);

    /**
     * Sets the maximum number of snapshots to be streamed.
     *
     * @param Integer_ $limit
     * @return Builder
     */
    public function limit(Integer_ $limit);

    /**
     * Sets the chunk size of the Streamer.
     *
     * @param Integer_ $size
     * @return Builder
     */
    public function chunk(Integer_ $size);

    /**
     * Returns a new Stream for the Log.
     *
     * @return Stream
     */
    public function stream();
}

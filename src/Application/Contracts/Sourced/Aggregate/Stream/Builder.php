<?php namespace BoundedContext\Contracts\Sourced\Aggregate\Stream;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Sourced\Stream\Stream;
use EventSourced\ValueObject\ValueObject\Integer as Integer_; // Fuck off PHP

interface Builder
{
    /**
     * Sets that the Stream should look for snapshots with an id.
     *
     * @return Builder
     */
    public function ids(Identifier $aggregate_id, Identifier $aggregate_type);

    /**
     * Sets that the Stream should look for snapshots after a version.
     *
     * @param Integer_ $version
     * @return Builder
     */
    public function after(Integer_ $version);

    /**
     * Sets the limit of the Stream.
     * Defaults to 1000.
     *
     * @return Builder
     */
    public function limit(Integer_ $limit);

    /**
     * Sets the chunk size from the data store.
     * Defaults to 1000.
     *
     * @return Builder
     */
    public function chunk(Integer_ $size);

    /**
     * Returns the configured Stream.
     *
     * @return Stream
     */
    public function stream();
}

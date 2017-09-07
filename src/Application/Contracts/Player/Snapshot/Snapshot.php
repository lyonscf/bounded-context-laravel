<?php namespace BoundedContext\Contracts\Player\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

interface Snapshot extends \BoundedContext\Contracts\Snapshot\Snapshot
{
    /**
     * @return ValueObject
     */
    public function class_name();

    /**
     * Returns the last id of this Snapshot.
     *
     * @return Identifier
     */

    public function last_id();

    /**
     * @return Integer_
     */
    public function playerVersion();

    /**
     * Returns a new Snapshot after resetting it back to its default state.
     *
     * @param IdentifierGenerator $identifier_generator
     * @param DateTimeGenerator $datetime_generator
     *
     * @return Snapshot
     */
    public function reset(
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator,
        Integer_ $version
    );

    /**
     * Returns a new Snapshot after skipping the current id.
     *
     * @param Identifier $next_id
     * @param DateTimeGenerator $datetime_generator
     * @return Snapshot
     */

    public function skip(
        Identifier $next_id,
        DateTimeGenerator $datetime_generator
    );

    /**
     * Returns a new Snapshot after processing the current id.
     *
     * @return Snapshot
     */

    public function take(
        Identifier $next_id,
        DateTimeGenerator $datetime_generator
    );
}

<?php namespace BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot;

use BoundedContext\Contracts\Sourced\Aggregate\State\State;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

interface Factory
{
    /**
     * Creates a new Snapshot from a State.
     *
     * @param Identifier $aggregate_id
     * @param Identifier $aggregate_type
     * @return Snapshot
     */

    public function create(Identifier $aggregate_id, Identifier $aggregate_type);

    /**
     * Creates a Snapshot from a Tree.
     *
     * @param array $tree
     * @return Snapshot
     */

    public function tree(array $tree);

    /**
     * Creates a Snapshot of a State.
     *
     * @param State $state
     * @return Snapshot
     */

    public function state(State $state);
}

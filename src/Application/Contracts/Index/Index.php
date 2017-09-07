<?php namespace BoundedContext\Contracts\Index;

use BoundedContext\Contracts\Core\Collector;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Boolean;
use BoundedContext\Contracts\Core\Countable;
use BoundedContext\Contracts\Entity\Entity;

interface Index extends Countable, Collector
{
    /*
     * Evaluates whether or not two Entities are equal.
     *
     * @param Identifier $id
     * @return Boolean
     */
    public function exists(Identifier $id);

    /*
     * Evaluates whether or not two Entities are equal.
     *
     * @param Entity $entity
     * @throws Exception\Exists
     * @return void
     */
    public function add(Entity $entity);

    /*
     * Evaluates whether or not two Entities are equal.
     *
     * @param Entity $entity
     * @throws Exception\NotExists
     * @return void
     */
    public function replace(Entity $entity);

    /*
     * Evaluates whether or not two Entities are equal.
     *
     * @param Identifier $id
     * @throws Exception\NotExists
     * @return void
     */
    public function remove(Identifier $id);
}


<?php namespace BoundedContext\Index;

use BoundedContext\Collection\Collection;
use BoundedContext\Contracts\Entity\Entity;
use BoundedContext\Contracts\Index\Exception\Exists;
use BoundedContext\Contracts\Index\Exception\NotExists;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

class Index implements \BoundedContext\Contracts\Index\Index
{
    private $index;

    public function __construct(array $items = [])
    {
        $this->index = [];

        foreach($items as $item)
        {
            $this->add($item);
        }
    }

    public function collection()
    {
        return new Collection($this->index);
    }

    public function count()
    {
        return count($this->index);
    }

    public function exists(Identifier $id)
    {
        return array_key_exists($id->value(), $this->index);
    }

    public function add(Entity $entity)
    {
        if($this->exists($entity->id()))
        {
            throw new Exists("Entity" . $entity->id()->value() . " already exists in this index.");
        }

        $this->index[$entity->id()->value()] = $entity;
    }

    public function replace(Entity $entity)
    {
        if(!$this->exists($entity->id()))
        {
            throw new NotExists("Entity" . $entity->id()->value() . " does not exist in this index.");
        }

        $this->index[$entity->id()->value()] = $entity;
    }

    public function remove(Identifier $id)
    {
        if(!$this->exists($id))
        {
            throw new NotExists("Entity" . $id->value() . " does not exist in this index.");
        }

        unset($this->index[$id->value()]);
    }

    public function serialize()
    {
        return $this->collection()->value();
    }
}

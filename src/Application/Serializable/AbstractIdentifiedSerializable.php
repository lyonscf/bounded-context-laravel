<?php namespace BoundedContext\Serializable;

use BoundedContext\Contracts\Core\Identifiable;
use EventSourced\ValueObject\Contracts\ValueObject;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

class AbstractIdentifiedSerializable extends AbstractSerializable implements Identifiable
{
    protected $id;

    public function __construct(Identifier $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function equals(ValueObject $other)
    {
        return ($this->value() === $other->value());
    }

    public function serialize()
    {
        $serialized = parent::serialize();
        $serialized['id'] = $this->id->value();

        return $serialized;
    }
}
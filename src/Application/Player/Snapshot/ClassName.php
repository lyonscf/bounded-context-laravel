<?php namespace BoundedContext\Player\Snapshot;

use BoundedContext\Contracts\Core\Collectable;
use EventSourced\ValueObject\ValueObject\Type\AbstractSingleValue;

class ClassName extends AbstractSingleValue implements Collectable
{
    protected function validator()
    {
        return parent::validator();
    }
}
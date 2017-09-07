<?php namespace BoundedContext\Event;

use EventSourced\ValueObject\ValueObject\Type\AbstractComposite;
use EventSourced\ValueObject\ValueObject\Integer;

abstract class AbstractEvent extends AbstractComposite
{

    public function version()
    {
        return new Integer(1);
    }
}
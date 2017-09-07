<?php namespace BoundedContext\Contracts\Core;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

interface Identifiable
{
    /**
     * Returns the objects unique identifier.
     *
     * @return Identifier
     */

    public function id();
}

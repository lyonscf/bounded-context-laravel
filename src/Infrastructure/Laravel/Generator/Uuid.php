<?php namespace BoundedContext\Laravel\Generator;

use Rhumsaa\Uuid\Uuid as RhumsaaUuid;
use EventSourced\ValueObject\ValueObject;

class Uuid implements \BoundedContext\Contracts\Generator\Identifier
{
    public function generate()
    {
        $uuid = RhumsaaUuid::uuid4();
        return new ValueObject\Uuid( $uuid->toString() );
    }

    public function null()
    {
        return new ValueObject\Uuid('00000000-0000-0000-0000-000000000000');
    }

    public function string($identifier)
    {
        return new ValueObject\Uuid($identifier);
    }
}

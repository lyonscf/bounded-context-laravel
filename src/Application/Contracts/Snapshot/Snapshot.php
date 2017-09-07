<?php namespace BoundedContext\Contracts\Snapshot;

use BoundedContext\Contracts\Core\Temporal;
use BoundedContext\Contracts\Core\Versionable;
use EventSourced\ValueObject\Contracts\ValueObject;

interface Snapshot extends Versionable, Temporal, ValueObject
{

}

<?php namespace BoundedContext\Contracts\Workflow;

use BoundedContext\Contracts\Core\Playable;
use BoundedContext\Contracts\Core\Resetable;
use BoundedContext\Contracts\Core\Snapshotable;

interface Workflow extends Resetable, Playable, Snapshotable
{

}

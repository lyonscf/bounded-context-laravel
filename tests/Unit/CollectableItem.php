<?php namespace BoundedContext\Tests\Unit;

use BoundedContext\Contracts\Core\Collectable;

class CollectableItem implements Collectable
{
    protected $secret;

    public function __construct($value)
    {
        $this->secret = $value;
    }

    public function value()
    {
        return $this->secret;
    }
}

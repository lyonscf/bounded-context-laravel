<?php namespace BoundedContext\Tests\Unit\Invariant;

use BoundedContext\Business\Invariant\AbstractInvariant;
use BoundedContext\Contracts\Business\Invariant\Invariant;

class LogicInvariant extends AbstractInvariant implements Invariant
{
    protected $error_message_positive = "Universe does exist";
    protected $error_message_negative = "Universe does not exist";

    protected $flag;

    protected function assumptions($flag)
    {
        $this->flag = $flag;
    }

    protected function satisfier()
    {
        return !!$this->flag;
    }
}

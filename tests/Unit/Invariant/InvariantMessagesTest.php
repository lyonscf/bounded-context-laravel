<?php namespace BoundedContext\Tests\Unit\Invariant;

use BoundedContext\Contracts\Business\Invariant;

class InvariantMessagesTest extends \PHPUnit_Framework_TestCase
{
    public function test_valid_positive_assumption()
    {
        $exists = true;
        $invariant = new LogicInvariant(new FakeQueryable());
        $invariant->assuming([$exists])->asserts();
    }

    public function test_invalid_positive_assumption()
    {
        $this->setExpectedException(
            Invariant\Exception::class,
            'Universe does exist'
        );

        $exists = true;
        $invariant = new LogicInvariant(new FakeQueryable());
        $invariant->assuming([$exists])->not()->asserts();
    }

    public function test_valid_negative_assumption()
    {
        $exists = false;
        $invariant = new LogicInvariant(new FakeQueryable());
        $invariant->assuming([$exists])->not()->asserts();
    }

    public function test_invalid_negative_assumption()
    {
        $this->setExpectedException(
            Invariant\Exception::class,
            "Universe does not exist"
        );
        
        $exists = false;
        $invariant = new LogicInvariant(new FakeQueryable());
        $invariant->assuming([$exists])->asserts();
    }
}

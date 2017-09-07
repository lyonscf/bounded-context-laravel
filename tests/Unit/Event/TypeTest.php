<?php namespace BoundedContext\Tests\Unit\Event;

use BoundedContext\Event\Type;
use BoundedContext\Event\TypeException;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function test_title_case_is_turned_into_snakecase()
    {
        $event_class = "Domain\\Iam\\Identity\\Aggregate\\User\\Event\\Registered";

        $expected = new Type("iam.identity.user.registered");
        $actual = Type::from_event_class($event_class);

        $this->assertEquals($expected, $actual);
    }

    public function test_mid_uppercases_become_underscores()
    {
        $event_class = "Domain\\Iam\\Identity\\Aggregate\\User\\Event\\LoginFailed";

        $expected = new Type("iam.identity.user.login_failed");
        $actual = Type::from_event_class($event_class);

        $this->assertEquals($expected, $actual);
    }

    public function test_fails_if_multiple_uppercases_are_found()
    {
        $this->setExpectedException(TypeException::class);
        $event_class = "Domain\\IAM\\Identity\\Aggregate\\User\\Event\\LoginFailed";

        $actual = Type::from_event_class($event_class);
    }

    public function test_converting_back_to_a_event_class()
    {
        $type = new Type("iam.identity.user.login_failed");

        $expected = "Domain\\Iam\\Identity\\Aggregate\\User\\Event\\LoginFailed";
        $actual = $type->to_event_class();

        $this->assertEquals($expected, $actual);
    }
}

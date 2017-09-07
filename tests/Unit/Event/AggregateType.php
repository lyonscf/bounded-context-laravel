<?php namespace BoundedContext\Tests\Unit\Event;

use BoundedContext\Event\AggregateType;
use BoundedContext\Event\TypeException;

class AggregateTypeTest extends \PHPUnit_Framework_TestCase
{
    public function test_title_case_is_turned_into_snakecase()
    {
        $aggregate_class = "Domain\\Iam\\Identity\\Aggregate\\User";

        $expected = new AggregateType("iam.identity.user");
        $actual = AggregateType::from_class_string($aggregate_class);

        $this->assertEquals($expected, $actual);
    }

    public function test_mid_uppercases_become_underscores()
    {
        $aggregate_class = "Domain\\Iam\\AccessManagement\\Aggregate\\User";

        $expected = new AggregateType("iam.access_management.user");
        $actual = AggregateType::from_class_string($aggregate_class);

        $this->assertEquals($expected, $actual);
    }

    public function test_fails_if_multiple_uppercases_are_found()
    {
        $this->setExpectedException(TypeException::class);
        $aggregate_class = "Domain\\IAM\\Identity\\Aggregate\\User";

        $actual = AggregateType::from_class_string($aggregate_class);
    }

    public function test_converting_back_to_a_event_class()
    {
        $type = new AggregateType("iam.access_management.user");

        $expected = "Domain\\Iam\\AccessManagement\\Aggregate\\User";
        $actual = $type->to_aggregate_class();

        $this->assertEquals($expected, $actual);
    }
}

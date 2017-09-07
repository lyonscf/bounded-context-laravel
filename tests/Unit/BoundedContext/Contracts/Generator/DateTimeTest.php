<?php namespace BoundedContext\Tests\Unit\BoundedContext\Contracts\Generator;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    private $dateTime;
    private $currentTimezone;

    public function setUp()
    {
        parent::setUp();
        $this->currentTimezone = date_default_timezone_get();
        $this->dateTime = New \BoundedContext\Laravel\Generator\DateTime();
    }

    /**
     * @dataProvider  timezoneProvider
     */
    public function test_generator_with_right_timezone($timezone, $offset)
    {
        date_default_timezone_set($timezone);
        $generated = $this->dateTime->generate();
        $timezone = (new \DateTime($generated->value()))->getTimezone();

        $this->assertEquals($offset, $timezone->getName());
    }

    public function timezoneProvider()
    {
        return [
            ['America/Los_Angeles', '-07:00'],
            ['Europe/Dublin', '+01:00'],
            ['Europe/Kiev', '+03:00'],
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
        date_default_timezone_set($this->currentTimezone);
    }
}
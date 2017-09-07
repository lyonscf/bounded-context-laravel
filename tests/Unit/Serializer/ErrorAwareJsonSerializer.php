<?php namespace BoundedContext\Tests\Unit\Serializer;

use BoundedContext\Laravel\Serializer\ErrorAwareJsonSerializer;
use BoundedContext\Laravel\Serializer\JsonDeserializationException;
use BoundedContext\Laravel\Serializer\JsonSerializationException;

class ErrorAwareJsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ErrorAwareJsonSerializer
     */
    private $serializer;

    protected function setUp()
    {
        parent::setUp();

        $this->serializer = new ErrorAwareJsonSerializer();
    }

    /**
     * @dataProvider serializableProvider
     *
     * @param $actual
     * @param $expected
     */
    public function test_should_serialize_data_without_errors($actual, $expected)
    {
        $this->assertJsonStringEqualsJsonString(
            $expected,
            $this->serializer->serialize($actual)
        );
    }

    /**
     * @dataProvider deserializableProvider
     * 
     * @param $deserializable
     * @param $expected
     */
    public function test_should_deserialize_data_without_errors($deserializable, $expected)
    {
         $this->assertEquals(
             $expected,
             $this->serializer->deserialize($deserializable)
         );
    }

    public function test_should_throw_serialization_exception()
    {
        $this->setExpectedException(JsonSerializationException::class);
        $this->serializer->serialize("\xB1\x31");
    }

    public function test_should_throw_deserialization_exception()
    {
        $this->setExpectedException(JsonDeserializationException::class);
        $this->serializer->deserialize('{foo: bar}');
    }

    public function serializableProvider()
    {
        return [
            [['foo' => 'bar', 'baz' => 1], '{"foo": "bar", "baz": 1}'],
            [[], '[]'],
            [(object)[], '{}']
        ];
    }

    public function deserializableProvider()
    {
        return [
            ['{"foo": "bar", "baz": 1}', (object)['foo' => 'bar', 'baz' => 1]],
        ];
    }
}

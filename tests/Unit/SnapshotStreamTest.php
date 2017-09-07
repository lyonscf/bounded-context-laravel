<?php namespace BoundedContext\Tests\Unit;

use BoundedContext\Contracts\Event\Snapshot\Transformer;
use BoundedContext\Contracts\Sourced\Stream\Stream;
use BoundedContext\Event;
use BoundedContext\Event\AggregateType;
use BoundedContext\Event\Snapshot\Snapshot;
use BoundedContext\Schema\Schema;
use BoundedContext\Sourced\Stream\SnapshotStream;
use EventSourced\ValueObject\ValueObject\Uuid;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;
use EventSourced\ValueObject\ValueObject\DateTime;

class SnapshotStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider eventProvider
     */
    public function test_conversion($popo, $snapshot)
    {
        $stream = new SnapshotStream($this->fakeTransformer(), $this->fakeStream($popo) );

        $this->assertEquals($snapshot, $stream->current());
    }

    public function test_can_foreach()
    {
        $provider = $this->eventProvider();
        $popo = reset($provider)[0];
        $stream = new SnapshotStream($this->fakeTransformer(), $this->fakeStream($popo) );

        $count = 0;
        foreach ($stream as $snapshot) {
            $count++;
        }

        $this->assertEquals(1, $count, "Number of elements differ from expected");
    }

    public function eventProvider()
    {
        return [
            'turn POPO into Snapshot'  => [
                (object)[
                    'id' => '3e207cd8-a03c-4a53-9210-e92880b0c19a',
                    'aggregate_id' => '286c0f1a-54e5-4f38-a05d-9ba6c62461c7',
                    'command_id' => 'd675b814-a202-407b-bc41-b5365efe190d',
                    'type' => 'test.snapshot.stream.make',
                    'aggregate_type' => 'test.snapshot.stream',
                    'occurred_at' => '2017-01-01 00:00:00',
                    'version' => 1,
                    'event' => (object)['a'=>'b']
                ],
                new Snapshot(
                    new Uuid('3e207cd8-a03c-4a53-9210-e92880b0c19a'),
                    new Integer_(1),
                    new DateTime('2017-01-01 00:00:00'),
                    new Event\Type('test.snapshot.stream.make'),
                    new Uuid('d675b814-a202-407b-bc41-b5365efe190d'),
                    new Uuid('286c0f1a-54e5-4f38-a05d-9ba6c62461c7'),
                    new AggregateType('test.snapshot.stream'),
                    new Schema(['a'=>'b'])
                )
            ],
            'null is returned as null' => [null, null],
        ];
    }

    private function fakeTransformer()
    {
        $provider = $this->eventProvider();

        $popo = reset($provider)[0];
        $snapshot = reset($provider)[1];

        return new Class($popo, $snapshot) implements Transformer
        {
            private $popo;
            private $snapshot;

            public function __construct($popo, $snapshot)
            {
                $this->popo = $popo;
                $this->snapshot = $snapshot;
            }

            public function fromEvent(\BoundedContext\Contracts\Event\Event $event){}

            public function fromSchema(\BoundedContext\Contracts\Schema\Schema $schema){}

            public function toPopo($snapshot){}

            public function fromPopo($popo)
            {
                if ($popo == $this->popo) {
                    return $this->snapshot;
                }
                return null;
            }
        };
    }

    private function fakeStream($popo)
    {
        return new Class($popo) implements Stream
        {
            private $events;

            function __construct($event)
            {
                $this->events = [$event];
            }

            public function current()
            {
                return current($this->events);
            }

            public function next()
            {
                return next($this->events);
            }

            public function key()
            {
                // TODO: Implement key() method.
            }

            public function valid()
            {
                return current($this->events);
            }

            public function rewind()
            {
                // TODO: Implement rewind() method.
            }
        };
    }
}
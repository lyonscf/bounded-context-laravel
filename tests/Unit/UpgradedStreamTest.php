<?php namespace BoundedContext\Tests\Unit;

use BoundedContext\Contracts\Sourced\Stream\Stream;
use BoundedContext\Sourced\Stream\UpgradedStream;
use BoundedContext\Sourced\Stream\Upgrader;

class UpgradedStreamTest extends \PHPUnit_Framework_TestCase
{
    public function test_streaming_through_an_upgrader()
    {
        $stream = $this->fakeStream();
        $upgrader = $this->fakeUpgrader();

        $upgraded_stream = new UpgradedStream($stream, $upgrader);

        $items = [];
        foreach ($upgraded_stream as $item) {
            $items[] = $item;
        }

        $this->assertEquals([1,2,3,4,5], $items);
    }

    private function fakeStream()
    {
        return new Class() implements Stream {

            private $index = 0;
            private $items = ['a','b','c'];

            public function current()
            {
                return $this->items[$this->key()];
            }

            public function next()
            {
                $this->index++;
            }

            public function key()
            {
                return $this->index;
            }

            public function valid()
            {
                return isset($this->items[$this->key()]);
            }

            public function rewind()
            {
                $this->index = 0;
            }
        };
    }

    private function fakeUpgrader()
    {
        return new Class() implements Upgrader
        {
            public function upgrade($item): array
            {
                if ($item == 'a') {
                    return [1,2];
                }
                if ($item == 'c') {
                    return [3,4,5];
                }
                return [];
            }
        };
    }
}


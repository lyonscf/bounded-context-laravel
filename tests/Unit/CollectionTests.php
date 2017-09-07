<?php namespace BoundedContext\Tests\Unit;

use BoundedContext\Collection\Collection;

class CollectionTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    public function setup()
    {
        $this->collection = new Collection(array(
            new CollectableItem('A'),
            new CollectableItem('B'),
            new CollectableItem('C')
        ));
    }

    public function test_count()
    {
        $this->assertEquals($this->collection->count()->value(), 3);
    }

    public function test_reset()
    {
        $this->assertEquals($this->collection->count()->value(), 3);
        $this->collection->reset();
        $this->assertEquals($this->collection->count()->value(), 0);
    }

    public function test_rewind()
    {
        $this->collection->next();
        $this->collection->next();

        $this->assertEquals($this->collection->current()->value(), 'C');

        $this->collection->rewind();

        $this->assertEquals($this->collection->current()->value(), 'A');
    }

    public function test_ordering()
    {
        $this->assertEquals($this->collection->current()->value(), 'A');

        $this->assertTrue($this->collection->has_next());
        $this->collection->next();
        $this->assertEquals($this->collection->current()->value(), 'B');

        $this->assertTrue($this->collection->has_next());
        $this->collection->next();
        $this->assertEquals($this->collection->current()->value(), 'C');
        
        $this->assertFalse($this->collection->has_next());
    }

    public function test_append()
    {
        $this->collection->append(
            new CollectableItem('D')
        );

        $this->collection->next();
        $this->collection->next();

        $this->assertTrue($this->collection->has_next());
        $this->collection->next();

        $this->assertEquals($this->collection->current()->value(), 'D');
        $this->assertFalse($this->collection->has_next());
    }

    public function test_append_after_reset()
    {
        $this->collection->reset();

        $this->assertFalse($this->collection->has_next());

        $this->collection->append(
            new CollectableItem('D')
        );

        $this->assertFalse($this->collection->has_next());
        $this->assertEquals($this->collection->current()->value(), 'D');
    }

    public function test_append_after_playthrough()
    {
        $this->collection->next();
        $this->collection->next();

        $this->assertFalse($this->collection->has_next());

        $this->collection->append(
            new CollectableItem('D')
        );

        $this->assertTrue($this->collection->has_next());
        $this->collection->next();

        $this->assertFalse($this->collection->has_next());
        $this->assertEquals($this->collection->current()->value(), 'D');
    }
}

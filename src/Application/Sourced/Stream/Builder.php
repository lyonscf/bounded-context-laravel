<?php namespace BoundedContext\Sourced\Stream;

use BoundedContext\Contracts\Sourced\Stream\Factory as StreamFactory;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

class Builder implements \BoundedContext\Contracts\Sourced\Stream\Builder
{
    private $stream_factory;

    private $starting_id;
    private $limit;
    private $chunk_size;

    public function __construct(
        IdentifierGenerator $generator,
        StreamFactory $stream_factory
    )
    {
        $this->stream_factory = $stream_factory;

        $this->id = $generator->null();
        $this->limit = new Integer_(1000);
        $this->chunk_size = new Integer_(1000);
    }

    public function after(Identifier $starting_id)
    {
        $this->starting_id = $starting_id;

        return $this;
    }

    public function limit(Integer_ $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function chunk(Integer_ $size)
    {
        $this->chunk_size = $size;

        return $this;
    }

    public function stream()
    {
        return $this->stream_factory->create(
            $this->starting_id,
            $this->limit,
            $this->chunk_size
        );
    }
}

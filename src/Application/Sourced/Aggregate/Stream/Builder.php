<?php namespace BoundedContext\Sourced\Aggregate\Stream;

use BoundedContext\Contracts\Sourced\Aggregate\Stream\Factory as StreamFactory;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

class Builder implements \BoundedContext\Contracts\Sourced\Aggregate\Stream\Builder
{
    private $stream_factory;

    private $aggregate_id;
    private $aggregate_type;
    private $version;
    private $limit;
    private $chunk_size;

    public function __construct(
        IdentifierGenerator $generator,
        StreamFactory $stream_factory
    )
    {
        $this->stream_factory = $stream_factory;

        $this->aggregate_id = $generator->null();
        $this->version = new Integer_(0);
        $this->limit = new Integer_(1000);
        $this->chunk_size = new Integer_(1000);
    }

    public function after(Integer_ $version)
    {
        $this->version = $version;

        return $this;
    }

    public function ids(Identifier $aggregate_id, Identifier $aggregate_type)
    {
        $this->aggregate_id = $aggregate_id;
        $this->aggregate_type = $aggregate_type;

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
            $this->aggregate_id,
            $this->aggregate_type,
            $this->version,
            $this->limit,
            $this->chunk_size
        );
    }
}

<?php namespace BoundedContext\Snapshot;

use EventSourced\ValueObject\Contracts\ValueObject\DateTime;
use EventSourced\ValueObject\ValueObject\Type\AbstractComposite;
use EventSourced\ValueObject\ValueObject\Integer as Integer_;

abstract class AbstractSnapshot extends AbstractComposite
{
    protected $version;
    protected $occurred_at;

    public function __construct(
        Integer_ $version,
        DateTime $occurred_at
    )
    {
        $this->version = $version;
        $this->occurred_at = $occurred_at;
    }

    public function version()
    {
        return $this->version;
    }

    public function occurred_at()
    {
        return $this->occurred_at;
    }
}

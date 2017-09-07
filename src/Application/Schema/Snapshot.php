<?php namespace BoundedContext\Schema;

use BoundedContext\Contracts\Schema\Snapshot as SnapshotContract;
use EventSourced\ValueObject\ValueObject\Integer;

class Snapshot implements SnapshotContract
{
    private $schema;
    private $version;

    public function __construct(
        Schema $schema,
        Integer $version
    )
    {
        $this->schema = $schema;
        $this->version = $version;
    }

    public function schema()
    {
        return $this->schema;
    }

    public function version()
    {
        return $this->version;
    }
}

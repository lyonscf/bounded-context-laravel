<?php namespace BoundedContext\Player\Snapshot;

use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use EventSourced\ValueObject\ValueObject\Integer;

class Factory
{
    protected $identifier_generator;
    protected $datetime_generator;

    public function __construct(
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator
    )
    {
        $this->identifier_generator = $identifier_generator;
        $this->datetime_generator = $datetime_generator;
    }

    public function make(array $snapshot)
    {
        return new Snapshot(
            new ClassName($snapshot['class_name']),
            new Integer($snapshot['version']),
            new Integer($snapshot['player_version']),
            $this->datetime_generator->string($snapshot['occurred_at']),
            $this->identifier_generator->string($snapshot['last_id'])
        );
    }
}

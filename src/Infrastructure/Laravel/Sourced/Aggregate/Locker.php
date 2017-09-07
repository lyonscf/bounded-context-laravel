<?php namespace BoundedContext\Laravel\Sourced\Aggregate;

use Illuminate\Database\DatabaseManager;
use EventSourced\ValueObject\ValueObject\Uuid;
use BoundedContext\Event\AggregateType;

class Locker
{
    private $connection;

    public function __construct(DatabaseManager $database_manager)
    {
        $this->connection = $database_manager->connection();
    }

    public function lock(Uuid $id, AggregateType $type)
    {
        $this->connection->raw(
            "SELECT GET_LOCK(:lockid, 1)",
            ['lockid'=> $this->lock_id($id, $type)]
        );
    }

    public function unlock(Uuid $id, AggregateType $type)
    {
        $this->connection->raw(
            "SELECT RELEASE_LOCK(:lockid)",
            ['lockid'=> $this->lock_id($id, $type)]
        );
    }

    private function lock_id(Uuid $id, AggregateType $type)
    {
        return $id->value().'-'.$type->value();
    }
}
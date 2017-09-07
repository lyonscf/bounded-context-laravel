<?php namespace BoundedContext\Laravel\Illuminate\Log;

use BoundedContext\Contracts\Event\Snapshot\Factory;
use Illuminate\Database\DatabaseManager;
use BoundedContext\Laravel\Illuminate\BinaryString;

class Command implements \BoundedContext\Contracts\Sourced\Log\Command
{
    private $connection;
    private $binary_string_factory;
    private $snapshot_factory;
    private $table;
    
    public function __construct(
        Factory $snapshot_factory, 
        DatabaseManager $db_manager,
        BinaryString\Factory $binary_string_factory
    )
    {
        $this->snapshot_factory = $snapshot_factory;
        $this->connection = $db_manager->connection();
        $this->binary_string_factory = $binary_string_factory;
        $this->table = config('logs.command_log.table_name');
    }
    
    public function append(\BoundedContext\Contracts\Command\Command $command)
    {
        $snapshot = $this->snapshot_factory->command($command);
        $this->connection->table($this->table)->insert([
            'snapshot' => $this->encode_snapshot($snapshot)
        ]);
    }
    
    private function encode_snapshot(\BoundedContext\Contracts\Command\Snapshot\Snapshot $snapshot)
    {
        return json_encode([
            'type' => $snapshot->type()->value(),
            'version' => $snapshot->version()->value(),
            'occurred_at' => $snapshot->occurred_at()->value(),
            'command' => $snapshot->schema()->data_tree()
        ]);
    }
   
    public function reset()
    {
        $this->connection
            ->table($this->table)
            ->delete();
    }
}

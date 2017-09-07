<?php namespace BoundedContext\Laravel\Bus;

use DB;
use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Collection\Collection;

class TransactionalDispatcher extends Dispatcher
{
    public function dispatch(Command $command)
    {
        $connection = DB::connection();

        $connection->beginTransaction();

        try {
            parent::dispatch($command);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }
    }

    public function dispatch_collection(Collection $commands)
    {
        $connection = DB::connection();

        $connection->beginTransaction();

        try {
            parent::dispatch_collection($commands);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }
    }
}
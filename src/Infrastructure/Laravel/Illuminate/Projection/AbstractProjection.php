<?php namespace BoundedContext\Laravel\Illuminate\Projection;

use BoundedContext\Contracts\Projection\Queryable;
use Illuminate\Database\Query\Builder;
use DB;
use BoundedContext\Contracts\Projection\Projection;

abstract class AbstractProjection implements Projection
{
    protected $connection;
    protected $queryable;
    protected $table = 'projection_table';

    public function __construct(Queryable $queryable)
    {
        $this->connection = DB::connection();
        $this->queryable = $queryable;
    }

    public function reset()
    {
        $this->query()->delete();
    }

    /**
     * @return Builder
     */
    protected function query()
    {
        return $this->connection->table($this->table);
    }

    public function queryable()
    {
        return $this->queryable;
    }
}

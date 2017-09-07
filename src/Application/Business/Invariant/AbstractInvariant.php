<?php namespace BoundedContext\Business\Invariant;

use BoundedContext\Contracts\Business\Invariant\Exception;
use BoundedContext\Contracts\Business\Invariant\Invariant;
use BoundedContext\Contracts\Projection\Queryable;

abstract class AbstractInvariant implements Invariant
{
    protected $queryable;
    protected $assumptions;
    protected $error_message_positive;
    protected $error_message_negative;

    private $is_invariant;

    public function __construct(Queryable $queryable)
    {
        $this->queryable = $queryable;
        $this->assumptions = [];

        $this->is_invariant = true;
    }

    public function __get($name)
    {
        return $this->assumptions[$name];
    }

    public function __set($name, $value)
    {
        $this->assumptions[$name] = $value;
    }

    public function assuming(array $assumptions = [])
    {
        $this->assumptions = $assumptions;

        if(count($assumptions) > 0)
        {
            call_user_func_array(array($this, 'assumptions'), $assumptions);
        }

        return $this;
    }

    public function not()
    {
        $this->is_invariant = false;

        return $this;
    }

    public function is_satisfied()
    {
        return ($this->satisfier($this->queryable) && $this->is_invariant);
    }

    public function asserts()
    {
        if (!$this->satisfier($this->queryable) && $this->is_invariant) {
            $error_message = $this->error_message_negative ?: "Invariant broken: ".get_called_class();
            throw new Exception($error_message);
        }

        if ($this->satisfier($this->queryable) && !$this->is_invariant) {
            $error_message = $this->error_message_positive ?: "Invariant broken: ".get_called_class();
            throw new Exception($error_message);
        }
    }
}

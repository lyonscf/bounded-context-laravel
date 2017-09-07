<?php

namespace BoundedContext\Schema;

class Schema implements \BoundedContext\Contracts\Schema\Schema
{
    private $schema;

    public function __construct($schema = [])
    {
        $this->schema = $schema;
    }

    private function exists($key)
    {
        return array_key_exists($key, $this->schema);
    }

    public function __set($key, $value = null)
    {
        return $this->schema[$key] = $value;
    }

    public function __get($key)
    {
        if(!$this->exists($key))
        {
            throw new \Exception("The key [$key] does not exist in the schema.");
        }

        return $this->schema[$key];
    }

    public function add($key, \Closure $changes = null)
    {
        if($this->exists($key))
        {
            throw new \Exception("The key [$key] already exists in the schema.");
        }

        $this->schema[$key] = null;

        if(!is_null($changes))
        {
            $this->schema[$key] = $changes();
        }
    }

    public function upgrade($key, \Closure $changes)
    {
        if(!$this->exists($key))
        {
            throw new \Exception("The key [$key] does not exist in the schema.");
        }

        $this->schema[$key] = $changes($this->schema[$key]);
    }

    public function rename($old_key, $new_key)
    {
        if(!$this->exists($old_key))
        {
            throw new \Exception("The old key [$old_key] does not exist in the schema.");
        }

        if($this->exists($new_key))
        {
            throw new \Exception("The new key [$new_key] exists in the schema and cannot be overwritten.");
        }

        $this->schema[$new_key] = $this->schema[$old_key];
        unset($this->schema[$old_key]);
    }

    public function remove($key)
    {
        if(!$this->exists($key))
        {
            throw new \Exception("The key [$key] does not exist in the schema.");
        }

        unset($this->schema[$key]);
    }

    public function data_tree()
    {
        return $this->schema;
    }
}

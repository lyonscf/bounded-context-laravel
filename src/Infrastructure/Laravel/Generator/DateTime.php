<?php namespace BoundedContext\Laravel\Generator;

class DateTime implements \BoundedContext\Contracts\Generator\DateTime
{
    public function now()
    {
        $datetime = date(\DateTime::ISO8601);
        return new \EventSourced\ValueObject\ValueObject\DateTime($datetime);
    }

    public function string($datetime)
    {
        return new \EventSourced\ValueObject\ValueObject\DateTime($datetime);
    }

    public function generate()
    {
        return $this->now();
    }
}

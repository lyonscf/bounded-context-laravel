<?php namespace EventSourced\ValueObject\Contracts\ValueObject;

interface ValueObject 
{
    /*
     * Evaluates whether or not two ValueObjects are equal.
     *
     * @return boolean
     */
    public function equals(ValueObject $other);
}

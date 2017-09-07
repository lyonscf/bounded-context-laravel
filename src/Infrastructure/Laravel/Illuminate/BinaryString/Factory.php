<?php namespace BoundedContext\Laravel\Illuminate\BinaryString;

use EventSourced\ValueObject\ValueObject\Uuid;

class Factory
{
    public function uuid(Uuid $uuid)
    {
        $hex = str_replace("-", "", $uuid->value());
        return hex2bin($hex);
    }
}

<?php namespace BoundedContext\Contracts\Core;

interface Serializable
{
    /**
     * Serializes the object into an array.
     *
     * @return array|string
     */

    public function serialize();


    /**
     * Deserializes the object from an array or string.
     *
     * @param array|string $data
     * @return mixed
     */

    public static function deserialize($parameters = null);
}
<?php

namespace BoundedContext\Laravel\Serializer;

class ErrorAwareJsonSerializer
{
    public function serialize($serializable)
    {
        $result = json_encode($serializable);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonSerializationException(
                sprintf('An error occurred while encoding your data (error code %d).', json_last_error())
            );
        }

        return $result;
    }

    public function deserialize($serialized, $toArray = false)
    {
        $result = json_decode($serialized, $toArray);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDeserializationException(
                sprintf('Unable to decode JSON string (error code %d)', json_last_error())
            );
        }

        return $result;
    }
}

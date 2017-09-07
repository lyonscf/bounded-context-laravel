<?php namespace BoundedContext\Serializable;

class AbstractSerializable
{
    public function serialize()
    {
        $class = new \ReflectionClass(get_called_class());
        $parameters = $class->getConstructor()->getParameters();

        $serialized = [];
        foreach ($parameters as $index => $parameter)
        {
            $name = $parameter->getName();
            $value = $this->$name;
            $serialized[$name] = $value->value();
        }

        return $serialized;
    }

    public static function deserialize($serialised = null)
    {
        if (is_null($serialised)) {
            throw new \Exception("Having problems deserializing...");
        }

        $deserialised = [];

        $reflection = new \ReflectionClass(get_called_class());

        $parameters = $reflection->getConstructor()->getParameters();

        foreach($parameters as $parameter)
        {
            $parameter_name = $parameter->getName();
            $parameter_class = $parameter->getClass()->name;

            $deserialised[$parameter_name] = $parameter_class::deserialize(
                $serialised[$parameter_name]
            );
        }
        
        return $reflection->newInstanceArgs($deserialised);
    }
}
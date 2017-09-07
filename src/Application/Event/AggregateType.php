<?php namespace BoundedContext\Event;

use EventSourced\ValueObject\ValueObject\Type\AbstractSingleValue;
use EventSourced\ValueObject\Contracts\ValueObject\Identifier;

class AggregateType extends AbstractSingleValue implements Identifier
{
    protected function validator()
    {
        return parent::validator()->alnum("_.")->noWhitespace()->lowercase();
    }

    public static function from_class_string($class)
    {
        $type = Type::from_event_class($class);
        return new AggregateType($type->value());
    }

    public function to_aggregate_class()
    {
        $snake_case_parts = explode(".", $this->value());

        $camel_case_parts = array_map(function($str) {
            return CaseTransformer::to_camelcase($str);
        }, $snake_case_parts);

        $class_path = [
            "Domain",
            $camel_case_parts[0],
            $camel_case_parts[1],
            "Aggregate",
            $camel_case_parts[2]
        ];
        return implode("\\", $class_path);
    }

    public function is_null()
    {
        return false;
    }
}
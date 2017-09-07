<?php namespace BoundedContext\Event;

use EventSourced\ValueObject\ValueObject\Type\AbstractSingleValue;

class Type extends AbstractSingleValue
{
    protected function validator()
    {
        return parent::validator()->alnum("_.")->noWhitespace()->lowercase();
    }

    public function aggregate_type()
    {
        $parts = explode(".", $this->value());
        array_pop($parts);
        return new AggregateType(implode(".", $parts));
    }

    public static function from_event($object)
    {
        return self::from_event_class(get_class($object));
    }

    public static function from_event_class($class)
    {
        $parts = explode("\\", $class);
        unset($parts[0]);
        unset($parts[3]);
        unset($parts[5]);

        $parts = array_map(function($str){
            CaseTransformer::assert_not_two_uppercase_letters_in_a_row($str);
            return CaseTransformer::to_snakecase($str);
        }, array_values($parts));

        return new Type(implode(".", $parts));
    }

    public function to_event_class()
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
            $camel_case_parts[2],
            "Event",
            $camel_case_parts[3]
        ];
        return implode("\\", $class_path);
    }
}


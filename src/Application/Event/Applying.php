<?php namespace BoundedContext\Event;

use BoundedContext\Contracts\Event\DomainEvent;
use BoundedContext\Contracts\Projection\Projection;
use EventSourced\ValueObject\ValueObject\Integer;

trait Applying
{
    /**
     * @var Projection $projection;
     */
    protected $projection;

    /**
     * @var Integer $version
     */
    protected $version;

    private function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    private function get_handler_name(DomainEvent $event)
    {
        $namespace_path_items = array_map([$this, 'from_camel_case'], 
            $this->remove_unneccessary_path_items(explode("\\", get_class($event)))
        );

        return 'when_'.implode("_", $namespace_path_items);
    }
    
    private function remove_unneccessary_path_items($namespace_path_items)
    {
        unset($namespace_path_items[0]);
        unset($namespace_path_items[3]);
        unset($namespace_path_items[5]);
        return array_values($namespace_path_items);
    }

    protected function mutate(DomainEvent $event)
    {
        $handler_name = $this->get_handler_name($event);

        if (method_exists($this, $handler_name)) {
            $this->$handler_name(
                $this->projection,
                $event
            );
        }

        $this->version = $this->version->increment();
    }
}

<?php namespace BoundedContext\Player;

use BoundedContext\Contracts\Event\Snapshot\Snapshot;

trait Playing
{
    protected function get_handler_name(Snapshot $event)
    {
        
        $event_type_snakecase = str_replace(".", "_", $event->type()->value());
        return 'when_'.$event_type_snakecase;
    }
    
    protected function can_apply(Snapshot $event)
    {
        $function = $this->get_handler_name($event);
        return method_exists($this, $function);
    }

    protected function mutate(Snapshot $snapshot)
    {
        $handler = $this->get_handler_name($snapshot);
        $event = $snapshot->schema();
        $this->$handler($event, $snapshot);
    }
}

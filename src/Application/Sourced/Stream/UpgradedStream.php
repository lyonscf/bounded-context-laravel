<?php namespace BoundedContext\Sourced\Stream;

use BoundedContext\Contracts\Sourced\Stream\Stream;

class UpgradedStream implements Stream
{
    private $stream;
    private $upgrader;

    private $index = 0;
    private $upgraded_events = [];

    public function __construct(Stream $stream, Upgrader $upgrader)
    {
        $this->stream = $stream;
        $this->upgrader = $upgrader;

        $this->next();
    }

    public function current()
    {
        return $this->upgraded_events[$this->index];
    }

    public function next()
    {
        $this->index++;

        if (!$this->valid()) {
            $this->loadUpgradedEvents();
        }
    }

    private function loadUpgradedEvents()
    {
        $this->index = 0;
        $this->upgraded_events = [];
        while ($this->stream->valid()) {
            $event = $this->stream->current();
            $this->stream->next();

            if ($event == null) {
                return;
            }

            $this->upgraded_events = $this->upgrader->upgrade($event);
            if (isset($this->upgraded_events[$this->index])) {
                return;
            }
        }
    }

    public function valid()
    {
        return isset($this->upgraded_events[$this->index]);
    }

    /**
        The below functions are not needed for foreach
    **/

    public function key()
    {
        return $this->index;
    }

    public function rewind()
    {
        $this->index = 0;
    }
}
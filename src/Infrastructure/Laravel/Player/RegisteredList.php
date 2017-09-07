<?php namespace BoundedContext\Laravel\Player;

class RegisteredList
{
    private $players = [];

    public function append_array($players)
    {
        $this->players = array_unique(array_merge_recursive($this->players, $players), SORT_REGULAR);
    }

    public function all()
    {
        return $this->players;
    }
}
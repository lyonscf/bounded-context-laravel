<?php namespace BoundedContext\Contracts\Command;

interface Handler
{
    /**
     * Handles a Command.
     *
     * @param Command $command
     * @throws \Exception
     * @return void
     */

    public function handle(Command $command);
}

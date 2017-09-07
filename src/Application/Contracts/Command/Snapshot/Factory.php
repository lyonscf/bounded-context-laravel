<?php namespace BoundedContext\Contracts\Command\Snapshot;

use BoundedContext\Contracts\Command\Command;

interface Factory
{
    /**
     * Returns a new Snapshot from an Command.
     *
     * @param Event $command
     * @return Snapshot $snapshot
     */

    public function command(Command $command);
}

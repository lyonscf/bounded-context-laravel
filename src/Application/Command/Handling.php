<?php namespace BoundedContext\Command;

use BoundedContext\Contracts\Command\Command;

trait Handling
{
    private function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);

        $ret = $matches[0];

        foreach ($ret as &$match)
        {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    private function get_handler_name(Command $command)
    {
        $reflect = new \ReflectionClass($command);

        return 'handle_' . $this->from_camel_case(
            $reflect->getShortName()
        );
    }

    protected function mutate(Command $command)
    {
        $handler = $this->get_handler_name($command);

        if(!method_exists($this, $handler))
        {
            throw new \Exception("Command [".get_class($command)."] does not have a handler.");
        }

        $this->$handler($command);
    }
}

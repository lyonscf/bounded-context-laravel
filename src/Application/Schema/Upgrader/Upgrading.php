<?php namespace BoundedContext\Schema\Upgrader;

use BoundedContext\Contracts\Schema\Schema;
use EventSourced\ValueObject\ValueObject\Integer as Version;

trait Upgrading
{
    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var Version
     */
    private $version;

    private function get_function_name()
    {
        return 'when_version_' . $this->version->value();
    }

    private function can_upgrade()
    {
        return method_exists(
            $this,
            $this->get_function_name()
        );
    }

    public function run()
    {
        while($this->can_upgrade())
        {
            $function = $this->get_function_name();

            if (!method_exists($this, $function)) {
                throw new \Exception('An upgrade handler could not be found.');
            }

            $this->$function($this->schema);
            $this->version = $this->version->increment();
        }
    }
}

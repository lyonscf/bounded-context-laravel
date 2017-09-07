<?php namespace BoundedContext\Schema\Upgrader;

use BoundedContext\Contracts\Schema\Schema;
use BoundedContext\Contracts\Schema\Upgrader;
use EventSourced\ValueObject\ValueObject\Integer as Version;
use EventSourced\ValueObject\ValueObject\Integer;

abstract class AbstractUpgrader implements Upgrader
{
    use Upgrading;

    public function __construct(Schema $schema, Version $version)
    {
        $this->schema = $schema;
        $this->version = $version;

        if($this->version->equals(new Version(0)))
        {
            $this->schema->add('id');
        }
    }

    public function version()
    {
        return $this->version;
    }

    public function latest_version()
    {
        $class = new \ReflectionClass($this);
        $methods = $class->getMethods(\ReflectionMethod::IS_PROTECTED);

        $version = new Integer(0);

        foreach($methods as $method)
        {
            if (preg_match('#^when_version_#i', $method->name) === 1) {
                $version = $version->increment();
            }
        }

        return $version;
    }

    public function schema()
    {
        return $this->schema;
    }
}

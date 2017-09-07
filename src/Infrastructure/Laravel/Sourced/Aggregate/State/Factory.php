<?php namespace BoundedContext\Laravel\Sourced\Aggregate\State;

use BoundedContext\Contracts\Command\Command;
use BoundedContext\Contracts\Sourced\Aggregate\State\Snapshot\Snapshot;
use EventSourced\ValueObject\Deserializer\Deserializer;
use BoundedContext\Laravel\Sourced\Aggregate\Locker;

class Factory implements \BoundedContext\Contracts\Sourced\Aggregate\State\Factory
{
    protected $deserializer;

    protected $state_class;
    protected $state_projection_class;
    protected $locker;

    public function __construct(Deserializer $deserializer, Locker $locker)
    {
        $this->deserializer = $deserializer;
    }

    public function with(Command $command)
    {
        $command_class = get_class($command);

        $aggregate_prefix = substr($command_class, 0, strpos($command_class, "Command"));

        $this->state_class = $aggregate_prefix . 'Projector';
        $this->state_projection_class = $aggregate_prefix . 'Projection';

        return $this;
    }

    public function aggregateClass($class)
    {
        $aggregate_prefix = substr($class, 0, strlen($class) - strlen("Aggregate"));

        $this->state_class = $aggregate_prefix . 'Projector';
        $this->state_projection_class = $aggregate_prefix . 'Projection';

        return $this;
    }

    private function parse_doc_comment($doc_comment)
    {
        $clean_doc_comment = trim(preg_replace('/\r?\n *\* *\//', '', $doc_comment));
        $comments = [];
        preg_match_all('/@([a-z]+)\s+(.*?)\s*(?=$|@[a-z]+\s)/s', $clean_doc_comment, $comments);
                
        $comments[2] = array_map(function($comment) {
            return trim(str_replace("*/", "", $comment));
        }, $comments[2]);
        
        return array_combine($comments[1], $comments[2]);
    }

    public function snapshot(Snapshot $snapshot)
    {
        $projection_class = new \ReflectionClass($this->state_projection_class);
        $projection = $projection_class->newInstanceArgs();

        $schema = $snapshot->schema();

        if($schema->data_tree() == []) {
            return $this->create_state($snapshot, $projection);
        }

        $projection_object = new \ReflectionObject($projection);

        $properties = $projection_object->getProperties();
        foreach ($properties as $property) {
            
            $comment = $this->parse_doc_comment($property->getDocComment());
            $property_class_name = $comment['var'];
            $property_name = $property->name;

            $projection->$property_name = $this->deserializer->deserialize(
                $property_class_name,
                $snapshot->schema()->$property_name
            );
        }
        $state = $this->create_state($snapshot, $projection);

        $this->locker->unlock($state->aggregate_id(), $state->aggregate_type());

        return $state;
    }
    
    private function create_state($snapshot, $projection) 
    {
        return new $this->state_class(
            $snapshot->aggregate_id(),
            $snapshot->aggregate_type(),
            $snapshot->version(),
            $projection
        );
    }
}

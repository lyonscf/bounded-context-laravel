<?php namespace BoundedContext\Player;

use BoundedContext\Contracts\Generator\Identifier as IdentifierGenerator;
use BoundedContext\Contracts\Generator\DateTime as DateTimeGenerator;
use BoundedContext\Contracts\Player\Player;
use BoundedContext\Contracts\Sourced\Log\Event as EventLog;
use BoundedContext\Contracts\Player\Snapshot\Snapshot;
use BoundedContext\Event\Snapshot\Snapshot as EventSnapshot;
use EventSourced\ValueObject\ValueObject\Integer;

abstract class AbstractPlayer implements Player
{
    use Playing;

    protected $identifier_generator;
    protected $datetime_generator;
    protected $log;
    protected $snapshot;

    public function __construct(
        IdentifierGenerator $identifier_generator,
        DateTimeGenerator $datetime_generator,
        EventLog $event_log,
        Snapshot $snapshot
    )
    {
        $this->identifier_generator = $identifier_generator;
        $this->datetime_generator = $datetime_generator;
        $this->log = $event_log;
        $this->snapshot = $snapshot;
    }

    public function reset()
    {
        $this->snapshot = $this->snapshot->reset(
            $this->identifier_generator,
            $this->datetime_generator,
            new Integer($this->version())
        );
    }

    public function play($limit = 1000)
    {
        if ($this->snapshot()->playerVersion()->value() != static::version()) {
            throw new \Exception("The snapshot (".$this->snapshot()->version()->value().") and projector (".$this->version().") for '".get_called_class()."' have different versions, playing would cause potential data corruption. This exception only occurs if the players are used incorrectly.");
        }

        $snapshot_stream = $this->log
            ->builder()
            ->after($this->snapshot()->last_id())
            ->limit(new Integer($limit))
            ->stream();

        foreach($snapshot_stream as $snapshot) {
            $this->apply($snapshot);
        }
    }

    protected function apply(EventSnapshot $event_snapshot)
    {
        if (!$this->can_apply($event_snapshot)) {
            $this->snapshot = $this->snapshot->skip(
                $event_snapshot->id(),
                $this->datetime_generator
            );

            return true;
        }

        $this->mutate($event_snapshot);

        $this->snapshot = $this->snapshot->take(
            $event_snapshot->id(),
            $this->datetime_generator
        );
    }

    public function snapshot()
    {
        return $this->snapshot;
    }

    /**
     * @return int
     */
    public static function version()
    {
        $player_class = get_called_class();

        $path = explode("\\", $player_class);

        $path[count($path)-1] = "Version";

        $version_class = implode("\\", $path);

        if (!class_exists($version_class)) {
            return 1;
        }

        return $version_class::VALUE;
    }
}

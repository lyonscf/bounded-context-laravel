<?php namespace BoundedContext\Laravel\Providers;

use BoundedContext\Laravel\Player\RegisteredList;

class Registerer
{
    private $players;

    public function __construct(RegisteredList $players)
    {
        $this->players = $players;
    }

    public function register_players($players)
    {
        $this->players->append_array($players);
    }

    public function register_projection_implementations($app, $projection_types)
    {
        if (is_null($projection_types)) {
            return;
        }

        foreach ($projection_types as $projection_type) {
            foreach ($projection_type as $projection => $implemented_projection) {
                $queryable =
                    '\\' .
                    chop($projection, 'Projection') .
                    "Queryable";

                $implemented_queryable =
                    chop($implemented_projection, 'Projection') .
                    "Queryable";

                $app->when($implemented_projection)
                    ->needs(\BoundedContext\Contracts\Projection\Queryable::class)
                    ->give($implemented_queryable);

                $app->singleton($projection, $implemented_projection);
                $app->singleton($queryable, $implemented_queryable);
            }
        }
    }
}
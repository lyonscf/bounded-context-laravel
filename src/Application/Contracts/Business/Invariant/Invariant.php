<?php namespace BoundedContext\Contracts\Business\Invariant;

use BoundedContext\Contracts\Business\Invariant\Exception;
use EventSourced\ValueObject\Contracts\ValueObject;

interface Invariant
{
    /**
     * Configures the Invariant to be a not Invariant.
     *
     * @return Invariant
     */

    public function not();

    /**
     * Adds assumptions to the Invariant.
     *
     * @param array $assumptions
     * @return Invariant
     */

    public function assuming(array $assumptions = []);

    /**
     * Returns whether or not the invariant has been satisfied.
     *
     * @return boolean
     */

    public function is_satisfied();

    /**
     * Asserts the invariant.
     *
     * @throws Exception
     * @return void
     */

    public function asserts();
}

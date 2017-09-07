<?php namespace BoundedContext\Contracts\Generator;

use EventSourced\ValueObject\Contracts\ValueObject\Identifier as IdentifierVO;

interface Identifier extends ValueObject
{
    /**
     * Generates a new random Identifier.
     *
     * @return IdentifierVO
     */
    public function generate();

    /**
     * Generates a null Identifier.
     *
     * @return IdentifierVO
     */
    public function null();

    /**
     * Generates a new Identifier from a string.
     *
     * @param string $identifier
     * @return IdentifierVO
     */
    public function string($identifier);
}

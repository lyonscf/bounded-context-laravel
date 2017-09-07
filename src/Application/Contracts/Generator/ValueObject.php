<?php namespace BoundedContext\Contracts\Generator;

use EventSourced\ValueObject\Contracts\ValueObject as VO;

interface ValueObject
{
    /**
     * Generates a new VO.
     *
     * @return VO
     */

    public function generate();

}

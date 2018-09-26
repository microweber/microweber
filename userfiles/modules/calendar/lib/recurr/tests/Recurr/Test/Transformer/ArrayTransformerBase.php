<?php

namespace Recurr\Test\Transformer;

use Recurr\Transformer\ArrayTransformer;

class ArrayTransformerBase extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayTransformer */
    protected $transformer;

    protected $timezone = 'America/New_York';

    public function setUp()
    {
        $this->transformer = new ArrayTransformer();
    }
}

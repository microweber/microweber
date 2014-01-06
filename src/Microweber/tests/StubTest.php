<?php


class Stub {}
class StubTest extends PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $this->getMock('Stub');
    }
}
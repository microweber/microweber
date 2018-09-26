<?php

namespace Recurr\Test;

use Recurr\Recurrence;
use Recurr\RecurrenceCollection;

class RecurrenceCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var RecurrenceCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new RecurrenceCollection(
            array(
                new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
                new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
                new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
                new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
                new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
            )
        );
    }

    public function testStartsBetween()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
        );

        $after  = new \DateTime('2014-01-01');
        $before = new \DateTime('2014-05-01');
        $result = $this->collection->startsBetween($after, $before);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testStartsBetweenInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $after  = new \DateTime('2014-01-01');
        $before = new \DateTime('2014-05-01');
        $result = $this->collection->startsBetween($after, $before, true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testStartsBefore()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
        );

        $result = $this->collection->startsBefore(new \DateTime('2014-03-01'));

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testStartsBeforeInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
        );

        $result = $this->collection->startsBefore(new \DateTime('2014-03-01'), true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testStartsAfter()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $result = $this->collection->startsAfter(new \DateTime('2014-03-01'));

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testStartsAfterInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $result = $this->collection->startsAfter(new \DateTime('2014-03-01'), true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsBetween()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
        );

        $after  = new \DateTime('2014-01-15');
        $before = new \DateTime('2014-05-15');
        $result = $this->collection->endsBetween($after, $before);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsBetweenInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $after  = new \DateTime('2014-01-15');
        $before = new \DateTime('2014-05-15');
        $result = $this->collection->endsBetween($after, $before, true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsBefore()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
        );

        $result = $this->collection->endsBefore(new \DateTime('2014-03-15'));

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsBeforeInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-01-01'), new \DateTime('2014-01-15')),
            new Recurrence(new \DateTime('2014-02-01'), new \DateTime('2014-02-15')),
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
        );

        $result = $this->collection->endsBefore(new \DateTime('2014-03-15'), true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsAfter()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $result = $this->collection->endsAfter(new \DateTime('2014-03-15'));

        $this->assertEquals($expected, array_values($result->toArray()));
    }

    public function testEndsAfterInc()
    {
        $expected = array(
            new Recurrence(new \DateTime('2014-03-01'), new \DateTime('2014-03-15')),
            new Recurrence(new \DateTime('2014-04-01'), new \DateTime('2014-04-15')),
            new Recurrence(new \DateTime('2014-05-01'), new \DateTime('2014-05-15')),
        );

        $result = $this->collection->endsAfter(new \DateTime('2014-03-15'), true);

        $this->assertEquals($expected, array_values($result->toArray()));
    }
}

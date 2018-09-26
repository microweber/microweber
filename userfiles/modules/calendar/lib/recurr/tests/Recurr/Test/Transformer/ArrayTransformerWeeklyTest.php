<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerWeeklyTest extends ArrayTransformerBase
{
    public function testWeekly()
    {
        $timezone = 'America/New_York';
        $timezoneObj = new \DateTimeZone($timezone);

        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;INTERVAL=1',
            new \DateTime('2013-06-13 00:00:00', $timezoneObj),
            null,
            $timezone
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-13 00:00:00', $timezoneObj), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-20 00:00:00', $timezoneObj), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-27 00:00:00', $timezoneObj), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-07-04 00:00:00', $timezoneObj), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-07-11 00:00:00', $timezoneObj), $computed[4]->getStart());
    }

    public function testWeeklyInterval()
    {
        $timezone = 'America/New_York';
        $timezoneObj = new \DateTimeZone($timezone);

        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;INTERVAL=2',
            new \DateTime('2013-12-19 00:00:00', $timezoneObj),
            null,
            $timezone
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-12-19 00:00:00', $timezoneObj), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-01-02 00:00:00', $timezoneObj), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-01-16 00:00:00', $timezoneObj), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-01-30 00:00:00', $timezoneObj), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-02-13 00:00:00', $timezoneObj), $computed[4]->getStart());
    }

    public function testWeeklyIntervalLeapYear()
    {
        $timezone = 'America/New_York';
        $timezoneObj = new \DateTimeZone($timezone);

        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=7;INTERVAL=2',
            new \DateTime('2015-12-21 00:00:00', $timezoneObj),
            null,
            $timezone
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(7, $computed);
        $this->assertEquals(new \DateTime('2015-12-21 00:00:00', $timezoneObj), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-01-04 00:00:00', $timezoneObj), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-01-18 00:00:00', $timezoneObj), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-02-01 00:00:00', $timezoneObj), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-02-15 00:00:00', $timezoneObj), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 00:00:00', $timezoneObj), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('2016-03-14 00:00:00', $timezoneObj), $computed[6]->getStart());
    }

    public function testWeeklyIntervalTouchingJan1()
    {
        $timezone = 'America/New_York';
        $timezoneObj = new \DateTimeZone($timezone);

        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=3;INTERVAL=2',
            new \DateTime('2013-12-18 00:00:00', $timezoneObj),
            null,
            $timezone
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2013-12-18 00:00:00', $timezoneObj), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-01-01 00:00:00', $timezoneObj), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-01-15 00:00:00', $timezoneObj), $computed[2]->getStart());
    }
}

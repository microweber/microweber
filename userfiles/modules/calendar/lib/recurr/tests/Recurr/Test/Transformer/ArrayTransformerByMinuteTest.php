<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByMinuteTest extends ArrayTransformerBase
{
    public function testByMinuteMinutely()
    {
        $rule = new Rule(
            'FREQ=MINUTELY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 17:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 17:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 18:14:00'), $computed[4]->getStart());
    }

    public function testByMinuteHourly()
    {
        $rule = new Rule(
            'FREQ=HOURLY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 17:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 17:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 18:14:00'), $computed[4]->getStart());
    }

    public function testByMinuteDaily()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-13 16:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-13 16:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-14 16:14:00'), $computed[4]->getStart());
    }

    public function testByMinuteWeekly()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-19 16:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-19 16:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-26 16:14:00'), $computed[4]->getStart());
    }

    public function testByMinuteMonthly()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-07-12 16:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-07-12 16:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-08-12 16:14:00'), $computed[4]->getStart());
    }

    public function testByMinuteYearly()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYMINUTE=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-12 16:14:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-12 16:15:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-06-12 16:14:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-06-12 16:15:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2015-06-12 16:14:00'), $computed[4]->getStart());
    }
}

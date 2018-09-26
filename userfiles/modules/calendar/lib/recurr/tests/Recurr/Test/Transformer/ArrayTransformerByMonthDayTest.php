<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByMonthDayTest extends ArrayTransformerBase
{
    public function testByMonthDayMonthly()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=28,29,30',
            new \DateTime('2013-01-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-03-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-03-29'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-03-30'), $computed[4]->getStart());
    }

    public function testByMonthDayMonthlyNegative()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=-10',
            new \DateTime('2013-06-07')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-21'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-07-22'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-08-22'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-09-21'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-10-22'), $computed[4]->getStart());
    }

    public function testByMonthDayMonthlyPositiveAndNegative()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=15,-1',
            new \DateTime('2013-10-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-10-15'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-10-31'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-11-15'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-11-30'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-12-15'), $computed[4]->getStart());
    }

    public function testByMonthDayMonthlyLeapYear()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=28,29,30',
            new \DateTime('2016-01-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-03-28'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-03-29'), $computed[4]->getStart());
    }

    public function testByMonthDayYearlyWithByMonthAndByDay()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=3;INTERVAL=4;BYMONTH=11;BYDAY=TU;BYMONTHDAY=2,3,4,5,6,7,8',
            new \DateTime('1996-11-05 09:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('1996-11-05 09:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2000-11-07 09:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2004-11-02 09:00:00'), $computed[2]->getStart());
    }
}

<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByWeekDayTest extends ArrayTransformerBase
{
    public function testByWeekDay()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;BYDAY=MO,TU',
            new \DateTime('2013-01-28')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-01-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-01-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-02-04'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-02-05'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-02-11'), $computed[4]->getStart());
    }

    public function testByWeekDayWeeklyWithInterval2()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;INTERVAL=2;BYDAY=TU,WE;WKST=WE',
            new \DateTime('2013-01-23')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-01-23'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-01-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-02-06'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-02-12'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-02-20'), $computed[4]->getStart());

        // --------------------------------

        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;INTERVAL=2;BYDAY=TU,WE;WKST=SA',
            new \DateTime('2013-01-24')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-02-05'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-06'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-02-19'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-02-20'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-03-05'), $computed[4]->getStart());
    }

    public function testByWeekDayMonthlyRelativeFromStart()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYDAY=+1MO',
            new \DateTime('2013-06-04')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-07-01'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-08-05'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-09-02'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-10-07'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-11-04'), $computed[4]->getStart());
    }

    public function testByWeekDayMonthlyRelativeFromEnd()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYDAY=-1MO',
            new \DateTime('2013-06-04')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-24'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-07-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-08-26'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-09-30'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-10-28'), $computed[4]->getStart());

        // -----------------------

        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYDAY=-5MO',
            new \DateTime('2013-06-05')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-07-01'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-09-02'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-12-02'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-03-03'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-06-02'), $computed[4]->getStart());
    }

    public function testByWeekDayMonthlyRelativeFromStartAndEnd()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=10;BYDAY=-1MO,3FR',
            new \DateTime('2013-06-04')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(10, $computed);
        $this->assertEquals(new \DateTime('2013-06-21'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-24'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-07-19'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-07-29'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-08-16'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2013-08-26'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('2013-09-20'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('2013-09-30'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('2013-10-18'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('2013-10-28'), $computed[9]->getStart());
    }

    public function testByWeekDayMonthlyWithSundayStartOfYear()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=6;BYDAY=MO',
            new \DateTime('2017-01-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(6, $computed);
        $this->assertEquals(new \DateTime('2017-01-02'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2017-01-09'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2017-01-16'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-01-23'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2017-01-30'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2017-02-06'), $computed[5]->getStart());

        // -----------------------------------

        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=6;BYDAY=-3MO,3FR',
            new \DateTime('2017-01-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(6, $computed);
        $this->assertEquals(new \DateTime('2017-01-16'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2017-01-20'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2017-02-13'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-02-17'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2017-03-13'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2017-03-17'), $computed[5]->getStart());
    }

    public function testByWeekDayYearlyRelativeFromStart()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYDAY=+1MO',
            new \DateTime('2013-06-04')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2014-01-06'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-01-05'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-01-04'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-01-02'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2018-01-01'), $computed[4]->getStart());
    }

    public function testByWeekDayYearlyRelativeFromEnd()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYDAY=-1MO',
            new \DateTime('2013-06-04')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-12-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-12-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-12-26'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2017-12-25'), $computed[4]->getStart());
    }
}
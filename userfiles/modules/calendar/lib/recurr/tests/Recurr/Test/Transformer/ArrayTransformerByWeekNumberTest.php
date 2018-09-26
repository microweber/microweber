<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByWeekNumberTest extends ArrayTransformerBase
{
    public function testByWeekNumber()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYWEEKNO=22;WKST=SU',
            new \DateTime('2013-05-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-05-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-05-31'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-01'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-05-25'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-05-26'), $computed[4]->getStart());
    }

    public function testByWeekNumberNegative()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYWEEKNO=-44;WKST=TH',
            new \DateTime('2013-05-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2014-02-27'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-01'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-03-02'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-03-03'), $computed[4]->getStart());
    }

    public function testByWeekNumberWeek53()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYWEEKNO=53;WKST=MO',
            new \DateTime('2013-05-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2015-12-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-30'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2015-12-31'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-01-01'), $computed[4]->getStart());
    }

    public function testByWeekNumberWeek53Negative()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYWEEKNO=-53;WKST=MO',
            new \DateTime('2008-05-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2009-01-01'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2009-01-02'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2009-01-03'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2009-01-04'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2015-01-01'), $computed[4]->getStart());
    }
}

<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByMonthTest extends ArrayTransformerBase
{
    public function testByMonth()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=4;BYMONTH=2,3',
            new \DateTime('2013-02-26')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2013-02-26'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-27'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-02-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-03-01'), $computed[3]->getStart());
    }

    public function testByMonthLeapYear()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=4;BYMONTH=2,3',
            new \DateTime('2016-02-27')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-02-27'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01'), $computed[3]->getStart());
    }
}

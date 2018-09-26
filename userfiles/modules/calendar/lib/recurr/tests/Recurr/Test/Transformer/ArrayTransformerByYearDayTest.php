<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByYearDayTest extends ArrayTransformerBase
{
    public function testByYearDay()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=4;BYYEARDAY=125',
            new \DateTime('2013-01-02')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2013-05-05'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-05-05'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-05-05'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-05-04'), $computed[3]->getStart());
    }

    public function testByYearDayNegative()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=4;BYYEARDAY=-307',
            new \DateTime('2013-06-07')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-02-28'), $computed[3]->getStart());
    }
}

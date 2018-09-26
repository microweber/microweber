<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerMinutesTest extends ArrayTransformerBase
{
    public function testMinutely()
    {
        $rule = new Rule(
            'FREQ=MINUTELY;COUNT=5;',
            new \DateTime('2016-02-29 23:58:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29 23:58:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:59:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01 00:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01 00:01:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01 00:02:00'), $computed[4]->getStart());
    }

    public function testMinutelyInterval()
    {
        $rule = new Rule(
            'FREQ=MINUTELY;COUNT=5;INTERVAL=58;',
            new \DateTime('2013-02-28 23:58:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-02-28 23:58:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-29 00:56:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-02-29 01:54:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-02-29 02:52:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-02-29 03:50:00'), $computed[4]->getStart());
    }
}

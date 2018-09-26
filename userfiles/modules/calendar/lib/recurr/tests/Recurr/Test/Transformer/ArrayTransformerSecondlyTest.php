<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerSecondlyTest extends ArrayTransformerBase
{
    public function testSecondly()
    {
        $rule = new Rule(
            'FREQ=SECONDLY;COUNT=5;',
            new \DateTime('2016-02-29 23:58:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29 23:58:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:58:01'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:58:02'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:58:03'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:58:04'), $computed[4]->getStart());
    }

    public function testSecondlyInterval()
    {
        $rule = new Rule(
            'FREQ=SECONDLY;COUNT=5;INTERVAL=58;',
            new \DateTime('2016-02-29 23:58:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29 23:58:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:58:58'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 23:59:56'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01 00:00:54'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01 00:01:52'), $computed[4]->getStart());
    }
}

<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerDtendTest extends ArrayTransformerBase
{
    public function testDtend()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=3;DTEND=20140316T040000',
            new \DateTime('2014-03-14 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2014-03-14 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-03-16 04:00:00'), $computed[0]->getEnd());
        $this->assertEquals(new \DateTime('2014-04-14 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-04-16 04:00:00'), $computed[1]->getEnd());
        $this->assertEquals(new \DateTime('2014-05-14 04:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-05-16 04:00:00'), $computed[2]->getEnd());
    }

    public function testDtendWithoutLastDayOfMonthFix()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;DTEND=20140201T040000',
            new \DateTime('2014-01-31 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2014-01-31 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-01 04:00:00'), $computed[0]->getEnd());
        $this->assertEquals(new \DateTime('2014-03-31 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-04-01 04:00:00'), $computed[1]->getEnd());
        $this->assertEquals(new \DateTime('2014-05-31 04:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-06-01 04:00:00'), $computed[2]->getEnd());
        $this->assertEquals(new \DateTime('2014-07-31 04:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-08-01 04:00:00'), $computed[3]->getEnd());
        $this->assertEquals(new \DateTime('2014-08-31 04:00:00'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2014-09-01 04:00:00'), $computed[4]->getEnd());
    }

    public function testDtendWithLastDayOfMonthFix()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;DTEND=20140201T040000',
            new \DateTime('2014-01-31 04:00:00')
        );

        $config = new ArrayTransformerConfig();
        $config->enableLastDayOfMonthFix();
        $this->transformer->setConfig($config);

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2014-01-31 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-01 04:00:00'), $computed[0]->getEnd());
        $this->assertEquals(new \DateTime('2014-02-28 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-01 04:00:00'), $computed[1]->getEnd());
        $this->assertEquals(new \DateTime('2014-03-31 04:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-01 04:00:00'), $computed[2]->getEnd());
        $this->assertEquals(new \DateTime('2014-04-30 04:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-05-01 04:00:00'), $computed[3]->getEnd());
        $this->assertEquals(new \DateTime('2014-05-31 04:00:00'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2014-06-01 04:00:00'), $computed[4]->getEnd());
    }
}

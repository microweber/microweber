<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerYearlyTest extends ArrayTransformerBase
{
    public function testYearly()
    {
        $rule = new Rule('FREQ=YEARLY;COUNT=3;INTERVAL=1', new \DateTime('2013-06-13 00:00:00'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2013-06-13'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-13'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-06-13'), $computed[2]->getStart());
    }

    public function testYearlyByMonth()
    {
        $rule = new Rule('FREQ=YEARLY;BYMONTH=9;COUNT=3', new \DateTime('2017-07-31 00:00:00'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(0, $computed);
    }

    public function testLeapYear()
    {
        $rule     = new Rule('FREQ=YEARLY;COUNT=5;INTERVAL=1', new \DateTime('2096-02-29'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2096-02-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2104-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2108-02-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2112-02-29'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2116-02-29'), $computed[4]->getStart());
    }

    public function testLastDayOfMonthFixLeapYear()
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $this->transformer->setConfig($transformerConfig);

        $rule     = new Rule('FREQ=YEARLY;COUNT=5;INTERVAL=1', new \DateTime('2016-02-29'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2017-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2018-02-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2019-02-28'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2020-02-29'), $computed[4]->getStart());
    }
}

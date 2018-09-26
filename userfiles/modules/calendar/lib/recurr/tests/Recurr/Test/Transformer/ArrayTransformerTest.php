<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerTest extends ArrayTransformerBase
{
    public function testVirtualLimitWithCountLimit()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=30',
            new \DateTime('2014-03-16 04:00:00')
        );

        $config = new ArrayTransformerConfig();
        $this->transformer->setConfig($config);

        $config->setVirtualLimit(5);
        $computed = $this->transformer->transform($rule);
        $this->assertCount(5, $computed);

        $config->setVirtualLimit(40);
        $computed = $this->transformer->transform($rule);
        $this->assertCount(30, $computed);
    }

    public function testVirtualLimitWithoutCountLimit()
    {
        $rule = new Rule(
            'FREQ=YEARLY',
            new \DateTime('2014-03-16 04:00:00')
        );

        $config = new ArrayTransformerConfig();
        $this->transformer->setConfig($config);

        $config->setVirtualLimit(5);
        $computed = $this->transformer->transform($rule);
        $this->assertCount(5, $computed);

        $config->setVirtualLimit(10);
        $computed = $this->transformer->transform($rule);
        $this->assertCount(10, $computed);
    }

    public function testUntil()
    {
        $rule = new Rule(
            'FREQ=YEARLY;UNTIL=20160316T040000',
            new \DateTime('2014-03-16 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2014-03-16 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-03-16 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-16 04:00:00'), $computed[2]->getStart());
    }

    public function testRfc2445Example()
    {
        $rule = new Rule(
            'FREQ=YEARLY;INTERVAL=2;BYMONTH=1;BYDAY=SU;BYHOUR=8,9;COUNT=30',
            new \DateTime('1997-01-05 08:30:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(30, $computed);
        $this->assertEquals(new \DateTime('1997-01-05 08:30:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('1997-01-05 09:30:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('1997-01-12 08:30:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('1997-01-12 09:30:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('1997-01-19 08:30:00'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('1997-01-19 09:30:00'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('1997-01-26 08:30:00'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('1997-01-26 09:30:00'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('1999-01-03 08:30:00'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('1999-01-03 09:30:00'), $computed[9]->getStart());
        $this->assertEquals(new \DateTime('1999-01-10 08:30:00'), $computed[10]->getStart());
        $this->assertEquals(new \DateTime('1999-01-10 09:30:00'), $computed[11]->getStart());
        $this->assertEquals(new \DateTime('1999-01-17 08:30:00'), $computed[12]->getStart());
        $this->assertEquals(new \DateTime('1999-01-17 09:30:00'), $computed[13]->getStart());
        $this->assertEquals(new \DateTime('1999-01-24 08:30:00'), $computed[14]->getStart());
        $this->assertEquals(new \DateTime('1999-01-24 09:30:00'), $computed[15]->getStart());
        $this->assertEquals(new \DateTime('1999-01-31 08:30:00'), $computed[16]->getStart());
        $this->assertEquals(new \DateTime('1999-01-31 09:30:00'), $computed[17]->getStart());
        $this->assertEquals(new \DateTime('2001-01-07 08:30:00'), $computed[18]->getStart());
        $this->assertEquals(new \DateTime('2001-01-07 09:30:00'), $computed[19]->getStart());
        $this->assertEquals(new \DateTime('2001-01-14 08:30:00'), $computed[20]->getStart());
        $this->assertEquals(new \DateTime('2001-01-14 09:30:00'), $computed[21]->getStart());
        $this->assertEquals(new \DateTime('2001-01-21 08:30:00'), $computed[22]->getStart());
        $this->assertEquals(new \DateTime('2001-01-21 09:30:00'), $computed[23]->getStart());
        $this->assertEquals(new \DateTime('2001-01-28 08:30:00'), $computed[24]->getStart());
        $this->assertEquals(new \DateTime('2001-01-28 09:30:00'), $computed[25]->getStart());
        $this->assertEquals(new \DateTime('2003-01-05 08:30:00'), $computed[26]->getStart());
        $this->assertEquals(new \DateTime('2003-01-05 09:30:00'), $computed[27]->getStart());
        $this->assertEquals(new \DateTime('2003-01-12 08:30:00'), $computed[28]->getStart());
        $this->assertEquals(new \DateTime('2003-01-12 09:30:00'), $computed[29]->getStart());
    }

    public function testIndex()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=10',
            new \DateTime('2000-01-01 09:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(10, $computed);
        $this->assertEquals(1, $computed[0]->getIndex());
        $this->assertEquals(10, $computed[9]->getIndex());
    }
}

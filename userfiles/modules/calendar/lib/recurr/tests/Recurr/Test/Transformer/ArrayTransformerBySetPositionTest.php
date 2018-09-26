<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerBySetPositionTest extends ArrayTransformerBase
{
    public function testBySetPosition()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;BYSETPOS=-1;BYDAY=MO,TU,WE,TH,FR;COUNT=5',
            new \DateTime('2013-01-24')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-03-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-04-30'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-05-31'), $computed[4]->getStart());

        // --------------------------------------

        $rule = new Rule(
            'FREQ=MONTHLY;BYSETPOS=-1;BYDAY=MO,TU,WE,TH,FR;COUNT=5',
            new \DateTime('2016-01-24')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-29'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-05-31'), $computed[4]->getStart());

        // --------------------------------------

        $rule = new Rule(
            'FREQ=MONTHLY;BYSETPOS=1,-1;BYDAY=MO,TU,WE,TH,FR;COUNT=5',
            new \DateTime('2016-01-24')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-01'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-03-01'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-03-31'), $computed[4]->getStart());

        // --------------------------------------

        $rule = new Rule(
            'FREQ=MONTHLY;BYSETPOS=5;BYDAY=SU;UNTIL=2018-08-01',
            new \DateTime('2017-04-30')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(6, $computed);
        $this->assertEquals(new \DateTime('2017-04-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2017-07-30'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2017-10-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-12-31'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2018-04-29'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2018-07-29'), $computed[5]->getStart());
    }

    public function testBySetPositionVirtualLimit()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;BYSETPOS=-1;BYDAY=MO,TU,WE,TH,FR',
            new \DateTime('2013-01-24')
        );

        $config = new ArrayTransformerConfig();
        $config->setVirtualLimit(5);
        $this->transformer->setConfig($config);

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-03-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-04-30'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-05-31'), $computed[4]->getStart());
    }

    public function testBySetPositionWithInterval()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;INTERVAL=2;BYDAY=MO;BYSETPOS=2;COUNT=10',
            new \DateTime('2013-10-09')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(10, $computed);
        $this->assertEquals(new \DateTime('2013-10-14'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-12-09'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-02-10'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-14'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-06-09'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2014-08-11'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('2014-10-13'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('2014-12-08'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('2015-02-09'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('2015-04-13'), $computed[9]->getStart());
    }

    public function testNoIndexErrorOnEmptySet()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;UNTIL=20180102T120000Z;BYDAY=TH,FR;BYMONTH=11,12;BYSETPOS=-1,-2',
            new \DateTime('2017-07-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(18, $computed);
    }

    public function testBySetPositionHandlesMultipleNegatives()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;UNTIL=20180102T120000Z;BYDAY=TH,FR;BYMONTH=11,12;BYSETPOS=-1,-2',
            new \DateTime('2017-11-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(18, $computed);
        $this->assertEquals(new \DateTime('2017-11-02'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2017-11-03'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2017-11-09'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2017-11-10'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-11-16'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('2017-11-17'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2017-11-23'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('2017-11-24'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('2017-11-30'), $computed[9]->getStart());
        $this->assertEquals(new \DateTime('2017-12-01'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('2017-12-07'), $computed[11]->getStart());
        $this->assertEquals(new \DateTime('2017-12-08'), $computed[10]->getStart());
        $this->assertEquals(new \DateTime('2017-12-14'), $computed[13]->getStart());
        $this->assertEquals(new \DateTime('2017-12-15'), $computed[12]->getStart());
        $this->assertEquals(new \DateTime('2017-12-21'), $computed[15]->getStart());
        $this->assertEquals(new \DateTime('2017-12-22'), $computed[14]->getStart());
        $this->assertEquals(new \DateTime('2017-12-28'), $computed[17]->getStart());
        $this->assertEquals(new \DateTime('2017-12-29'), $computed[16]->getStart());
    }
}

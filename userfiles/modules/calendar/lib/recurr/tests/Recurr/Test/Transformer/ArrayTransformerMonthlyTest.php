<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerMonthlyTest extends ArrayTransformerBase
{
    public function testMonthly()
    {
        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-28'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-28'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-29'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-03-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-04-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-05-29'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-30'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-03-30'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-04-30'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-05-30'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-31'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-03-31'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-05-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-07-31'), $computed[3]->getStart());
    }

    public function testMonthlyOnLeapYear()
    {
        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-28'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-28'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-29'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-29'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-30'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-03-30'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-04-30'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-05-30'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-31'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-03-31'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-05-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-07-31'), $computed[3]->getStart());
    }

    public function testLastDayOfMonthFix()
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $this->transformer->setConfig($transformerConfig);

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-28'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-28'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-29'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-29'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-30'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-30'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-30'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-01-31'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-03-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-04-30'), $computed[3]->getStart());
    }

    public function testLastDayOfMonthFixOnLeapYear()
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $this->transformer->setConfig($transformerConfig);

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-28'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-28'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-28'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-28'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-28'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-29'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-29'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-30'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-30'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-30'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-30'), $computed[3]->getStart());

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2016-01-31'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2016-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-30'), $computed[3]->getStart());
    }

    public function testLastDayOfMonthFixOn29thDayIn30DayMonth()
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $this->transformer->setConfig($transformerConfig);

        $rule     = new Rule('FREQ=MONTHLY;COUNT=4;INTERVAL=1', new \DateTime('2014-08-29'));
        $computed = $this->transformer->transform($rule);
        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2014-08-29'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-09-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-10-29'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-11-29'), $computed[3]->getStart());
    }

    public function testLastDayOfMonth()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=28,29,30,31;BYSETPOS=-1',
            new \DateTime('2016-01-29')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-01-31'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-03-31'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-04-30'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-05-31'), $computed[4]->getStart());
    }
}

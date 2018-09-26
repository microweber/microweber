<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformerConfig;

class ArrayTransformerByHourTest extends ArrayTransformerBase
{
    public function testByHourHourly()
    {
        $rule = new Rule(
            'FREQ=HOURLY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-13 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-13 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-14 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-14 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-15 14:00:00'), $computed[4]->getStart());
    }

    public function testByHourDaily()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-13 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-13 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-14 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-14 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-06-15 14:00:00'), $computed[4]->getStart());
    }

    public function testByHourWithVirtualLimit()
    {
        $rule = new Rule(
            'FREQ=DAILY;BYHOUR=13',
            new \DateTime('2014-06-12 15:00:00')
        );

        $config = new ArrayTransformerConfig();
        $config->setVirtualLimit(2);
        $this->transformer->setConfig($config);

        $computed = $this->transformer->transform($rule);

        $this->assertCount(2, $computed);
        $this->assertEquals(new \DateTime('2014-06-13 13:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-14 13:00:00'), $computed[1]->getStart());
    }

    public function testByHourWeekly()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-06-19 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-06-19 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-06-26 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-06-26 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-07-03 14:00:00'), $computed[4]->getStart());
    }

    public function testByHourMonthly()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2013-07-12 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2013-07-12 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2013-08-12 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2013-08-12 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2013-09-12 14:00:00'), $computed[4]->getStart());
    }

    public function testByHourMonthlyLeapYear()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2016-01-29 12:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-01-29 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-01-29 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-03-29 14:00:00'), $computed[4]->getStart());
    }

    public function testByHourYearly()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=5;BYHOUR=14,15',
            new \DateTime('2013-06-12 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2014-06-12 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-12 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-06-12 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2015-06-12 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2016-06-12 14:00:00'), $computed[4]->getStart());
    }

    public function testYearlyOnLeapYear()
    {
        $rule = new Rule('FREQ=YEARLY;COUNT=5;BYHOUR=14,15', new \DateTime('2016-02-29 12:00:00'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2020-02-29 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2020-02-29 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2024-02-29 14:00:00'), $computed[4]->getStart());
    }

    public function testYearlyOnLeapYearWithLastDayOfMonthFix()
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $this->transformer->setConfig($transformerConfig);

        $rule = new Rule('FREQ=YEARLY;COUNT=5;BYHOUR=14,15', new \DateTime('2016-02-29 12:00:00'));
        $computed = $this->transformer->transform($rule);

        $this->assertCount(5, $computed);
        $this->assertEquals(new \DateTime('2016-02-29 14:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2016-02-29 15:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2017-02-28 14:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2017-02-28 15:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2018-02-28 14:00:00'), $computed[4]->getStart());
    }
}

<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;

class ArrayTransformerByDayTest extends ArrayTransformerBase
{
    public function testByDayWeekly()
    {
        $rule = new Rule(
            'FREQ=WEEKLY;COUNT=10;INTERVAL=2;BYDAY=MO,WE,FR',
            new \DateTime('1997-09-02 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertEquals(10, count($computed));
        $this->assertEquals(new \DateTime('1997-09-03 16:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('1997-09-05 16:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('1997-09-15 16:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('1997-09-17 16:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('1997-09-19 16:00:00'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('1997-09-29 16:00:00'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('1997-10-01 16:00:00'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('1997-10-03 16:00:00'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('1997-10-13 16:00:00'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('1997-10-15 16:00:00'), $computed[9]->getStart());
    }

    public function testByDayWeeklyAcrossUKBST()
    {
              $rule = new Rule(
            'FREQ=WEEKLY;COUNT=10;BYDAY=WE',
            new \DateTime('2015-03-01 16:00:00', new \DateTimeZone('Europe/London')),
            new \DateTime('2015-03-01 17:00:00', new \DateTimeZone('Europe/London')),
            'Europe/London'
        );

        $computed = $this->transformer->transform($rule);

        $this->assertEquals(10, count($computed));
        $this->assertEquals('Europe/London', $computed[0]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-03-04 16:00:00', new \DateTimeZone('UTC')), $computed[0]->getStart());
        $this->assertEquals('Europe/London', $computed[1]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-03-11 16:00:00', new \DateTimeZone('UTC')), $computed[1]->getStart());
        $this->assertEquals('Europe/London', $computed[2]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-03-18 16:00:00', new \DateTimeZone('UTC')), $computed[2]->getStart());
        $this->assertEquals('Europe/London', $computed[3]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-03-25 16:00:00', new \DateTimeZone('UTC')), $computed[3]->getStart());
        // UK BST change happens here. Should change
        // to be honest, not sure if it should be 15 or 17 but def should NOT be 16.
        $this->assertEquals('Europe/London', $computed[4]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-04-01 15:00:00', new \DateTimeZone('UTC')), $computed[4]->getStart());
        $this->assertEquals('Europe/London', $computed[5]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-04-08 15:00:00', new \DateTimeZone('UTC')), $computed[5]->getStart());
        $this->assertEquals('Europe/London', $computed[6]->getStart()->getTimezone()->getName());
        $this->assertEquals(new \DateTime('2015-04-15 15:00:00', new \DateTimeZone('UTC')), $computed[6]->getStart());
    }

    public function testByDayMonthly()
    {
        $rule = new Rule(
            'FREQ=MONTHLY;COUNT=10;BYDAY=WE,TH',
            new \DateTime('2014-01-14 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertEquals(10, count($computed));
        $this->assertEquals(new \DateTime('2014-01-15 16:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-01-16 16:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2014-01-22 16:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2014-01-23 16:00:00'), $computed[3]->getStart());
        $this->assertEquals(new \DateTime('2014-01-29 16:00:00'), $computed[4]->getStart());
        $this->assertEquals(new \DateTime('2014-01-30 16:00:00'), $computed[5]->getStart());
        $this->assertEquals(new \DateTime('2014-02-05 16:00:00'), $computed[6]->getStart());
        $this->assertEquals(new \DateTime('2014-02-06 16:00:00'), $computed[7]->getStart());
        $this->assertEquals(new \DateTime('2014-02-12 16:00:00'), $computed[8]->getStart());
        $this->assertEquals(new \DateTime('2014-02-13 16:00:00'), $computed[9]->getStart());
    }

    public function testByDayYearly()
    {
        $rule = new Rule(
            'FREQ=YEARLY;COUNT=3;BYDAY=20MO',
            new \DateTime('1997-05-19 16:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertEquals(3, count($computed));
        $this->assertEquals(new \DateTime('1997-05-19 16:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('1998-05-18 16:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('1999-05-17 16:00:00'), $computed[2]->getStart());
    }

    /**
     * @dataProvider unsupportedNthByDayFrequencies
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testNthByDayWithUnsupportedFrequency($frequency)
    {
        new Rule(
            "FREQ=$frequency;COUNT=3;BYDAY=2MO",
            new \DateTime('1997-05-19 16:00:00')
        );
    }

    public function unsupportedNthByDayFrequencies()
    {
        return array(array('DAILY'), array('HOURLY'), array('MINUTELY'), array('SECONDLY'));
    }
}

<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\DateExclusion;

class ArrayTransformerExDateTest extends ArrayTransformerBase
{

    /**
     * Original timezone. Restore it upon test suite completion!
     *
     * @var string
     */
    protected static $originalTimezoneName;

    public static function setUpBeforeClass()
    {
        self::$originalTimezoneName = date_default_timezone_get();
        date_default_timezone_set('America/New_York');

        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        date_default_timezone_set(self::$originalTimezoneName);

        parent::tearDownAfterClass();
    }

    public function testExDateNoTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3;EXDATE=20140602',
            new \DateTime('2014-06-01')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(2, $computed);
        $this->assertEquals(new \DateTime('2014-06-01'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-03'), $computed[1]->getStart());
    }

    public function testExDateWithTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3;EXDATE=20140603T040000',
            new \DateTime('2014-06-01 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(2, $computed);
        $this->assertEquals(new \DateTime('2014-06-01 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-02 04:00:00'), $computed[1]->getStart());
    }

    public function testExDateWithUtcTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3;EXDATE=20140602T110000Z',
            new \DateTime('2014-06-01 07:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(2, $computed);
        $this->assertEquals(new \DateTime('2014-06-01 07:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-03 07:00:00'), $computed[1]->getStart());
    }

    public function testExDateWithMixedTimezones()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3;EXDATE=20140601T040000,20140602T080000Z',
            new \DateTime('2014-06-01 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(1, $computed);
        $this->assertEquals(new \DateTime('2014-06-03 04:00:00'), $computed[0]->getStart());
    }

    public function testSetExDates()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3',
            new \DateTime('2014-06-01')
        );

        $exDates = array(
            new DateExclusion(new \DateTime('2014-06-02'), false),
        );
        $rule->setExDates($exDates);

        $computed = $this->transformer->transform($rule);

        $this->assertCount(2, $computed);
        $this->assertEquals(new \DateTime('2014-06-01'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2014-06-03'), $computed[1]->getStart());
    }
}

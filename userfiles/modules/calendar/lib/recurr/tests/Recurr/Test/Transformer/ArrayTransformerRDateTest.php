<?php

namespace Recurr\Test\Transformer;

use Recurr\Rule;
use Recurr\DateInclusion;

class ArrayTransformerRDateTest extends ArrayTransformerBase
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

    public function testRDateInConjunctionWithExDate()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=3;RDATE=20151208;EXDATE=20151208',
            new \DateTime('2015-12-10')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2015-12-10'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-12'), $computed[2]->getStart());
    }

    public function testRDateNoTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=2;RDATE=20151208',
            new \DateTime('2015-12-10')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2015-12-10'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-08'), $computed[2]->getStart());
    }

    public function testRDateWithTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=2;RDATE=20151208T010000',
            new \DateTime('2015-12-10 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2015-12-10 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-08 01:00:00'), $computed[2]->getStart());
    }

    public function testRDateWithUtcTime()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=2;RDATE=20151208T110000Z',
            new \DateTime('2015-12-10 07:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2015-12-10 07:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11 07:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-08 11:00:00Z'), $computed[2]->getStart());
    }

    public function testRDateWithMixedTimezones()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=2;RDATE=20151208T190000,20151125T080000Z',
            new \DateTime('2015-12-10 04:00:00')
        );

        $computed = $this->transformer->transform($rule);

        $this->assertCount(4, $computed);
        $this->assertEquals(new \DateTime('2015-12-10 04:00:00'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11 04:00:00'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-08 19:00:00'), $computed[2]->getStart());
        $this->assertEquals(new \DateTime('2015-11-25 08:00:00Z'), $computed[3]->getStart());
    }

    public function testSetRDates()
    {
        $rule = new Rule(
            'FREQ=DAILY;COUNT=2',
            new \DateTime('2015-12-10')
        );

        $rDates = array(
            new DateInclusion(new \DateTime('2015-12-08'), false),
        );
        $rule->setRDates($rDates);

        $computed = $this->transformer->transform($rule);

        $this->assertCount(3, $computed);
        $this->assertEquals(new \DateTime('2015-12-10'), $computed[0]->getStart());
        $this->assertEquals(new \DateTime('2015-12-11'), $computed[1]->getStart());
        $this->assertEquals(new \DateTime('2015-12-08'), $computed[2]->getStart());
    }
}

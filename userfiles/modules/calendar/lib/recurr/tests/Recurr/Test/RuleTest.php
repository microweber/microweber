<?php

namespace Recurr\Test;

use Recurr\DateExclusion;
use Recurr\DateInclusion;
use Recurr\Frequency;
use Recurr\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var Rule */
    protected $rule;

    public function setUp()
    {
        $this->rule = new Rule;
    }

    public function testConstructAcceptableStartDate()
    {
        $this->rule = new Rule(null, null);
        $this->assertInstanceOf(\DateTime::class, $this->rule->getStartDate());

        $this->rule = new Rule(null, '2018-09-19');
        $this->assertInstanceOf(\DateTime::class, $this->rule->getStartDate());

        $this->rule = new Rule(null, new \DateTime('2018-09-19'));
        $this->assertInstanceOf(\DateTime::class, $this->rule->getStartDate());
    }

    public function testConstructAcceptableEndDate()
    {
        $this->rule = new Rule(null, null, '2018-09-19');
        $this->assertInstanceOf(\DateTime::class, $this->rule->getEndDate());

        $this->rule = new Rule(null, null, new \DateTime('2018-09-19'));
        $this->assertInstanceOf(\DateTime::class, $this->rule->getEndDate());
    }

    public function testDefaultTimezone()
    {
        $this->assertEquals(date_default_timezone_get(), $this->rule->getTimezone());
    }

    public function testTimezoneObtainedFromStartDate()
    {
        $startDate = new \DateTime('2014-01-25 05:20:30', new \DateTimeZone('America/Los_Angeles'));

        $this->rule = new Rule(null, $startDate);
        $this->assertEquals($startDate->getTimezone()->getName(), $this->rule->getTimezone());
    }

    /**
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testLoadFromStringWithMissingFreq()
    {
        $this->rule->loadFromString('COUNT=2');
    }

    /**
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testLoadFromStringWithBothCountAndUntil()
    {
        $this->rule->loadFromString('FREQ=DAILY;COUNT=2;UNTIL=20130510');
    }

    public function testLoadFromString()
    {
        $string = 'FREQ=YEARLY;';
        $string .= 'COUNT=2;';
        $string .= 'INTERVAL=2;';
        $string .= 'BYSECOND=30;';
        $string .= 'BYMINUTE=10;';
        $string .= 'BYHOUR=5,15;';
        $string .= 'BYDAY=SU,WE;';
        $string .= 'BYMONTHDAY=16,22;';
        $string .= 'BYYEARDAY=201,203;';
        $string .= 'BYWEEKNO=29,32;';
        $string .= 'BYMONTH=7,8;';
        $string .= 'BYSETPOS=1,3;';
        $string .= 'WKST=TU;';
        $string .= 'RDATE=20151210,20151214T020000,20151215T210000Z;';
        $string .= 'EXDATE=20140607,20140620T010000,20140620T160000Z;';

        $this->rule->loadFromString($string);

        $this->assertEquals(Frequency::YEARLY, $this->rule->getFreq());
        $this->assertEquals(2, $this->rule->getCount());
        $this->assertEquals(2, $this->rule->getInterval());
        $this->assertEquals(array(30), $this->rule->getBySecond());
        $this->assertEquals(array(10), $this->rule->getByMinute());
        $this->assertEquals(array(5, 15), $this->rule->getByHour());
        $this->assertEquals(array('SU', 'WE'), $this->rule->getByDay());
        $this->assertEquals(array(16, 22), $this->rule->getByMonthDay());
        $this->assertEquals(array(201, 203), $this->rule->getByYearDay());
        $this->assertEquals(array(29, 32), $this->rule->getByWeekNumber());
        $this->assertEquals(array(7, 8), $this->rule->getByMonth());
        $this->assertEquals(array(1, 3), $this->rule->getBySetPosition());
        $this->assertEquals('TU', $this->rule->getWeekStart());
        $this->assertEquals(
            array(
                new DateInclusion(new \DateTime(20151210), false),
                new DateInclusion(new \DateTime('20151214T020000'), true),
                new DateInclusion(new \DateTime('20151215 21:00:00 UTC'), true, true)
            ),
            $this->rule->getRDates()
        );
        $this->assertEquals(
            array(
                new DateExclusion(new \DateTime(20140607), false),
                new DateExclusion(new \DateTime('20140620T010000'), true),
                new DateExclusion(new \DateTime('20140620 16:00:00 UTC'), true, true)
            ),
            $this->rule->getExDates()
        );
    }


    public function testLoadFromArray()
    {
        $this->rule->loadFromArray(array(
            'FREQ'=>'YEARLY',
            'COUNT'=>'2',
            'INTERVAL'=>'2',
            'BYSECOND'=>'30',
            'BYMINUTE'=>'10',
            'BYHOUR'=>'5,15',
            'BYDAY'=>'SU,WE',
            'BYMONTHDAY'=>'16,22',
            'BYYEARDAY'=>'201,203',
            'BYWEEKNO'=>'29,32',
            'BYMONTH'=>'7,8',
            'BYSETPOS'=>'1,3',
            'WKST'=>'TU',
            'RDATE'=>'20151210,20151214T020000,20151215T210000Z',
            'EXDATE'=>'20140607,20140620T010000,20140620T160000Z',
        ));

        $this->assertEquals(Frequency::YEARLY, $this->rule->getFreq());
        $this->assertEquals(2, $this->rule->getCount());
        $this->assertEquals(2, $this->rule->getInterval());
        $this->assertEquals(array(30), $this->rule->getBySecond());
        $this->assertEquals(array(10), $this->rule->getByMinute());
        $this->assertEquals(array(5, 15), $this->rule->getByHour());
        $this->assertEquals(array('SU', 'WE'), $this->rule->getByDay());
        $this->assertEquals(array(16, 22), $this->rule->getByMonthDay());
        $this->assertEquals(array(201, 203), $this->rule->getByYearDay());
        $this->assertEquals(array(29, 32), $this->rule->getByWeekNumber());
        $this->assertEquals(array(7, 8), $this->rule->getByMonth());
        $this->assertEquals(array(1, 3), $this->rule->getBySetPosition());
        $this->assertEquals('TU', $this->rule->getWeekStart());
        $this->assertEquals(
            array(
                new DateInclusion(new \DateTime(20151210), false),
                new DateInclusion(new \DateTime('20151214T020000'), true),
                new DateInclusion(new \DateTime('20151215 21:00:00 UTC'), true, true)
            ),
            $this->rule->getRDates()
        );
        $this->assertEquals(
            array(
                new DateExclusion(new \DateTime(20140607), false),
                new DateExclusion(new \DateTime('20140620T010000'), true),
                new DateExclusion(new \DateTime('20140620 16:00:00 UTC'), true, true)
            ),
            $this->rule->getExDates()
        );
    }


    public function testLoadFromStringWithDtstart()
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('America/Chicago');

        $string = 'FREQ=MONTHLY;DTSTART=20140222T073000';

        $this->rule->setTimezone('America/Los_Angeles');
        $this->rule->loadFromString($string);

        $expectedStartDate = new \DateTime('2014-02-22 05:30:00', new \DateTimeZone('America/Los_Angeles'));

        $this->assertEquals(Frequency::MONTHLY, $this->rule->getFreq());
        $this->assertEquals($expectedStartDate, $this->rule->getStartDate());

        date_default_timezone_set($defaultTimezone);
    }

    public function testLoadFromStringWithDtend()
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('America/Chicago');
        $string = 'FREQ=MONTHLY;DTEND=20140422T140000';

        $this->rule->setTimezone('America/Los_Angeles');
        $this->rule->loadFromString($string);

        $expectedEndDate = new \DateTime('2014-04-22 12:00:00', new \DateTimeZone('America/Los_Angeles'));

        $this->assertEquals(Frequency::MONTHLY, $this->rule->getFreq());
        $this->assertEquals($expectedEndDate, $this->rule->getEndDate());

        date_default_timezone_set($defaultTimezone);
    }

    /**
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testLoadFromStringFails()
    {
        $this->rule->loadFromString('IM AN INVALID RRULE');
    }

    public function testGetString()
    {
        $this->rule->setFreq('YEARLY');
        $this->rule->setCount(2);
        $this->rule->setInterval(2);
        $this->rule->setBySecond(array(30));
        $this->rule->setByMinute(array(10));
        $this->rule->setByHour(array(5, 15));
        $this->rule->setByDay(array('SU', 'WE'));
        $this->rule->setByMonthDay(array(16, 22));
        $this->rule->setByYearDay(array(201, 203));
        $this->rule->setByWeekNumber(array(29, 32));
        $this->rule->setByMonth(array(7, 8));
        $this->rule->setBySetPosition(array(1, 3));
        $this->rule->setWeekStart('TU');
        $this->rule->setRDates(array('20151210', '20151214T020000Z', '20151215T210000'));
        $this->rule->setExDates(array('20140607', '20140620T010000Z', '20140620T010000'));

        $this->assertEquals(
            'FREQ=YEARLY;COUNT=2;INTERVAL=2;BYSECOND=30;BYMINUTE=10;BYHOUR=5,15;BYDAY=SU,WE;BYMONTHDAY=16,22;BYYEARDAY=201,203;BYWEEKNO=29,32;BYMONTH=7,8;BYSETPOS=1,3;WKST=TU;RDATE=20151210,20151214T020000Z,20151215T210000;EXDATE=20140607,20140620T010000Z,20140620T010000',
            $this->rule->getString()
        );
    }

    public function testGetStringWithUTC()
    {
        $this->rule->setFreq('DAILY');
        $this->rule->setInterval(1);
        $this->rule->setUntil(new \DateTime('2015-07-10 04:00:00', new \DateTimeZone('America/New_York')));

        $this->assertNotEquals(
            'FREQ=DAILY;UNTIL=20150710T040000Z;INTERVAL=1',
            $this->rule->getString()
        );

        $this->assertEquals(
            'FREQ=DAILY;UNTIL=20150710T080000Z;INTERVAL=1',
            $this->rule->getString(Rule::TZ_FIXED)
        );
    }

    public function testGetStringWithDtstart()
    {
        $string = 'FREQ=MONTHLY;DTSTART=20140210T163045;INTERVAL=1;WKST=MO';

        $this->rule->loadFromString($string);

        $this->assertEquals($string, $this->rule->getString());
    }

    public function testGetStringWithDtend()
    {
        $string = 'FREQ=MONTHLY;DTEND=20140410T163045;INTERVAL=1;WKST=MO';

        $this->rule->loadFromString($string);

        $this->assertEquals($string, $this->rule->getString());
    }

    public function testGetStringWithUntil()
    {
        $string = 'FREQ=MONTHLY;UNTIL=20140410T163045;INTERVAL=1;WKST=MO';

        $this->rule->loadFromString($string);

        $this->assertEquals($string, $this->rule->getString());
    }

    public function testGetStringWithUntilUsingZuluTime()
    {
        $string = 'FREQ=MONTHLY;UNTIL=20170331T040000Z;INTERVAL=1;WKST=MO';

        $this->rule->loadFromString($string);

        $this->assertSame($string, $this->rule->getString(Rule::TZ_FIXED));
    }

    public function testGetStringWithoutExplicitWkst()
    {
        $string = 'FREQ=MONTHLY;COUNT=2;INTERVAL=1';

        $this->rule->loadFromString($string);

        $this->assertNotContains('WKST', $this->rule->getString());
    }

    public function testGetStringWithExplicitWkst()
    {
        $string = 'FREQ=MONTHLY;COUNT=2;INTERVAL=1;WKST=TH';

        $this->rule->loadFromString($string);

        $this->assertContains('WKST=TH', $this->rule->getString());
    }

    public function testSetStartDateAffectsStringOutput()
    {
        $this->rule->loadFromString('FREQ=MONTHLY;COUNT=2');
        $this->assertEquals('FREQ=MONTHLY;COUNT=2', $this->rule->getString());

        $this->rule->setStartDate(new \DateTime('2015-12-10'));
        $this->assertEquals('FREQ=MONTHLY;COUNT=2', $this->rule->getString());

        $this->rule->setStartDate(new \DateTime('2015-12-10'), true);
        $this->assertEquals('FREQ=MONTHLY;COUNT=2;DTSTART=20151210T000000', $this->rule->getString());

        $this->rule->setStartDate(new \DateTime('2015-12-10'), false);
        $this->assertEquals('FREQ=MONTHLY;COUNT=2', $this->rule->getString());
    }

    /**
     * @expectedException \Recurr\Exception\InvalidArgument
     */
    public function testBadInterval()
    {
        $this->rule->setInterval('six');
    }

    /**
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testEmptyByDayThrowsException()
    {
        $this->rule->setByDay(array());
    }

    /**
     * @expectedException \Recurr\Exception\InvalidRRule
     */
    public function testEmptyByDayFromStringThrowsException()
    {
        $this->rule->loadFromString('FREQ=WEEKLY;BYDAY=;INTERVAL=1;UNTIL=20160725');
    }

    /**
     * @expectedException \Recurr\Exception\InvalidArgument
     */
    public function testBadWeekStart()
    {
        $this->rule->setWeekStart('monday');
    }

    /**
     * @dataProvider exampleRruleProvider
     */
    public function testRepeatsIndefinitely($string, $expected)
    {
        $this->assertSame($expected, $this->rule->loadFromString($string)->repeatsIndefinitely());
    }

    /**
     * Taken from https://tools.ietf.org/html/rfc5545#section-3.8.5.3
     *
     * @return array
     */
    public function exampleRruleProvider()
    {
        return array(
            array('FREQ=DAILY;COUNT=10', false),
            array('FREQ=DAILY;UNTIL=19971224T000000Z', false),
            array('FREQ=DAILY;INTERVAL=2', true),
            array('FREQ=DAILY;INTERVAL=10;COUNT=5', false),
            array('FREQ=YEARLY;UNTIL=20000131T140000Z', false),
            array('FREQ=DAILY;UNTIL=20000131T140000Z;BYMONTH=1', false),
            array('FREQ=WEEKLY;COUNT=10', false),
            array('FREQ=WEEKLY;UNTIL=19971224T000000Z', false),
            array('FREQ=WEEKLY;INTERVAL=2;WKST=SU', true),
            array('FREQ=WEEKLY;UNTIL=19971007T000000Z;WKST=SU;BYDAY=TU,TH', false),
            array('FREQ=WEEKLY;COUNT=10;WKST=SU;BYDAY=TU,TH', false),
            array('FREQ=WEEKLY;INTERVAL=2;UNTIL=19971224T000000Z;WKST=SU', false),
            array('FREQ=WEEKLY;INTERVAL=2;COUNT=8;WKST=SU;BYDAY=TU,TH', false),
            array('FREQ=MONTHLY;COUNT=10;BYDAY=1FR', false),
            array('FREQ=MONTHLY;UNTIL=19971224T000000Z;BYDAY=1FR', false),
            array('FREQ=MONTHLY;INTERVAL=2;COUNT=10;BYDAY=1SU,-1SU', false),
            array('FREQ=MONTHLY;COUNT=6;BYDAY=-2MO', false),
            array('FREQ=MONTHLY;BYMONTHDAY=-3', true),
            array('FREQ=MONTHLY;COUNT=10;BYMONTHDAY=2,15', false),
            array('FREQ=MONTHLY;COUNT=10;BYMONTHDAY=1,-1', false),
            array('FREQ=MONTHLY;INTERVAL=18;COUNT=10;BYMONTHDAY=10,11,12,', false),
            array('FREQ=MONTHLY;INTERVAL=2;BYDAY=TU', true),
            array('FREQ=YEARLY;COUNT=10;BYMONTH=6,7', false),
            array('FREQ=YEARLY;INTERVAL=2;COUNT=10;BYMONTH=1,2,3', false),
            array('FREQ=YEARLY;INTERVAL=3;COUNT=10;BYYEARDAY=1,100,200', false),
            array('FREQ=YEARLY;BYDAY=20MO', true),
            array('FREQ=YEARLY;BYWEEKNO=20;BYDAY=MO', true),
            array('FREQ=YEARLY;BYMONTH=3;BYDAY=TH', true),
            array('FREQ=YEARLY;BYDAY=TH;BYMONTH=6,7,8', true),
            array('FREQ=MONTHLY;BYDAY=FR;BYMONTHDAY=13', true),
            array('FREQ=MONTHLY;BYDAY=SA;BYMONTHDAY=7,8,9,10,11,12,13', true),
            array('FREQ=YEARLY;INTERVAL=4;BYMONTH=11;BYDAY=TU', true),
            array('FREQ=MONTHLY;COUNT=3;BYDAY=TU,WE,TH;BYSETPOS=3', false),
            array('FREQ=MONTHLY;BYDAY=MO,TU,WE,TH,FR;BYSETPOS=-2', true),
            array('FREQ=HOURLY;INTERVAL=3;UNTIL=19970902T170000Z', false),
            array('FREQ=MINUTELY;INTERVAL=15;COUNT=6', false),
            array('FREQ=MINUTELY;INTERVAL=90;COUNT=4', false),
            array('FREQ=DAILY;BYHOUR=9,10,11,12,13,14,15,16;BYMINUTE=0,20,40', true),
            array('FREQ=MINUTELY;INTERVAL=20;BYHOUR=9,10,11,12,13,14,15,16', true),
            array('FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=MO', false),
            array('FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=SU', false),
            array('FREQ=MONTHLY;BYMONTHDAY=15,30;COUNT=5', false),
        );
    }
}

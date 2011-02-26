<?php

require_once 'Sabre/CalDAV/TestUtil.php';

class Sabre_CalDAV_CalendarQueryFilterTest extends PHPUnit_Framework_TestCase {

    function testSubStringMatchAscii() {

        $caldav = new Sabre_CalDAV_Plugin();

        $this->assertTrue($caldav->substringMatch('string','string','i;ascii-casemap'));
        $this->assertTrue($caldav->substringMatch('string','rin', 'i;ascii-casemap'));
        $this->assertTrue($caldav->substringMatch('STRING','string','i;ascii-casemap'));
        $this->assertTrue($caldav->substringMatch('string','RIN', 'i;ascii-casemap'));
        $this->assertFalse($caldav->substringMatch('string','ings', 'i;ascii-casemap'));

    }

    function testSubStringMatchOctet() {

        $caldav = new Sabre_CalDAV_Plugin();

        $this->assertTrue($caldav->substringMatch('string','string','i;octet'));
        $this->assertTrue($caldav->substringMatch('string','rin', 'i;octet'));
        $this->assertFalse($caldav->substringMatch('STRING','string','i;octet'));
        $this->assertFalse($caldav->substringMatch('string','RIN', 'i;octet'));
        $this->assertFalse($caldav->substringMatch('string','ings', 'i;octet'));

    }

    /**
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testSubStringMatchUnknownCollation() {

        $caldav = new Sabre_CalDAV_Plugin();

        $caldav->substringMatch('string','string','i;bla');

    }

    function testCompFilter() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(),
        );

        $this->assertTrue($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));

    }


    /**
     * @depends testCompFilter
     */
    function testTimeRangeEvent() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(
                'time-range' => array(
                    'start' => new DateTime('2006-01-04 00:00:00',new DateTimeZone('UTC')),
                    'end' =>   new DateTime('2006-01-05 00:00:00',new DateTimeZone('UTC')),
                ),
            ),
        );
        

        $this->assertFalse($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));
        $filters['/c:iCalendar/c:vcalendar/c:vevent']['time-range']['end'] = new DateTime('2011-01-01 00:00:00', new DateTimeZone('UTC'));

        foreach(range(1,7) as $testCase) {
            $this->assertTrue($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData($testCase),$filters));
        }

    }

    /**
     * @depends testCompFilter
     * @depends testTimeRangeEvent
     */
    function testTimeRangeTodo() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vtodo' => array(
                'time-range' => array(
                    'start' => new DateTime('2006-01-01 00:00:00',new DateTimeZone('UTC')),
                    'end' => new DateTime('2007-01-01 00:00:00', new DateTimeZone('UTC')),
                ),
            ),
        );
        

        $tests = array(
            'dtstart_duration' =>  true,
            'dtstart_duration2'=> false,
            'due'              => false,
            'due2'             => true,
            'due_date'         => true,
            'due_tz'           => true,
            'due_dtstart'      => true,
            'due_dtstart2'     => false,
            'dtstart'          => false,
            'dtstart2'         => true,
            'dtstart_tz'       => false,
            'dtstart_date'     => false,
            'completed'        => true,
            'completed2'       => false,
            'created'          => true,
            'created2'         => false,
            'completedcreated' => true,
            'completedcreated2'=> false,
            'notime'           => true,
        );
        foreach($tests as $test=>$expectedResult) {
            $this->assertEquals($expectedResult, $calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestTodo($test),$filters), 'Testname: ' . $test);
        }

    }

    /**
     * @depends testTimeRangeEvent
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testTimeRangeNoDTSTART() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(
                'time-range' => array(
                    'start' => new DateTime('2006-01-04 00:00:00', new DateTimeZone('UTC')),
                    'end'   => new DateTime('2011-01-05 00:00:00', new DateTimeZone('UTC')),
                ),
            ),
        );
       
        $calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData('X'),$filters);

    }


    /**
     * @depends testCompFilter
     * @depends testSubStringMatchOctet
     */
    function testPropFilter() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent/c:uid' => array(
                'text-match' => array(
                    'collation' => 'i;octet',
                    'value' => 'DC6C50A017428C5216A2F1CD@example.com',
                    'negate-condition' => false,
                ),
            ),
        );

        $this->assertFalse($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));
        $filters['/c:iCalendar/c:vcalendar/c:vevent/c:uid']['text-match']['value'] = '39A6B5ED-DD51-4AFE-A683-C35EE3749627';
        $this->assertTrue($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));


    }

    /**
     * @depends testPropFilter
     * @depends testSubStringMatchAscii
     */
    function testParamFilter() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent/c:attendee' => array(
                'text-match' => array(
                    'collation' => 'i;ascii-casemap',
                    'negate-condition' => false,
                    'value' => 'mailto:lisa@example.com',
                ),
             ),
             '/c:iCalendar/c:vcalendar/c:vevent/c:attendee/@partstat' => array(
                'text-match' => array(
                    'collation' => 'i;ascii-casemap',
                    'negate-condition' => false,
                    'value' => 'needs-action',
                ),
            ),
        );

        $this->assertTrue($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));


    }

    /**
     * @depends testParamFilter
     */
    function testUndefinedNegation() {

        $calendarPlugin = new Sabre_CalDAV_Plugin(Sabre_CalDAV_Util::getBackend());

        $filters = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vtodo' => array(),
            '/c:iCalendar/c:vcalendar/c:vtodo/c:completed' => array(
                'is-not-defined' => true,
            ),
            '/c:iCalendar/c:vcalendar/c:vtodo/c:status' => array(
                'text-match' => array(
                    'collation' => 'i;ascii-casemap',
                    'negate-condition' => true,
                    'value'    => 'CANCELLED',
                ),
            ),
        );

        $this->assertFalse($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestCalendarData(),$filters));
        $this->assertTrue($calendarPlugin->validateFilters(Sabre_CalDAV_TestUtil::getTestTodo(),$filters));

    }

}

<?php

require_once 'Sabre/CalDAV/TestUtil.php';

class Sabre_CalDAV_XMLUtilTest extends PHPUnit_Framework_TestCase {

    function testParseICalendarDuration() {

        $this->assertEquals('+1 weeks', Sabre_CalDAV_XMLUtil::parseICalendarDuration('P1W'));
        $this->assertEquals('+5 days',  Sabre_CalDAV_XMLUtil::parseICalendarDuration('P5D'));
        $this->assertEquals('+5 days 3 hours 50 minutes 12 seconds', Sabre_CalDAV_XMLUtil::parseICalendarDuration('P5DT3H50M12S'));
        $this->assertEquals('-1 weeks 50 minutes', Sabre_CalDAV_XMLUtil::parseICalendarDuration('-P1WT50M'));
        $this->assertEquals('+50 days 3 hours 2 seconds', Sabre_CalDAV_XMLUtil::parseICalendarDuration('+P50DT3H2S'));

    }

    /**
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testParseICalendarDurationFail() {

        Sabre_CalDAV_XMLUtil::parseICalendarDuration('P1X');

    }

    function testCompFilter() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
   <C:comp-filter name="VEVENT" />
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(),
        );
        

        $result = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $result);

    }


    /**
     * @depends testCompFilter
     * @depends testParseICalendarDuration
     */
    function testTimeRangeEvent() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
    <C:comp-filter name="VEVENT">
        <C:time-range start="20060104T000000Z" end="20060105T000000Z" />
    </C:comp-filter>
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vevent' => array(
                'time-range' => array(
                    'start' => new DateTime('2006-01-04 00:00:00',new DateTimeZone('UTC')),
                    'end' =>   new DateTime('2006-01-05 00:00:00',new DateTimeZone('UTC')),
                ),
            ),
        );
        

        $filters = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $filters);
        
    }

    /**
     * @depends testCompFilter
     * @depends testParseICalendarDuration
     * @depends testTimeRangeEvent
     */
    function testTimeRangeTodo() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
    <C:comp-filter name="VTODO">
        <C:time-range start="20060101T000000Z" end="20070101T000000Z" />
    </C:comp-filter>
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
            '/c:iCalendar/c:vcalendar' => array(),
            '/c:iCalendar/c:vcalendar/c:vtodo' => array(
                'time-range' => array(
                    'start' => new DateTime('2006-01-01 00:00:00',new DateTimeZone('UTC')),
                    'end' => new DateTime('2007-01-01 00:00:00', new DateTimeZone('UTC')),
                ),
            ),
        );
        

        $filters = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $filters);


    }

    /**
     * @depends testCompFilter
     */
    function testPropFilter() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
    <C:comp-filter name="VEVENT">
        <C:prop-filter name="UID">
            <C:text-match collation="i;octet">DC6C50A017428C5216A2F1CD@example.com</C:text-match>
        </C:prop-filter>
    </C:comp-filter>
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
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

        $filters = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $filters);

    }

    /**
     * @depends testPropFilter
     */
    function testParamFilter() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
    <C:comp-filter name="VEVENT">
        <C:prop-filter name="ATTENDEE">
            <C:text-match collation="i;ascii-casemap">mailto:lisa@example.com</C:text-match>
            <C:param-filter name="PARTSTAT">
                <C:text-match collation="i;ascii-casemap">needs-action</C:text-match>
            </C:param-filter>
        </C:prop-filter>
    </C:comp-filter>
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
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

        $result = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $result);

    }

    /**
     * @depends testParamFilter
     */
    function testUndefinedNegation() {

        $xml = <<<XML
<?xml version="1.0"?>
<C:filter xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <C:comp-filter name="VCALENDAR">
    <C:comp-filter name="VTODO">
        <C:prop-filter name="COMPLETED">
            <C:is-not-defined />
        </C:prop-filter>
        <C:prop-filter name="STATUS">
            <C:text-match negate-condition="yes">CANCELLED</C:text-match>
        </C:prop-filter>
    </C:comp-filter>
 </C:comp-filter>
</C:filter>
XML;


        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        $expected = array(
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

        $result = Sabre_CalDAV_XMLUtil::parseCalendarQueryFilters($dom->firstChild);
        $this->assertEquals($expected, $result);

    }

    function testParseICalendarDateTime() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDateTime('20100316T141405');

        $compare = new DateTime('2010-03-16 14:14:05',new DateTimeZone('UTC'));

        $this->assertEquals($compare, $dateTime);

    }

    /** 
     * @depends testParseICalendarDateTime
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testParseICalendarDateTimeBadFormat() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDateTime('20100316T141405 ');

    }

    /** 
     * @depends testParseICalendarDateTime
     */
    function testParseICalendarDateTimeUTC() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDateTime('20100316T141405Z');

        $compare = new DateTime('2010-03-16 14:14:05',new DateTimeZone('UTC'));
        $this->assertEquals($compare, $dateTime);

    }

    /** 
     * @depends testParseICalendarDateTime
     */
    function testParseICalendarDateTimeCustomTimeZone() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDateTime('20100316T141405', new DateTimeZone('Europe/Amsterdam'));

        $compare = new DateTime('2010-03-16 13:14:05',new DateTimeZone('UTC'));
        $this->assertEquals($compare, $dateTime);

    }

    function testParseICalendarDate() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDate('20100316');

        $compare = new DateTime('2010-03-16 00:00:00',new DateTimeZone('UTC'));

        $this->assertEquals($compare, $dateTime);

    }

    /** 
     * @depends testParseICalendarDate
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testParseICalendarDateBadFormat() {

        $dateTime = Sabre_CalDAV_XMLUtil::parseICalendarDate('20100316T141405');

    }
}

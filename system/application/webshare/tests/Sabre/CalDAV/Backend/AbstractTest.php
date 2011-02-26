<?php

class Sabre_CalDAV_Backend_AbstractTest extends PHPUnit_Framework_TestCase {

    function testUpdateCalendar() {

        $abstract = new Sabre_CalDAV_Backend_AbstractMock();
        $this->assertEquals(false, $abstract->updateCalendar('randomid', array('{DAV:}displayname' => 'anything')));

    }

}

class Sabre_CalDAV_Backend_AbstractMock extends Sabre_CalDAV_Backend_Abstract {

    function getCalendarsForUser($principalUri) { }
    function createCalendar($principalUri,$calendarUri,array $properties) { }
    function deleteCalendar($calendarId) { }
    function getCalendarObjects($calendarId) { }
    function getCalendarObject($calendarId,$objectUri) { }
    function createCalendarObject($calendarId,$objectUri,$calendarData) { }
    function updateCalendarObject($calendarId,$objectUri,$calendarData) { }
    function deleteCalendarObject($calendarId,$objectUri) { }

}

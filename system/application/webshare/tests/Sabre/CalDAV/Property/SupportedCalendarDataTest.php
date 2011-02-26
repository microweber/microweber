<?php

class Sabre_CalDAV_Property_SupportedCalendarDataTest extends PHPUnit_Framework_TestCase {

    function testSimple() {

        $sccs = new Sabre_CalDAV_Property_SupportedCalendarData();

    }

    /**
     * @depends testSimple
     */
    function testSerialize() {

        $property = new Sabre_CalDAV_Property_SupportedCalendarData();

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');
        $root->setAttribute('xmlns:cal',Sabre_CalDAV_Plugin::NS_CALDAV);

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:root xmlns:d="DAV:" xmlns:cal="' . Sabre_CalDAV_Plugin::NS_CALDAV . '">' .
'<cal:calendar-data content-type="text/calendar" version="2.0"/>' . 
'</d:root>
', $xml);

    }

}

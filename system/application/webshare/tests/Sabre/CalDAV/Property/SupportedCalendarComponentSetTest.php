<?php

class Sabre_CalDAV_Property_SupportedCalendarComponentSetTest extends PHPUnit_Framework_TestCase {

    function testSimple() {

        $sccs = new Sabre_CalDAV_Property_SupportedCalendarComponentSet(array('VEVENT'));
        $this->assertEquals(array('VEVENT'), $sccs->getValue());

    }

    /**
     * @depends testSimple
     */
    function testSerialize() {

        $property = new Sabre_CalDAV_Property_SupportedCalendarComponentSet(array('VEVENT','VJOURNAL'));

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
'<cal:comp name="VEVENT"/>' . 
'<cal:comp name="VJOURNAL"/>' . 
'</d:root>
', $xml);

    }

    /** 
     * @depends testSimple
     */
    function testUnserializer() {

        $xml = '<?xml version="1.0"?>
<d:root xmlns:d="DAV:" xmlns:cal="' . Sabre_CalDAV_Plugin::NS_CALDAV . '">' .
'<cal:comp name="VEVENT"/>' . 
'<cal:comp name="VJOURNAL"/>' . 
'</d:root>';

        $dom = Sabre_DAV_XMLUtil::loadDOMDocument($xml);
        
        $property = Sabre_CalDAV_Property_SupportedCalendarComponentSet::unserialize($dom->firstChild);

        $this->assertTrue($property instanceof Sabre_CalDAV_Property_SupportedCalendarComponentSet);
        $this->assertEquals(array(
            'VEVENT',
            'VJOURNAL',
           ),
           $property->getValue());

    }

}

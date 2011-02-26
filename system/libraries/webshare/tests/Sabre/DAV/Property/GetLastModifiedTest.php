<?php

class Sabre_DAV_Property_GetLastModifiedTest extends PHPUnit_Framework_TestCase {

    function testConstructDateTime() {

        $dt = new DateTime('2010-03-14 16:35', new DateTimeZone('UTC'));
        $lastMod = new Sabre_DAV_Property_GetLastModified($dt);
        $this->assertEquals($dt->format(DateTime::ATOM), $lastMod->getTime()->format(DateTime::ATOM));

    }

    function testConstructString() {

        $dt = new DateTime('2010-03-14 16:35', new DateTimeZone('UTC'));
        $lastMod = new Sabre_DAV_Property_GetLastModified('2010-03-14 16:35');
        $this->assertEquals($dt->format(DateTime::ATOM), $lastMod->getTime()->format(DateTime::ATOM));

    }

    function testConstructInt() {

        $dt = new DateTime('2010-03-14 16:35', new DateTimeZone('UTC'));
        $lastMod = new Sabre_DAV_Property_GetLastModified((int)$dt->format('U'));
        $this->assertEquals($dt->format(DateTime::ATOM), $lastMod->getTime()->format(DateTime::ATOM));

    }

    function testSerialize() {

        $dt = new DateTime('2010-03-14 16:35', new DateTimeZone('UTC'));
        $lastMod = new Sabre_DAV_Property_GetLastModified($dt);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:getlastmodified');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $lastMod->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:getlastmodified xmlns:d="DAV:" xmlns:b="urn:uuid:c2f41010-65b3-11d1-a29f-00aa00c14882/" b:dt="dateTime.rfc1123">' .
$dt->format(DateTime::RFC1123) . 
'</d:getlastmodified>
', $xml);


    }

}

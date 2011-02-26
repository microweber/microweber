<?php

class Sabre_DAV_Property_HrefTest extends PHPUnit_Framework_TestCase {

    function testConstruct() {

        $href = new Sabre_DAV_Property_Href('path');
        $this->assertEquals('path',$href->getHref());

    }

    function testSerialize() {

        $href = new Sabre_DAV_Property_Href('path');
        $this->assertEquals('path',$href->getHref());

        $doc = new DOMDocument();
        $root = $doc->createElement('d:anything');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);
        $server->setBaseUri('/bla/');

        $href->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:anything xmlns:d="DAV:"><d:href>/bla/path</d:href></d:anything>
', $xml);

    }

    function testSerializeNoPrefix() {

        $href = new Sabre_DAV_Property_Href('path',false);
        $this->assertEquals('path',$href->getHref());

        $doc = new DOMDocument();
        $root = $doc->createElement('d:anything');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);
        $server->setBaseUri('/bla/');

        $href->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:anything xmlns:d="DAV:"><d:href>path</d:href></d:anything>
', $xml);

    }

    function testUnserialize() {

        $xml = '<?xml version="1.0"?>
<d:anything xmlns:d="urn:DAV"><d:href>/bla/path</d:href></d:anything>
';

        $dom = new DOMDocument();
        $dom->loadXML($xml);

        $href = Sabre_DAV_Property_Href::unserialize($dom->firstChild);
        $this->assertEquals('/bla/path',$href->getHref());

    }

    function testUnserializeIncompatible() {

        $xml = '<?xml version="1.0"?>
<d:anything xmlns:d="urn:DAV"><d:href2>/bla/path</d:href2></d:anything>
';

        $dom = new DOMDocument();
        $dom->loadXML($xml);

        $href = Sabre_DAV_Property_Href::unserialize($dom->firstChild);
        $this->assertNull($href);

    } 

}

<?php

class Sabre_DAV_Property_ResponseTest extends PHPUnit_Framework_TestCase {

    function testSimple() {

        $innerProps = array(
            200 => array(
                '{DAV:}displayname' => 'my file',
            ),
            404 => array(
                '{DAV:}owner' => null,
            )
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $this->assertEquals('uri',$property->getHref());
        $this->assertEquals($innerProps,$property->getResponseProperties());
               

    }

    /**
     * @depends testSimple
     */
    function testSerialize() {

        $innerProps = array(
            200 => array(
                '{DAV:}displayname' => 'my file',
            ),
            404 => array(
                '{DAV:}owner' => null,
            )
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:root xmlns:d="DAV:">' .
'<d:response>' .
'<d:href>/uri</d:href>' .
'<d:propstat>' . 
'<d:prop>' .
'<d:displayname>my file</d:displayname>' . 
'</d:prop>' . 
'<d:status>HTTP/1.1 200 Ok</d:status>' . 
'</d:propstat>' . 
'<d:propstat>' . 
'<d:prop>' .
'<d:owner/>' . 
'</d:prop>' .
'<d:status>HTTP/1.1 404 Not Found</d:status>' . 
'</d:propstat>' . 
'</d:response>' . 
'</d:root>
', $xml);

    }

    /**
     * This one is specifically for testing properties with no namespaces, which is legal xml
     *
     * @depends testSerialize
     */
    function testSerializeEmptyNamespace() {

        $innerProps = array(
            200 => array(
                '{}propertyname' => 'value',
            ),
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:root xmlns:d="DAV:">' .
'<d:response>' .
'<d:href>/uri</d:href>' .
'<d:propstat>' . 
'<d:prop>' .
'<propertyname xmlns="">value</propertyname>' . 
'</d:prop>' . 
'<d:status>HTTP/1.1 200 Ok</d:status>' . 
'</d:propstat>' . 
'</d:response>' . 
'</d:root>
', $xml);

    }

    /**
     * This one is specifically for testing properties with no namespaces, which is legal xml
     *
     * @depends testSerialize
     */
    function testSerializeCustomNamespace() {

        $innerProps = array(
            200 => array(
                '{http://sabredav.org/NS/example}propertyname' => 'value',
            ),
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:root xmlns:d="DAV:">' .
'<d:response>' .
'<d:href>/uri</d:href>' .
'<d:propstat>' . 
'<d:prop>' .
'<x2:propertyname xmlns:x2="http://sabredav.org/NS/example">value</x2:propertyname>' . 
'</d:prop>' . 
'<d:status>HTTP/1.1 200 Ok</d:status>' . 
'</d:propstat>' . 
'</d:response>' . 
'</d:root>
', $xml);

    }

    /**
     * @depends testSerialize
     */
    function testSerializeComplexProperty() {

        $innerProps = array(
            200 => array(
                '{DAV:}link' => new Sabre_DAV_Property_Href('http://sabredav.org/', false)
            ),
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

        $xml = $doc->saveXML();

        $this->assertEquals(
'<?xml version="1.0"?>
<d:root xmlns:d="DAV:">' .
'<d:response>' .
'<d:href>/uri</d:href>' .
'<d:propstat>' . 
'<d:prop>' .
'<d:link><d:href>http://sabredav.org/</d:href></d:link>' .  
'</d:prop>' . 
'<d:status>HTTP/1.1 200 Ok</d:status>' . 
'</d:propstat>' . 
'</d:response>' . 
'</d:root>
', $xml);

    }

    /**
     * @depends testSerialize
     * @expectedException Sabre_DAV_Exception
     */
    function testSerializeBreak() {

        $innerProps = array(
            200 => array(
                '{DAV:}link' => new STDClass()
            ),
        );

        $property = new Sabre_DAV_Property_Response('uri',$innerProps);

        $doc = new DOMDocument();
        $root = $doc->createElement('d:root');
        $root->setAttribute('xmlns:d','DAV:');

        $doc->appendChild($root);
        $objectTree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('rootdir'));
        $server = new Sabre_DAV_Server($objectTree);

        $property->serialize($server, $root);

    }

}

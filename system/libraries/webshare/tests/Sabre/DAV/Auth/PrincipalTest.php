<?php

class Sabre_DAV_Auth_PrincipalTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin');
        $this->assertTrue($principal instanceof Sabre_DAV_Auth_Principal);

    }

    public function testGetName() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin');
        $this->assertEquals('admin',$principal->getName());

    }

    public function testGetDisplayName() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin');
        $this->assertEquals('admin',$principal->getDisplayname());

        $principal = new Sabre_DAV_Auth_Principal('principals/admin',array(
            '{DAV:}displayname' => 'Mr. Admin'
        ));
        $this->assertEquals('Mr. Admin',$principal->getDisplayname());

    }

    public function testGetPropertiesAll() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin',array(
            '{DAV:}displayname' => 'Mr. Admin',
            '{http://www.example.org/custom}custom' => 'Custom',
            '{http://sabredav.org/ns}email-address' => 'admin@example.org',
        ));

        $props = $principal->getProperties(array());
        $keys = array(
            '{DAV:}resourcetype',
            '{DAV:}displayname',
        );

        $this->assertEquals($keys,array_keys($props));
        $this->assertEquals('Mr. Admin',$props['{DAV:}displayname']);
        $this->assertEquals('{DAV:}principal',$props['{DAV:}resourcetype']->getValue());


    }

    public function testGetProperties() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin',array(
            '{DAV:}displayname' => 'Mr. Admin',
            '{http://www.example.org/custom}custom' => 'Custom',
            '{http://sabredav.org/ns}email-address' => 'admin@example.org',
        ));

        $keys = array(
            '{DAV:}resourcetype',
            '{DAV:}displayname',
            '{http://www.example.org/custom}custom',
            '{DAV:}alternate-URI-set',
            '{DAV:}principal-URL',
            '{DAV:}group-member-set',
            '{DAV:}group-membership',
            '{http://sabredav.org/ns}email-address',
        );
        $props = $principal->getProperties($keys);

        foreach($keys as $key) $this->assertArrayHasKey($key,$props);

        $this->assertEquals('Mr. Admin',$props['{DAV:}displayname']);
        $this->assertEquals('{DAV:}principal',$props['{DAV:}resourcetype']->getValue());

        $this->assertEquals('principals/admin',$props['{DAV:}principal-URL']->getHref());
        $this->assertNull($props['{DAV:}group-member-set']);
        $this->assertNull($props['{DAV:}group-membership']);

        $this->assertEquals('admin@example.org', $props['{http://sabredav.org/ns}email-address']);
        $this->assertType('Sabre_DAV_Property_IHref', $props['{DAV:}alternate-URI-set']);
        $this->assertEquals('mailto:admin@example.org', $props['{DAV:}alternate-URI-set']->getHref());
    }

    /**
     * @depends testGetProperties
     */
    public function testGetPropertiesNoEmail() {

        $principal = new Sabre_DAV_Auth_Principal('principals/admin',array(
            '{DAV:}displayname' => 'Mr. Admin',
            '{http://www.example.org/custom}custom' => 'Custom',
        ));

        $keys = array(
            '{DAV:}alternate-URI-set',
        );
        $props = $principal->getProperties($keys);

        foreach($keys as $key) $this->assertArrayHasKey($key,$props);

        $this->assertType('null', $props['{DAV:}alternate-URI-set']);
    }

    public function testUpdateProperties() {
        
        $principal = new Sabre_DAV_Auth_Principal('principals/admin');
        $result = $principal->updateProperties(array('{DAV:}yourmom'=>'test'));
        $this->assertEquals(false,$result);

    }

}

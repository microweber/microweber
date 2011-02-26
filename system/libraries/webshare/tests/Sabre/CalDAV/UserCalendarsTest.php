<?php

require_once 'Sabre/CalDAV/TestUtil.php';
require_once 'Sabre/DAV/Auth/MockBackend.php';

/**
 * @covers Sabre_CalDAV_UserCalendars
 */
class Sabre_CalDAV_UserCalendarsTest extends PHPUnit_Framework_TestCase {

    protected $usercalendars;
    protected $backend;
    protected $authBackend;

    function setup() {

        if (!SABRE_HASSQLITE) $this->markTestSkipped('SQLite driver is not available');
        $this->backend = Sabre_CalDAV_TestUtil::getBackend();
        $this->authBackend = new Sabre_DAV_Auth_MockBackend('realm');
        //$this->authBackend->setCurrentUser('principals/user1');
        $this->usercalendars = new Sabre_CalDAV_UserCalendars($this->authBackend, $this->backend, 'principals/user1'); 

    }

    function testSimple() {

        $this->assertEquals('user1',$this->usercalendars->getName()); 

    }

    /**
     * @expectedException Sabre_DAV_Exception_FileNotFound
     * @depends testSimple
     */
    function testGetChildNotFound() {

        $this->usercalendars->getChild('randomname');

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     * @depends testSimple
     */
    function testSetName() {

        $this->usercalendars->setName('bla');

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     * @depends testSimple
     */
    function testDelete() {

        $this->usercalendars->delete();

    }

    /**
     * @depends testSimple
     */
    function testGetLastModified() {

        $this->assertNull($this->usercalendars->getLastModified());

    }

    /**
     * @expectedException Sabre_DAV_Exception_MethodNotAllowed
     * @depends testSimple
     */
    function testCreateFile() {

        $this->usercalendars->createFile('bla');

    }


    /**
     * @expectedException Sabre_DAV_Exception_MethodNotAllowed
     * @depends testSimple
     */
    function testCreateDirectory() {

        $this->usercalendars->createDirectory('bla');

    }

    /**
     * @depends testSimple
     */
    function testCreateExtendedCollection() {

        $result = $this->usercalendars->createExtendedCollection('newcalendar', array('{DAV:}collection', '{urn:ietf:params:xml:ns:caldav}calendar'), array());
        $this->assertNull($result);
        $cals = $this->backend->getCalendarsForUser('principals/user1');
        $this->assertEquals(2,count($cals));

    }

    /**
     * @expectedException Sabre_DAV_Exception_InvalidResourceType
     * @depends testSimple
     */
    function testCreateExtendedCollectionBadResourceType() {

        $this->usercalendars->createExtendedCollection('newcalendar', array('{DAV:}collection','{DAV:}blabla'), array());

    }


}

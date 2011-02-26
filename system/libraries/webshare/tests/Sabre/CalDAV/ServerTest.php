<?php

require_once 'Sabre/CalDAV/TestUtil.php';

class Sabre_CalDAV_ServerTest extends PHPUnit_Framework_TestCase {

    /**
     * The CalDAV server is a simple script that just composes a 
     * Sabre_DAV_Server. All we really have to do is check if the setup
     * is done correctly.
     */
    function testSetup() {

        if (!SABRE_HASSQLITE) $this->markTestSkipped('SQLite driver is not available');
        $pdo = Sabre_CalDAV_TestUtil::getSQLiteDB();
        $server = new Sabre_CalDAV_Server($pdo);

        $authPlugin = $server->getPlugin('Sabre_DAV_Auth_Plugin');
        $this->assertTrue($authPlugin instanceof Sabre_DAV_Auth_Plugin);

        $caldavPlugin = $server->getPlugin('Sabre_CalDAV_Plugin');
        $this->assertTrue($caldavPlugin instanceof Sabre_CalDAV_Plugin);

        $node = $server->tree->getNodeForPath('');
        $this->assertTrue($node instanceof Sabre_DAV_SimpleDirectory);

        $this->assertEquals('root', $node->getName());

    }

}

<?php

abstract class Sabre_DAV_Auth_Backend_AbstractPDOTest extends PHPUnit_Framework_TestCase {

    abstract function getPDO();

    function testConstruct() {

        $pdo = $this->getPDO();
        $backend = new Sabre_DAV_Auth_Backend_PDO($pdo);
        $this->assertTrue($backend instanceof Sabre_DAV_Auth_Backend_PDO);

    }

    /**
     * @depends testConstruct
     */
    function testUserInfo() {

        $pdo = $this->getPDO();
        $backend = new Sabre_DAV_Auth_Backend_PDO($pdo);

        $expected = array(
            array(
                'uri' => 'principals/user',
                '{http://sabredav.org/ns}email-address' => 'user@example.org',
            ),
        );

        $this->assertEquals($expected, $backend->getUsers());
        $this->assertFalse($backend->getUserInfo('realm','blabla'));

        $expected = array(
            'uri' => 'principals/user',
            'digestHash' => 'hash',
            '{http://sabredav.org/ns}email-address' => 'user@example.org',
        );

        $this->assertEquals($expected, $backend->getUserInfo('realm','user'));

    }

}

<?php

class Sabre_DAV_ExceptionTest extends PHPUnit_Framework_TestCase {

    function testStatus() {

        $e = new Sabre_DAV_Exception();
        $this->assertEquals(500,$e->getHTTPCode());

    } 

    function testExceptionStatuses() {

        $c = array(
            'Sabre_DAV_Exception_NotAuthenticated'    => 401,
            'Sabre_DAV_Exception_InsufficientStorage' => 507,
        );

        foreach($c as $class=>$status) {

            $obj = new $class();
            $this->assertEquals($status, $obj->getHTTPCode());

        }

    }

}

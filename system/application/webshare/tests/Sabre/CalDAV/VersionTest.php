<?php

class Sabre_CalDAV_VersionTest extends PHPUnit_Framework_TestCase {

    function testString() {

        $v = Sabre_CalDAV_Version::VERSION;
        $this->assertEquals(-1, version_compare('1.0.0',$v));

        $s = Sabre_CalDAV_Version::STABILITY;
        $this->assertTrue($s == 'alpha' || $s == 'beta' || $s =='stable');

    }

}

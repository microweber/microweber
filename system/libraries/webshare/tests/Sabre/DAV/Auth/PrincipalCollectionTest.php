<?php

require_once 'Sabre/DAV/Auth/MockBackend.php';

class Sabre_DAV_Auth_PrincipalCollectionTest extends PHPUnit_Framework_TestCase {

    public function testBasic() {

        $backend = new Sabre_DAV_Auth_MockBackend();
        $pc = new Sabre_DAV_Auth_PrincipalCollection($backend);
        $this->assertTrue($pc instanceof Sabre_DAV_Auth_PrincipalCollection);

        $this->assertEquals(Sabre_DAV_Auth_PrincipalCollection::NODENAME,$pc->getName());

    }

    /**
     * @depends testBasic
     */
    public function testGetChildren() {

        $backend = new Sabre_DAV_Auth_MockBackend();
        $pc = new Sabre_DAV_Auth_PrincipalCollection($backend);
        
        $children = $pc->getChildren();
        $this->assertTrue(is_array($children));

        foreach($children as $child) {
            $this->assertTrue($child instanceof Sabre_DAV_Auth_Principal);
        }

    }

}

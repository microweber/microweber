<?php

class Sabre_DAV_BasicNodeTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testPut() {

        $file = new Sabre_DAV_FileMock();
        $file->put('hi');

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testGet() {

        $file = new Sabre_DAV_FileMock();
        $file->get();

    }

    public function testGetSize() {

        $file = new Sabre_DAV_FileMock();
        $this->assertEquals(0,$file->getSize());

    }


    public function testGetETag() {

        $file = new Sabre_DAV_FileMock();
        $this->assertNull($file->getETag());

    }

    public function testGetContentType() {

        $file = new Sabre_DAV_FileMock();
        $this->assertNull($file->getContentType());

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testDelete() {

        $file = new Sabre_DAV_FileMock();
        $file->delete();

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testSetName() {

        $file = new Sabre_DAV_FileMock();
        $file->setName('hi');

    }

    public function testGetLastModified() {

        $file = new Sabre_DAV_FileMock();
        // checking if lastmod is within the range of a few seconds
        $lastMod = $file->getLastModified();
        $compareTime = ($lastMod + 1)-time();
        $this->assertTrue($compareTime < 3);

    }

    public function testGetChild() {

        $dir = new Sabre_DAV_DirectoryMock();
        $file = $dir->getChild('mockfile');
        $this->assertTrue($file instanceof Sabre_DAV_FileMock); 

    }

    public function testChildExists() {

        $dir = new Sabre_DAV_DirectoryMock();
        $this->assertTrue($dir->childExists('mockfile')); 

    }

    public function testChildExistsFalse() {

        $dir = new Sabre_DAV_DirectoryMock();
        $this->assertFalse($dir->childExists('mockfile2')); 

    }

    /**
     * @expectedException Sabre_DAV_Exception_FileNotFound
     */
    public function testGetChild404() {

        $dir = new Sabre_DAV_DirectoryMock();
        $file = $dir->getChild('blabla');

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testCreateFile() {

        $dir = new Sabre_DAV_DirectoryMock();
        $dir->createFile('hello','data');

    }

    /**
     * @expectedException Sabre_DAV_Exception_Forbidden
     */
    public function testCreateDirectory() {

        $dir = new Sabre_DAV_DirectoryMock();
        $dir->createDirectory('hello');

    }

    public function testSimpleDirectoryConstruct() {

        $dir = new Sabre_DAV_SimpleDirectory('simpledir',array());

    }

    /**
     * @depends testSimpleDirectoryConstruct
     */
    public function testSimpleDirectoryConstructChild() {

        $file = new Sabre_DAV_FileMock();
        $dir = new Sabre_DAV_SimpleDirectory('simpledir',array($file));
        $file2 = $dir->getChild('mockfile');

        $this->assertEquals($file,$file2);

    }

    /**
     * @expectedException Sabre_DAV_Exception
     * @depends testSimpleDirectoryConstruct
     */
    public function testSimpleDirectoryBadParam() {

        $dir = new Sabre_DAV_SimpleDirectory('simpledir',array('string shouldn\'t be here'));

    }

    /**
     * @depends testSimpleDirectoryConstruct
     */
    public function testSimpleDirectoryAddChild() {

        $file = new Sabre_DAV_FileMock();
        $dir = new Sabre_DAV_SimpleDirectory('simpledir');
        $dir->addChild($file);
        $file2 = $dir->getChild('mockfile');

        $this->assertEquals($file,$file2);

    }

    /**
     * @depends testSimpleDirectoryConstruct
     * @depends testSimpleDirectoryAddChild
     */
    public function testSimpleDirectoryGetChildren() {

        $file = new Sabre_DAV_FileMock();
        $dir = new Sabre_DAV_SimpleDirectory('simpledir');
        $dir->addChild($file);

        $this->assertEquals(array($file),$dir->getChildren());

    }

    /*
     * @depends testSimpleDirectoryConstruct
     */
    public function testSimpleDirectoryGetName() {

        $dir = new Sabre_DAV_SimpleDirectory('simpledir');
        $this->assertEquals('simpledir',$dir->getName());

    }

    /**
     * @depends testSimpleDirectoryConstruct
     * @expectedException Sabre_DAV_Exception_FileNotFound
     */
    public function testSimpleDirectoryGetChild404() {

        $dir = new Sabre_DAV_SimpleDirectory('simpledir');
        $dir->getChild('blabla');

    }
}

class Sabre_DAV_DirectoryMock extends Sabre_DAV_Directory {

    function getName() {

        return 'mockdir';

    }

    function getChildren() {

        return array(new Sabre_DAV_FileMock());

    }

}

class Sabre_DAV_FileMock extends Sabre_DAV_File {

    function getName() {

        return 'mockfile';

    }

}

<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_FSExt_NodeTest extends PHPUnit_Framework_TestCase {

    function setUp() {

        mkdir(SABRE_TEMPDIR . '/dir');
        file_put_contents(SABRE_TEMPDIR . '/dir/file.txt', 'Contents');
        file_put_contents(SABRE_TEMPDIR . '/dir/file2.txt', 'Contents2');

    }

    function tearDown() {

        Sabre_TestUtil::clearTempDir();

    }

    function testGetLocksEmpty() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $this->assertEquals(array(),$file->getLocks());

    }

    function testLock() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $lockInfo = new Sabre_DAV_Locks_LockInfo();
       $lockInfo->owner = 'me';
       $lockInfo->token = 'SOMERANDOMSTRING';
       $file->lock($lockInfo);

       $result = $file->getLocks();
       $this->assertEquals(1,count($result));

       $this->assertEquals('me',$result[0]->owner);
       $this->assertEquals('SOMERANDOMSTRING',$result[0]->token);

    }

    
    /**
     * @depends testLock 
     */
    function testLockAgain() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $lockInfo = new Sabre_DAV_Locks_LockInfo();
       $lockInfo->owner = 'me';
       $lockInfo->token = 'SOMERANDOMSTRING';
       $file->lock($lockInfo);

       $lockInfo->owner = 'me2';
       $lockInfo->token = 'SOMERANDOMSTRING2';
       $file->lock($lockInfo);

       $result = $file->getLocks();
       $this->assertEquals(2,count($result));

       $this->assertEquals('me',$result[0]->owner);
       $this->assertEquals('SOMERANDOMSTRING',$result[0]->token);

       $this->assertEquals('me2',$result[1]->owner);
       $this->assertEquals('SOMERANDOMSTRING2',$result[1]->token);

    }

    /**
     * @depends testLock
     */
    function testLockRefresh() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $lockInfo = new Sabre_DAV_Locks_LockInfo();
       $lockInfo->owner = 'me';
       $lockInfo->token = 'SOMERANDOMSTRING';
       $file->lock($lockInfo);

       $lockInfo->owner = 'you';
       $lockInfo->token = 'SOMERANDOMSTRING';
       $file->lock($lockInfo);

       $result = $file->getLocks();
       $this->assertEquals(1,count($result));
       list($result) = $result;
    
       $this->assertEquals('you',$result->owner,'Wrong owner. We received: ' . print_r($result,true));
       $this->assertEquals('SOMERANDOMSTRING',$result->token);


    }

    /**
     * @depends testLock
     */
    function testUnlock() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $lockInfo = new Sabre_DAV_Locks_LockInfo();
       $lockInfo->owner = 'me';
       $lockInfo->token = 'SOMERANDOMSTRING';

       $file->lock($lockInfo);

       $result = $file->getLocks();
       $this->assertEquals(1,count($result));

       $file->unlock($lockInfo);
       $result = $file->getLocks();
       $this->assertEquals(0,count($result));

    }

    /**
     * @depends testUnlock
     */
    function testUnlockNotFound() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
       $lockInfo = new Sabre_DAV_Locks_LockInfo();
       $lockInfo->owner = 'me';
       $lockInfo->token = 'SOMERANDOMSTRING';

       $result = $file->unlock($lockInfo);
       $this->assertFalse($result);

    }

    function testUpdateProperties() {
        
        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
        $properties = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        );

        $result = $file->updateProperties($properties);
        $expected = true;

        $this->assertEquals($expected, $result);

        $getProperties = $file->getProperties(array_keys($properties));

        $this->assertEquals($properties, $getProperties);

    }

    /**
     * @depends testUpdateProperties
     */
    function testUpdatePropertiesAgain() {

        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        );

        $result = $file->updateProperties($mutations);
        
        $this->assertEquals(true, $result);

        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test3' => 'baz',
        );

        $result = $file->updateProperties($mutations);
        
        $this->assertEquals(true, $result);
    }

    /**
     * @depends testUpdateProperties
     */
    function testUpdatePropertiesDelete() {

        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');

        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        );

        $result = $file->updateProperties($mutations);
        
        $this->assertEquals(true, $result);

        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => null, 
            '{http://sabredav.org/NS/2010}test3' => null 
        );

        $result = $file->updateProperties($mutations);

        $this->assertEquals(true, $result);

        $properties = $file->getProperties(array('http://sabredav.org/NS/2010}test1','{http://sabredav.org/NS/2010}test2','{http://sabredav.org/NS/2010}test3'));

        $this->assertEquals(array(
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        ), $properties);
    }

    /**
     * @depends testUpdateProperties
     */
    function testUpdatePropertiesMove() {

        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');

        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        );

        $result = $file->updateProperties($mutations);

        $this->assertEquals(true, $result);

        $properties = $file->getProperties(array('{http://sabredav.org/NS/2010}test1','{http://sabredav.org/NS/2010}test2','{http://sabredav.org/NS/2010}test3'));

        $this->assertEquals(array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        ), $properties);

        // Renaming
        $file->setName('file3.txt');

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/dir/file.txt'));
        $this->assertTrue(file_exists(SABRE_TEMPDIR . '/dir/file3.txt'));
        $this->assertEquals('file3.txt',$file->getName());

        $newFile = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file3.txt');
        $this->assertEquals('file3.txt',$newFile->getName());

        $properties = $newFile->getProperties(array('{http://sabredav.org/NS/2010}test1','{http://sabredav.org/NS/2010}test2','{http://sabredav.org/NS/2010}test3'));

        $this->assertEquals(array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        ), $properties);
    }

    /**
     * @depends testUpdatePropertiesMove
     */
    function testUpdatePropertiesDeleteBleed() {

        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
        $mutations = array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        );

        $result = $file->updateProperties($mutations);
        
        $this->assertEquals(true, $result);

        $properties = $file->getProperties(array('{http://sabredav.org/NS/2010}test1','{http://sabredav.org/NS/2010}test2','{http://sabredav.org/NS/2010}test3'));

        $this->assertEquals(array(
            '{http://sabredav.org/NS/2010}test1' => 'foo',
            '{http://sabredav.org/NS/2010}test2' => 'bar',
        ), $properties);

        // Deleting 
        $file->delete();

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/dir/file.txt'));

        // Creating it again
        file_put_contents(SABRE_TEMPDIR . '/dir/file.txt','New Contents');
        $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');

        $properties = $file->getProperties(array('http://sabredav.org/NS/2010}test1','{http://sabredav.org/NS/2010}test2','{http://sabredav.org/NS/2010}test3'));

        $this->assertEquals(array(), $properties);

    }

    /**
     * @depends testLock
     */
    function testMultipleFilesInDir() {

        $file1 = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file.txt');
        $lockInfo = new Sabre_DAV_Locks_LockInfo();
        $lockInfo->owner = 'me';
        $lockInfo->token = 'SOMERANDOMSTRING';
        $file1->lock($lockInfo);

        // What we're testing is, to make sure that changing resourcedata
        // from one file does not mess with resourcedata from another

        $file2 = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/dir/file2.txt');
        $result = $file2->getLocks();

        $this->assertEquals(array(), $result);

    }

}

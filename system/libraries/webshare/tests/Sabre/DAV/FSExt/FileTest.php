<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_FSExt_FileTest extends PHPUnit_Framework_TestCase {

    function setUp() {

        file_put_contents(SABRE_TEMPDIR . '/file.txt', 'Contents');

    }

    function tearDown() {

        Sabre_TestUtil::clearTempDir();

    }

    function testPut() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt'); 
       $file->put('New contents');

       $this->assertEquals('New contents',file_get_contents(SABRE_TEMPDIR . '/file.txt'));

    }

    function testGet() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt');
       $this->assertEquals('Contents',stream_get_contents($file->get()));

    }

    function testDelete() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt');
       $file->delete();

       $this->assertFalse(file_exists(SABRE_TEMPDIR . '/file.txt'));

    }

    function testGetETag() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt');
       $this->assertEquals('"' . md5('Contents') . '"',$file->getETag());

    }

    function testGetContentType() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt');
       $this->assertNull($file->getContentType());

    }

    function testGetSize() {

       $file = new Sabre_DAV_FSExt_File(SABRE_TEMPDIR . '/file.txt');
       $this->assertEquals(8,$file->getSize());

    }

}

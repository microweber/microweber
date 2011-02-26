<?php

/**
 * @covers Sabre_DAV_Tree
 * @covers Sabre_DAV_Tree_Filesystem
 * @covers Sabre_DAV_FS_Node
 * @covers Sabre_DAV_FS_File
 * @covers Sabre_DAV_FS_Directory
 */
class Sabre_DAV_Tree_FilesystemTest extends PHPUnit_Framework_TestCase {

    function setUp() {

        Sabre_TestUtil::clearTempDir();
        file_put_contents(SABRE_TEMPDIR. '/file.txt','Body');
        mkdir(SABRE_TEMPDIR.'/dir');
        file_put_contents(SABRE_TEMPDIR.'/dir/subfile.txt','Body');

    }

    function tearDown() {

        Sabre_TestUtil::clearTempDir();

    }

    function testGetNodeForPath_File() {

        $fs = new Sabre_DAV_Tree_Filesystem(SABRE_TEMPDIR);
        $node = $fs->getNodeForPath('file.txt');
        $this->assertTrue($node instanceof Sabre_DAV_FS_File);

    }

    function testGetNodeForPath_Directory() {

        $fs = new Sabre_DAV_Tree_Filesystem(SABRE_TEMPDIR);
        $node = $fs->getNodeForPath('dir');
        $this->assertTrue($node instanceof Sabre_DAV_FS_Directory);

    }

    function testCopy() {

        $fs = new Sabre_DAV_Tree_Filesystem(SABRE_TEMPDIR);
        $fs->copy('file.txt','file2.txt');
        $this->assertTrue(file_exists(SABRE_TEMPDIR . '/file2.txt'));
        $this->assertEquals('Body',file_get_contents(SABRE_TEMPDIR . '/file2.txt'));

    }

    function testCopyDir() {

        $fs = new Sabre_DAV_Tree_Filesystem(SABRE_TEMPDIR);
        $fs->copy('dir','dir2');
        $this->assertTrue(file_exists(SABRE_TEMPDIR . '/dir2'));
        $this->assertEquals('Body',file_get_contents(SABRE_TEMPDIR . '/dir2/subfile.txt'));

    }

    function testMove() {

        $fs = new Sabre_DAV_Tree_Filesystem(SABRE_TEMPDIR);
        $fs->move('file.txt','file2.txt');
        $this->assertTrue(file_exists(SABRE_TEMPDIR . '/file2.txt'));
        $this->assertTrue(!file_exists(SABRE_TEMPDIR . '/file.txt'));
        $this->assertEquals('Body',file_get_contents(SABRE_TEMPDIR . '/file2.txt'));

    }


}

<?php

/**
 * @covers Sabre_DAV_Tree
 */
class Sabre_DAV_TreeTest extends PHPUnit_Framework_TestCase {

    function testNodeExists() {

        $tree = new Sabre_DAV_TreeMock();

        $this->assertTrue($tree->nodeExists('hi'));
        $this->assertFalse($tree->nodeExists('hello'));

    }

    function testCopy() {

        $tree = new Sabre_DAV_TreeMock();
        $tree->copy('hi','hi2');

        $this->assertArrayHasKey('hi2', $tree->getNodeForPath('')->newDirectories);
        $this->assertEquals('foobar', $tree->getNodeForPath('hi/file')->get());
        $this->assertEquals(array('test1'=>'value'), $tree->getNodeForPath('hi/file')->getProperties(array()));

    }

    function testMove() {

        $tree = new Sabre_DAV_TreeMock();
        $tree->move('hi','hi2');

        $this->assertEquals('hi2', $tree->getNodeForPath('hi')->getName());
        $this->assertTrue($tree->getNodeForPath('hi')->isRenamed);

    }

    function testDeepMove() {

        $tree = new Sabre_DAV_TreeMock();
        $tree->move('hi/sub','hi2');

        $this->assertArrayHasKey('hi2', $tree->getNodeForPath('')->newDirectories);
        $this->assertTrue($tree->getNodeForPath('hi/sub')->isDeleted);

    }

    function testDelete() {

        $tree = new Sabre_DAV_TreeMock();
        $tree->delete('hi');
        $this->assertTrue($tree->getNodeForPath('hi')->isDeleted);

    }

    function testGetChildren() {

        $tree = new Sabre_DAV_TreeMock();
        $children = $tree->getChildren('');
        $this->assertEquals(1,count($children));
        $this->assertEquals('hi', $children[0]->getName());

    }

}

class Sabre_DAV_TreeMock extends Sabre_DAV_Tree {

    private $nodes = array();

    function __construct() {

        $this->nodes['hi/sub'] = new Sabre_DAV_TreeDirectoryTester('sub');
        $this->nodes['hi/file'] = new Sabre_DAV_TreeFileTester('file');
        $this->nodes['hi/file']->properties = array('test1' => 'value');
        $this->nodes['hi/file']->data = 'foobar';
        $this->nodes['hi'] = new Sabre_DAV_TreeDirectoryTester('hi',array($this->nodes['hi/sub'], $this->nodes['hi/file']));
        $this->nodes[''] = new Sabre_DAV_TreeDirectoryTester('hi', array($this->nodes['hi']));

    }

    function getNodeForPath($path) {

        if (isset($this->nodes[$path])) return $this->nodes[$path];
        throw new Sabre_DAV_Exception_FileNotFound('item not found');

    }

}

class Sabre_DAV_TreeDirectoryTester extends Sabre_DAV_SimpleDirectory {

    public $newDirectories = array();
    public $newFiles = array();
    public $isDeleted = false;
    public $isRenamed = false;

    function createDirectory($name) {

        $this->newDirectories[$name] = true;

    }

    function createFile($name,$data = null) {

        $this->newFiles[$name] = $data;

    }

    function getChild($name) {

        if (isset($this->newDirectories[$name])) return new Sabre_DAV_TreeDirectoryTester($name);
        if (isset($this->newFiles[$name])) return new Sabre_DAV_TreeFileTester($name, $this->newFiles[$name]);
        return parent::getChild($name);

    }

    function delete() {

        $this->isDeleted = true;

    }

    function setName($name) {

        $this->isRenamed = true;
        $this->name = $name;

    }

}

class Sabre_DAV_TreeFileTester extends Sabre_DAV_File implements Sabre_DAV_IProperties {

    public $name;
    public $data;
    public $properties;

    function __construct($name, $data = null) {

        $this->name = $name;
        if (is_null($data)) $data = 'bla';
        $this->data = $data;

    }

    function getName() {

        return $this->name;

    }

    function get() {

        return $this->data;

    }

    function getProperties($properties) {

        return $this->properties;

    }

    function updateProperties($properties) {

        $this->properties = $properties;
        return true;

    }

}


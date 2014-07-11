<?php
/**
 * @file
 * CSS Event handling tests
 */
namespace QueryPath\Tests;

require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\Token;
use \QueryPath\CSS\DOMTraverser;
use \QueryPath\CSS\Parser;
use \QueryPath\CSS\EventHandler;

define('TRAVERSER_XML', __DIR__ . '/../../../DOMTraverserTest.xml');

/**
 * @ingroup querypath_tests
 * @group CSS
 */
class DOMTraverserTest extends TestCase {

  protected $xml_file = TRAVERSER_XML;
  public function debug($msg) {
    fwrite(STDOUT, PHP_EOL . $msg);
  }

  public function testConstructor() {
    $dom = new \DOMDocument('1.0');
    $dom->load($this->xml_file);

    $splos = new \SPLObjectStorage();
    $splos->attach($dom);

    $traverser = new DOMTraverser($splos);

    $this->assertInstanceOf('\QueryPath\CSS\Traverser', $traverser);
    $this->assertInstanceOf('\QueryPath\CSS\DOMTraverser', $traverser);
  }

  protected function traverser() {
    $dom = new \DOMDocument('1.0');
    $dom->load($this->xml_file);

    $splos = new \SPLObjectStorage();
    $splos->attach($dom->documentElement);

    $traverser = new DOMTraverser($splos);

    return $traverser;
  }

  protected function find($selector) {
    return $this->traverser()->find($selector)->matches();
  }

  public function testFind() {
    $res = $this->traverser()->find('root');

    // Ensure that return contract is not violated.
    $this->assertInstanceOf('\QueryPath\CSS\Traverser', $res);
  }

  public function testMatches() {
    $res = $this->traverser()->matches();
    $this->assertEquals(1, count($res));
  }

  public function testMatchElement() {
    // Canary: If element does not exist, must return FALSE.
    $matches = $this->find('NO_SUCH_ELEMENT');
    $this->assertEquals(0, count($matches));

    // Test without namespace
    $matches = $this->find('root');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('crowded');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('outside');
    $this->assertEquals(3, count($matches));

    // Check nested elements.
    $matches = $this->find('a');
    $this->assertEquals(3, count($matches));

    // Test wildcard.
    $traverser = $this->traverser();
    $matches = $traverser->find('*')->matches();
    $actual= $traverser->getDocument()->getElementsByTagName('*');
    $this->assertEquals($actual->length, count($matches));


    // Test with namespace
    //$this->markTestIncomplete();
    $matches = $this->find('ns_test');
    $this->assertEquals(3, count($matches));

    $matches = $this->find('test|ns_test');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('test|ns_test>ns_attr');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('*|ns_test');
    $this->assertEquals(3, count($matches));

    $matches = $this->find('test|*');
    $this->assertEquals(1, count($matches));

    // Test where namespace is declared on the element.
    $matches = $this->find('newns|my_element');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('test|ns_test>newns|my_element');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('test|*>newns|my_element');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('*|ns_test>newns|my_element');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('*|*>newns|my_element');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('*>newns|my_element');
    $this->assertEquals(1, count($matches));
  }

  public function testMatchAttributes() {

    $matches = $this->find('crowded[attr1]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('crowded[attr1=one]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('crowded[attr2^=tw]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('classtest[class~=two]');
    $this->assertEquals(1, count($matches));
    $matches = $this->find('classtest[class~=one]');
    $this->assertEquals(1, count($matches));
    $matches = $this->find('classtest[class~=seven]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('crowded[attr0]');
    $this->assertEquals(0, count($matches));

    $matches = $this->find('[level=1]');
    $this->assertEquals(3, count($matches));

    $matches = $this->find('[attr1]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('[test|myattr]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('[test|myattr=foo]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('[*|myattr=foo]');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('[|myattr=foo]');
    $this->assertEquals(0, count($matches));

    $matches = $this->find('[|level=1]');
    $this->assertEquals(3, count($matches));

    $matches = $this->find('[*|level=1]');
    $this->assertEquals(4, count($matches));

    // Test namespace on attr where namespace
    // is declared on that element
    $matches = $this->find('[nuther|ping]');
    $this->assertEquals(1, count($matches));

    // Test multiple namespaces on an element.
    $matches = $this->find('[*|ping=3]');
    $this->assertEquals(1, count($matches));

    // Test multiple namespaces on an element.
    $matches = $this->find('[*|ping]');
    $this->assertEquals(1, count($matches));
  }

  public function testMatchId() {
    $matches = $this->find('idtest#idtest-one');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('#idtest-one');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('outter#fake');
    $this->assertEquals(0, count($matches));

    $matches = $this->find('#fake');
    $this->assertEquals(0, count($matches));
  }

  public function testMatchClasses() {
    // Basic test.
    $matches = $this->find('a.a1');
    $this->assertEquals(1, count($matches));

    // Count multiple.
    $matches = $this->find('.first');
    $this->assertEquals(2, count($matches));

    // Grab one in the middle of a list.
    $matches = $this->find('.four');
    $this->assertEquals(1, count($matches));

    // One element with two classes.
    $matches = $this->find('.three.four');
    $this->assertEquals(1, count($matches));
  }

  public function testMatchPseudoClasses() {

    $matches = $this->find('ul>li:first');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('ul>li:not(.first)');
    $this->assertEquals(5, count($matches));

  }

  public function testMatchPseudoElements() {
    $matches = $this->find('p::first-line');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('p::first-letter');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('p::before');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('p::after');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('bottom::after');
    $this->assertEquals(0, count($matches));
  }

  public function testCombineAdjacent() {
    // Simple test
    $matches = $this->find('idtest + p');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('p', $m->tagName);
    }

    // Test ignoring PCDATA
    $matches = $this->find('p + one');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('one', $m->tagName);
    }

    // Test that non-adjacent elements don't match.
    $matches = $this->find('idtest + one');
    foreach ($matches as $m) {
      $this->assertEquals('one', $m->tagName);
    }
    $this->assertEquals(0, count($matches), 'Non-adjacents should not match.');

    // Test that elements BEFORE don't match
    $matches = $this->find('one + p');
    foreach ($matches as $m) {
      $this->assertEquals('one', $m->tagName);
    }
    $this->assertEquals(0, count($matches), 'Match only if b is after a');
  }

  public function testCombineSibling() {
    // Canary:
    $matches = $this->find('one ~ two');
    $this->assertEquals(0, count($matches));

    // Canary 2:
    $matches = $this->find('NO_SUCH_ELEMENT ~ two');
    $this->assertEquals(0, count($matches));

    // Simple test
    $matches = $this->find('idtest ~ p');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('p', $m->tagName);
    }

    // Simple test
    $matches = $this->find('outside ~ p');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('p', $m->tagName);
    }

    // Matches only go left, not right.
    $matches = $this->find('p ~ outside');
    $this->assertEquals(0, count($matches));
  }
  public function testCombineDirectDescendant() {
    // Canary:
    $matches = $this->find('one > four');
    $this->assertEquals(0, count($matches));

    $matches = $this->find('two>three');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('three', $m->tagName);
    }

    $matches = $this->find('one > two > three');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('three', $m->tagName);
    }

    $matches = $this->find('a>a>a');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('a>a');
    $this->assertEquals(2, count($matches));
  }
  public function testCombineAnyDescendant() {
    // Canary
    $matches = $this->find('four one');
    $this->assertEquals(0, count($matches));

    $matches = $this->find('one two');
    $this->assertEquals(1, count($matches));
    foreach ($matches as $m) {
      $this->assertEquals('two', $m->tagName);
    }

    $matches = $this->find('one four');
    $this->assertEquals(1, count($matches));

    $matches = $this->find('a a');
    $this->assertEquals(2, count($matches));

    $matches = $this->find('root two four');
    $this->assertEquals(1, count($matches));
  }
  public function testMultipleSelectors() {
    // fprintf(STDOUT, "=========TEST=========\n\n");
    $matches = $this->find('one, two');
    $this->assertEquals(2, count($matches));
  }

}


<?php
/**
 * @file
 * CSS Event handling tests
 */
namespace QueryPath\Tests;

require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\Token;
use \QueryPath\CSS\QueryPathEventHandler;
use \QueryPath\CSS\Parser;
use \QueryPath\CSS\EventHandler;

/**
 * @ingroup querypath_tests
 * @group CSS
 */
class ParserTest extends TestCase {

  private function getMockHandler($method) {
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array($method));
    $mock->expects($this->once())
      ->method($method)
      ->with($this->equalTo('mytest'));
    return $mock;
  }

  public function testElementID() {
    $mock = $this->getMockHandler('elementID');
    $parser = new Parser('#mytest', $mock);
    $parser->parse();

  }

  public function testElement() {

    // Without namespace
    $mock = $this->getMockHandler('element');
    $parser = new Parser('mytest', $mock);
    $parser->parse();

    // With empty namespace
    $mock = $this->getMockHandler('element');
    $parser = new Parser('|mytest', $mock);
    $parser->parse();
  }

  public function testElementNS() {
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('elementNS'));
    $mock->expects($this->once())
      ->method('elementNS')
      ->with($this->equalTo('mytest'), $this->equalTo('myns'));

    $parser = new Parser('myns|mytest', $mock);
    $parser->parse();

    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('elementNS'));
    $mock->expects($this->once())
      ->method('elementNS')
      ->with($this->equalTo('mytest'), $this->equalTo('*'));

    $parser = new Parser('*|mytest', $mock);
    $parser->parse();

    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('anyElementInNS'));
    $mock->expects($this->once())
      ->method('anyElementInNS')
      ->with($this->equalTo('*'));

    $parser = new Parser('*|*', $mock);
    $parser->parse();
  }

  public function testAnyElement() {
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('anyElement', 'element'));
    $mock->expects($this->once())
      ->method('anyElement');

    $parser = new Parser('*', $mock);
    $parser->parse();
  }

  public function testAnyElementInNS() {
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('anyElementInNS', 'element'));
    $mock->expects($this->once())
      ->method('anyElementInNS')
      ->with($this->equalTo('myns'));

    $parser = new Parser('myns|*', $mock);
    $parser->parse();
  }

  public function testElementClass() {
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('elementClass'));
    $mock->expects($this->once())
      ->method('elementClass')
      ->with($this->equalTo('myclass'));

    $parser = new Parser('.myclass', $mock);
    $parser->parse();
  }

  public function testPseudoClass() {

    // Test empty pseudoclass
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('pseudoClass'));
    $mock->expects($this->once())
      ->method('pseudoClass')
      ->with($this->equalTo('mypclass'));

    $parser = new Parser('myele:mypclass', $mock);
    $parser->parse();

    // Test pseudoclass with value
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('pseudoClass'));
    $mock->expects($this->once())
      ->method('pseudoClass')
      ->with($this->equalTo('mypclass'), $this->equalTo('myval'));

    $parser = new Parser('myele:mypclass(myval)', $mock);
    $parser->parse();

    // Test pseudclass with pseudoclass:
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('pseudoClass'));
    $mock->expects($this->once())
      ->method('pseudoClass')
      ->with($this->equalTo('mypclass'), $this->equalTo(':anotherPseudo'));

    $parser = new Parser('myele:mypclass(:anotherPseudo)', $mock);
    $parser->parse();

  }

  public function testPseudoElement() {
    // Test pseudo-element
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('pseudoElement'));
    $mock->expects($this->once())
      ->method('pseudoElement')
      ->with($this->equalTo('mypele'));

    $parser = new Parser('myele::mypele', $mock);
    $parser->parse();
  }

  public function testDirectDescendant() {
    // Test direct Descendant
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('directDescendant'));
    $mock->expects($this->once())
      ->method('directDescendant');

    $parser = new Parser('ele1 > ele2', $mock);
    $parser->parse();

  }

  public function testAnyDescendant() {
    // Test direct Descendant
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('anyDescendant'));
    $mock->expects($this->once())
      ->method('anyDescendant');

    $parser = new Parser('ele1  .class', $mock);
    $parser->parse();

  }

  public function testAdjacent() {
    // Test sibling
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('adjacent'));
    $mock->expects($this->once())
      ->method('adjacent');

    $parser = new Parser('ele1 + ele2', $mock);
    $parser->parse();
  }

  public function testSibling() {
    // Test adjacent
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('sibling'));
    $mock->expects($this->once())
      ->method('sibling');

    $parser = new Parser('ele1 ~ ele2', $mock);
    $parser->parse();
  }

  public function testAnotherSelector() {
    // Test adjacent
    $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('anotherSelector'));
    $mock->expects($this->once())
      ->method('anotherSelector');

    $parser = new Parser('ele1 , ele2', $mock);
    $parser->parse();
  }

  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testIllegalAttribute() {

    // Note that this is designed to test throwError() as well as
    // bad selector handling.

    $parser = new Parser('[test=~far]', new TestEventHandler());
    try {
      $parser->parse();
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  public function testAttribute() {
    $selectors = array(
      'element[attr]' => 'attr',
      '*[attr]' => 'attr',
      'element[attr]:class' => 'attr',
      'element[attr2]' => 'attr2', // Issue #
    );
    foreach ($selectors as $filter => $expected) {
      $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('attribute'));
      $mock->expects($this->once())
        ->method('attribute')
        ->with($this->equalTo($expected));

      $parser = new Parser($filter, $mock);
      $parser->parse();
    }

    $selectors = array(
      '*[attr="value"]' => array('attr','value',EventHandler::isExactly),
      '*[attr^="value"]' => array('attr','value',EventHandler::beginsWith),
      '*[attr$="value"]' => array('attr','value',EventHandler::endsWith),
      '*[attr*="value"]' => array('attr','value',EventHandler::containsInString),
      '*[attr~="value"]' => array('attr','value',EventHandler::containsWithSpace),
      '*[attr|="value"]' => array('attr','value',EventHandler::containsWithHyphen),

      // This should act like [attr="value"]
      '*[|attr="value"]' => array('attr', 'value', EventHandler::isExactly),

      // This behavior is displayed in the spec, but not accounted for in the
      // grammar:
      '*[attr=value]' => array('attr','value',EventHandler::isExactly),

      // Should be able to escape chars using backslash.
      '*[attr="\.value"]' => array('attr','.value',EventHandler::isExactly),
      '*[attr="\.\]\]\]"]' => array('attr','.]]]',EventHandler::isExactly),

      // Backslash-backslash should resolve to single backslash.
      '*[attr="\\\c"]' => array('attr','\\c',EventHandler::isExactly),

      // Should return an empty value. It seems, though, that a value should be
      // passed here.
      '*[attr=""]' => array('attr','',EventHandler::isExactly),
    );
    foreach ($selectors as $filter => $expected) {
      $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('attribute'));
      $mock->expects($this->once())
        ->method('attribute')
        ->with($this->equalTo($expected[0]), $this->equalTo($expected[1]), $this->equalTo($expected[2]));

      $parser = new Parser($filter, $mock);
      $parser->parse();
    }
  }

  public function testAttributeNS() {
    $selectors = array(
      '*[ns|attr="value"]' => array('attr', 'ns', 'value',EventHandler::isExactly),
      '*[*|attr^="value"]' => array('attr', '*', 'value',EventHandler::beginsWith),
      '*[*|attr|="value"]' => array('attr', '*', 'value',EventHandler::containsWithHyphen),
    );

    foreach ($selectors as $filter => $expected) {
      $mock = $this->getMock('\QueryPath\Tests\TestEventHandler', array('attributeNS'));
      $mock->expects($this->once())
        ->method('attributeNS')
        ->with($this->equalTo($expected[0]), $this->equalTo($expected[1]), $this->equalTo($expected[2]), $this->equalTo($expected[3]));

      $parser = new Parser($filter, $mock);
      $parser->parse();
    }
  }

  // Test things that should break...

  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testIllegalCombinators1() {
    $handler = new TestEventHandler();
    $parser = new Parser('ele1 > > ele2', $handler);
    $parser->parse();
  }

  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testIllegalCombinators2() {
    $handler = new TestEventHandler();
    $parser = new Parser('ele1+ ,ele2', $handler);
    $parser->parse();
  }

  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testIllegalID() {
    $handler = new TestEventHandler();
    $parser = new Parser('##ID', $handler);
    $parser->parse();
  }

  // Test combinations

  public function testElementNSClassAndAttribute() {

    $expect = array(
      new TestEvent(TestEvent::elementNS, 'element', 'ns'),
      new TestEvent(TestEvent::elementClass, 'class'),
      new TestEvent(TestEvent::attribute, 'name', 'value', EventHandler::isExactly),
    );
    $selector = 'ns|element.class[name="value"]';

    $handler = new TestEventHandler();
    $handler->expects($expect);
    $parser = new Parser($selector, $handler);
    $parser->parse();
    $this->assertTrue($handler->success());

    // Again, with spaces this time:
    $selector = ' ns|element. class[  name = "value" ]';

    $handler = new TestEventHandler();
    $handler->expects($expect);
    $parser = new Parser($selector, $handler);
    $parser->parse();

    //$handler->dumpStack();
    $this->assertTrue($handler->success());
  }

  public function testAllCombo() {

    $selector = '*|ele1 > ele2.class1 + ns1|ele3.class2[attr=simple] ~
     .class2[attr2~="longer string of text."]:pseudoClass(value)
     .class3::pseudoElement';
    $expect = array(
      new TestEvent(TestEvent::elementNS, 'ele1', '*'),
      new TestEvent(TestEvent::directDescendant),
      new TestEvent(TestEvent::element, 'ele2'),
      new TestEvent(TestEvent::elementClass, 'class1'),
      new TestEvent(TestEvent::adjacent),
      new TestEvent(TestEvent::elementNS, 'ele3', 'ns1'),
      new TestEvent(TestEvent::elementClass, 'class2'),
      new TestEvent(TestEvent::attribute, 'attr', 'simple', EventHandler::isExactly),
      new TestEvent(TestEvent::sibling),
      new TestEvent(TestEvent::elementClass, 'class2'),
      new TestEvent(TestEvent::attribute, 'attr2', 'longer string of text.', EventHandler::containsWithSpace),
      new TestEvent(TestEvent::pseudoClass, 'pseudoClass', 'value'),
      new TestEvent(TestEvent::anyDescendant),
      new TestEvent(TestEvent::elementClass, 'class3'),
      new TestEvent(TestEvent::pseudoElement, 'pseudoElement'),
    );


    $handler = new TestEventHandler();
    $handler->expects($expect);
    $parser = new Parser($selector, $handler);
    $parser->parse();

    //$handler->dumpStack();

    $this->assertTrue($handler->success());

    /*
    // Again, with spaces this time:
    $selector = ' *|ele1 > ele2. class1 + ns1|ele3. class2[ attr=simple] ~ .class2[attr2 ~= "longer string of text."]:pseudoClass(value) .class3::pseudoElement';

    $handler = new TestEventHandler();
    $handler->expects($expect);
    $parser = new Parser($selector, $handler);
    $parser->parse();

    $handler->dumpStack();
    $this->assertTrue($handler->success());
    */
  }
}

/**
 * Testing harness for the EventHandler.
 *
 * @ingroup querypath_tests
 * @group CSS
 */
class TestEventHandler implements EventHandler {
  var $stack = NULL;
  var $expect = array();

  public function __construct() {
    $this->stack = array();
  }

  public function getStack() {
    return $this->stack;
  }

  public function dumpStack() {
    print "\nExpected:\n";
    $format = "Element %d: %s\n";
    foreach ($this->expect as $item) {
      printf($format, $item->eventType(), implode(',', $item->params()));
    }

    print "Got:\n";
    foreach($this->stack as $item){
      printf($format, $item->eventType(), implode(',', $item->params()));
    }
  }

  public function expects($stack) {
    $this->expect = $stack;
  }

  public function success() {
    return ($this->expect == $this->stack);
  }

  public function elementID($id) {
    $this->stack[] = new TestEvent(TestEvent::elementID, $id);
  }
  public function element($name) {
    $this->stack[] = new TestEvent(TestEvent::element, $name);
  }
  public function elementNS($name, $namespace = NULL){
    $this->stack[] = new TestEvent(TestEvent::elementNS, $name, $namespace);
  }
  public function anyElement(){
    $this->stack[] = new TestEvent(TestEvent::anyElement);
  }
  public function anyElementInNS($ns){
    $this->stack[] = new TestEvent(TestEvent::anyElementInNS, $ns);
  }
  public function elementClass($name){
    $this->stack[] = new TestEvent(TestEvent::elementClass, $name);
  }
  public function attribute($name, $value = NULL, $operation = EventHandler::isExactly){
    $this->stack[] = new TestEvent(TestEvent::attribute, $name, $value, $operation);
  }
  public function attributeNS($name, $ns, $value = NULL, $operation = EventHandler::isExactly){
    $this->stack[] = new TestEvent(TestEvent::attributeNS, $name, $ns, $value, $operation);
  }
  public function pseudoClass($name, $value = NULL){
    $this->stack[] = new TestEvent(TestEvent::pseudoClass, $name, $value);
  }
  public function pseudoElement($name){
    $this->stack[] = new TestEvent(TestEvent::pseudoElement, $name);
  }
  public function directDescendant(){
    $this->stack[] = new TestEvent(TestEvent::directDescendant);
  }
  public function anyDescendant() {
    $this->stack[] = new TestEvent(TestEvent::anyDescendant);
  }
  public function adjacent(){
    $this->stack[] = new TestEvent(TestEvent::adjacent);
  }
  public function anotherSelector(){
    $this->stack[] = new TestEvent(TestEvent::anotherSelector);
  }
  public function sibling(){
    $this->stack[] = new TestEvent(TestEvent::sibling);
  }
}

/**
 * Simple utility object for use with the TestEventHandler.
 *
 * @ingroup querypath_tests
 * @group CSS
 */
class TestEvent {
  const elementID = 0;
  const element = 1;
  const elementNS = 2;
  const anyElement = 3;
  const elementClass = 4;
  const attribute = 5;
  const attributeNS = 6;
  const pseudoClass = 7;
  const pseudoElement = 8;
  const directDescendant = 9;
  const adjacent = 10;
  const anotherSelector = 11;
  const sibling = 12;
  const anyElementInNS = 13;
  const anyDescendant = 14;

  var $type = NULL;
  var $params = NULL;

  public function __construct($event_type) {
    $this->type = $event_type;
    $args = func_get_args();
    array_shift($args);
    $this->params = $args;
  }

  public function eventType() {
    return $this->type;
  }

  public function params() {
    return $this->params;
  }
}

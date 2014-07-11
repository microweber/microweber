#!/usr/bin/env php
<?php
/**
 * Generic CLI parser tests.
 *
 * These are not unit tests. They are just plain parser tests.
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
require '../src/QueryPath/QueryPath.php';
//$str = 'abc > def.g |: hi(jk)[lmn]*op';
//$str = '&abc.def';

/**
 * Testing harness for the CssEventHandler.
 * @ingroup querypath_tests
 */
class SimpleTestCssEventHandler implements CssEventHandler {
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
  public function attribute($name, $value = NULL, $operation = CssEventHandler::isExactly){
    $this->stack[] = new TestEvent(TestEvent::attribute, $name, $value, $operation);
  }
  public function attributeNS($name, $ns, $value = NULL, $operation = CssEventHandler::isExactly){
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
 * Simple utility object for use with the TestCssEventHandler.
 * @ingroup querypath_tests
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
    
    print "Event " . $event_type;
    print_r($args);
  }
  
  public function eventType() {
    return $this->type;
  }
  
  public function params() {
    return $this->params;
  }
}

print ord('"');
#$str = 'tag.class #id :test (test) + anotherElement > yetAnother[test] more[test="ing"]';
$str = 'tag.class #id :test (test)';
print "Now testing: $str\n";

$c = new SimpleTestCssEventHandler();

$p = new CssParser($str, $c);
$p->parse();


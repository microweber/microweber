<?php
namespace QueryPath\Tests;
require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\Selector,
    \QueryPath\CSS\SimpleSelector,
    \QueryPath\CSS\EventHandler;

class SelectorTest extends TestCase {

  protected function parse($selector) {
    $handler = new \QueryPath\CSS\Selector();
    $parser = new \QueryPath\CSS\Parser($selector, $handler);
    $parser->parse();
    return $handler;
  }

  public function testElement() {
    $selector = $this->parse('test')->toArray();

    $this->assertEquals(1, count($selector));
    $this->assertEquals('test', $selector[0]['0']->element);
  }

  public function testElementNS() {
    $selector = $this->parse('foo|test')->toArray();

    $this->assertEquals(1, count($selector));
    $this->assertEquals('test', $selector[0]['0']->element);
    $this->assertEquals('foo', $selector[0]['0']->ns);
  }

  public function testId() {
    $selector = $this->parse('#test')->toArray();

    $this->assertEquals(1, count($selector));
    $this->assertEquals('test', $selector[0][0]->id);
  }

  public function testClasses() {
    $selector = $this->parse('.test')->toArray();

    $this->assertEquals(1, count($selector));
    $this->assertEquals('test', $selector[0][0]->classes[0]);

    $selector = $this->parse('.test.foo.bar')->toArray();
    $this->assertEquals('test', $selector[0][0]->classes[0]);
    $this->assertEquals('foo', $selector[0][0]->classes[1]);
    $this->assertEquals('bar', $selector[0][0]->classes[2]);

  }

  public function testAttributes() {
    $selector = $this->parse('foo[bar=baz]')->toArray();
    $this->assertEquals(1, count($selector));
    $attrs = $selector[0][0]->attributes;

    $this->assertEquals(1, count($attrs));

    $attr = $attrs[0];
    $this->assertEquals('bar', $attr['name']);
    $this->assertEquals(EventHandler::isExactly, $attr['op']);
    $this->assertEquals('baz', $attr['value']);

    $selector = $this->parse('foo[bar=baz][size=one]')->toArray();
    $attrs = $selector[0][0]->attributes;

    $this->assertEquals('one', $attrs[1]['value']);
  }

  public function testAttributesNS() {
    $selector = $this->parse('[myns|foo=bar]')->toArray();

    $attr = $selector[0][0]->attributes[0];

    $this->assertEquals('myns', $attr['ns']);
    $this->assertEquals('foo', $attr['name']);
  }

  public function testPseudoClasses() {
    $selector = $this->parse('foo:first')->toArray();
    $pseudo = $selector[0][0]->pseudoClasses;

    $this->assertEquals(1, count($pseudo));

    $this->assertEquals('first', $pseudo[0]['name']);
  }

  public function testPseudoElements() {
    $selector = $this->parse('foo::bar')->toArray();
    $pseudo = $selector[0][0]->pseudoElements;

    $this->assertEquals(1, count($pseudo));

    $this->assertEquals('bar', $pseudo[0]);
  }

  public function testCombinators() {
    // This implies *>foo
    $selector = $this->parse('>foo')->toArray();

    $this->assertEquals(SimpleSelector::directDescendant, $selector[0][1]->combinator);

    // This will be a selector with three simples:
    // 'bar'
    // 'foo '
    // '*>'
    $selector = $this->parse('>foo bar')->toArray();
    $this->assertNull($selector[0][0]->combinator);
    $this->assertEquals(SimpleSelector::anyDescendant, $selector[0][1]->combinator);
    $this->assertEquals(SimpleSelector::directDescendant, $selector[0][2]->combinator);
  }

  public function testIterator() {
    $selector = $this->parse('foo::bar');

    $iterator = $selector->getIterator();
    $this->assertInstanceOf('\Iterator', $iterator);
  }
}

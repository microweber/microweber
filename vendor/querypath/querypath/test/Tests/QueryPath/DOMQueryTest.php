<?php
/** @file
 * Tests for the DOMQuery class.
 *
 *
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

namespace QueryPath\Tests;

/** @addtogroup querypath_tests Tests
 * Unit tests and regression tests for DOMQuery.
 */

use QueryPath\DOMQuery;

/** */
require_once 'PHPUnit/Autoload.php';
require_once __DIR__ . '/TestCase.php';

define('DATA_FILE', __DIR__ . '/../../data.xml');
define('DATA_HTML_FILE', __DIR__ . '/../../data.html');
define('NO_WRITE_FILE', __DIR__ . '/../../no-write.xml');
define('MEDIUM_FILE', __DIR__ . '/../../amplify.xml');
define('HTML_IN_XML_FILE', __DIR__ . '/../../html.xml');

/**
 * Tests for DOM Query. Primarily, this is focused on the DomQueryImpl
 * class which is exposed through the DomQuery interface and the dq()
 * factory function.
 * @ingroup querypath_tests
 */
class DOMQueryTest extends TestCase {

  /**
   * @group basic
   */
  public function testDOMQueryConstructors() {

    // From XML file
    $file = DATA_FILE;
    $qp = qp($file);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // From XML file with context
    $cxt = stream_context_create();
    $qp = qp($file, NULL, array('context' => $cxt));
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // From XML string
    $str = '<?xml version="1.0" ?><root><inner/></root>';
    $qp = qp($str);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // From SimpleXML
    $str = '<?xml version="1.0" ?><root><inner/></root>';
    $qp = qp(simplexml_load_string($str));
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Test from DOMDocument
    $qp = qp(\DOMDocument::loadXML($str));
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Now with a selector:
    $qp = qp($file, '#head');
    $this->assertEquals(1, count($qp->get()));
    $this->assertEquals($qp->get(0)->tagName, 'head');

    // Test HTML:
    $htmlFile = DATA_HTML_FILE;
    $qp = qp($htmlFile);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Test with another DOMQuery.
    $qp = qp($qp);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Test from array of DOMNodes
    $array = $qp->get();
    $qp = qp($array);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

  }
  /**
   * Test alternate constructors.
   * @group basic
   */
  public function testDOMQueryHtmlConstructors() {
    $qp = htmlqp(\QueryPath::HTML_STUB);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Bad BR tag.
    $borken = '<html><head></head><body><br></body></html>';
    $qp = htmlqp($borken);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // XHTML Faker
    $borken = '<?xml version="1.0"?><html><head></head><body><br></body></html>';
    $qp = htmlqp($borken);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // HTML in a file that looks like XML.
    $qp = htmlqp(HTML_IN_XML_FILE);
    $this->assertEquals(1, count($qp->get()));
    $this->assertTrue($qp->get(0) instanceof \DOMNode);

    // Stripping #13 (CR) from HTML.
    $borken = '<html><head></head><body><p>' . chr(13) . '</p><div id="after"/></body></html>';
    $this->assertFalse(strpos(htmlqp($borken)->html(), '&#13;'), 'Test that CRs are not encoded.');

    // Regression for #58: Make sure we aren't getting &#10; encoded.
    $borken = '<html><head><style>
        .BlueText {
          color:red;
        }</style><body></body></html>';

    $this->assertFalse(strpos(htmlqp($borken)->html(), '&#10;'), 'Test that LF is not encoded.');

    // Low ASCII in a file
    $borken = '<html><head></head><body><p>' . chr(27) . '</p><div id="after"/></body></html>';
    $this->assertEquals(1, htmlqp($borken, '#after')->size());
  }

  public function testForTests() {
    $qp_methods = get_class_methods('\QueryPath\DOMQuery');
    $test_methods = get_class_methods('\QueryPath\Tests\DOMQueryTest');

    $ignore = array("__construct", "__call", "__clone", "get", "getOptions", "setMatches", "toArray", "getIterator");

    $test_methods = array_map('strtolower', $test_methods);

    foreach($qp_methods as $q) {
      if(in_array($q, $ignore)) continue;
      $this->assertTrue(in_array(strtolower("test".$q), $test_methods), $q . ' does not have a test method.');
    }
  }

  public function testOptionXMLEncoding() {
    $xml = qp(NULL, NULL, array('encoding' => 'iso-8859-1'))->append('<test/>')->xml();
    $iso_found = preg_match('/iso-8859-1/', $xml) == 1;

    $this->assertTrue($iso_found, 'Encoding should be iso-8859-1 in ' . $xml . 'Found ' . $iso_found);

    $iso_found = preg_match('/utf-8/', $xml) == 1;
    $this->assertFalse($iso_found, 'Encoding should not be utf-8 in ' . $xml);

    $xml = qp('<?xml version="1.0" encoding="utf-8"?><test/>', NULL, array('encoding' => 'iso-8859-1'))->xml();
    $iso_found = preg_match('/utf-8/', $xml) == 1;
    $this->assertTrue($iso_found, 'Encoding should be utf-8 in ' . $xml);

    $iso_found = preg_match('/iso-8859-1/', $xml) == 1;
    $this->assertFalse($iso_found, 'Encoding should not be utf-8 in ' . $xml);

  }

  public function testQPAbstractFactory() {
    $options = array('QueryPath_class' => '\QueryPath\Tests\QueryPathExtended');
    $qp = qp(NULL, NULL, $options);
    $this->assertTrue($qp instanceof QueryPathExtended, 'Is instance of extending class.');
    $this->assertTrue($qp->foonator(), 'Has special foonator() function.');
  }

  public function testQPAbstractFactoryIterating() {
    $xml = '<?xml version="1.0"?><l><i/><i/><i/><i/><i/></l>';
    $options = array('QueryPath_class' => '\QueryPath\Tests\QueryPathExtended');
    foreach(qp($xml, 'i', $options) as $item) {
      $this->assertTrue($item instanceof QueryPathExtended, 'Is instance of extending class.');
    }

  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testFailedCall() {
    // This should hit __call() and then fail.
    qp()->fooMethod();
  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testFailedObjectConstruction() {
    qp(new \stdClass());
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedHTTPLoad() {
    try {
      qp('http://localhost:8877/no_such_file.xml');
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedHTTPLoadWithContext() {
    try {
      qp('http://localhost:8877/no_such_file.xml', NULL, array('foo' => 'bar'));
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedParseHTMLElement() {
    try {
      qp('<foo>&foonator;</foo>', NULL);
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedParseXMLElement() {
    try {
      qp('<?xml version="1.0"?><foo><bar>foonator;</foo>', NULL);
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }
  public function testIgnoreParserWarnings() {
    $qp = @qp('<html><body><b><i>BAD!</b></i></body>', NULL, array('ignore_parser_warnings' => TRUE));
    $this->assertTrue(strpos($qp->html(), '<i>BAD!</i>') !== FALSE);

    \QueryPath\Options::merge(array('ignore_parser_warnings' => TRUE));
    $qp = @qp('<html><body><b><i>BAD!</b></i></body>');
    $this->assertTrue(strpos($qp->html(), '<i>BAD!</i>') !== FALSE);

    $qp = @qp('<html><body><blarg>BAD!</blarg></body>');
    $this->assertTrue(strpos($qp->html(), '<blarg>BAD!</blarg>') !== FALSE, $qp->html());
    \QueryPath\Options::set(array()); // Reset to empty options.
  }
  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedParseNonMarkup() {
    try {
      qp('<23dfadf', NULL);
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testFailedParseEntity() {
    try {
      qp('<?xml version="1.0"?><foo>&foonator;</foo>', NULL);
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  public function testReplaceEntitiesOption() {
    $path = '<?xml version="1.0"?><root/>';
    $xml = qp($path, NULL, array('replace_entities' => TRUE))->xml('<foo>&</foo>')->xml();
    $this->assertTrue(strpos($xml, '<foo>&amp;</foo>') !== FALSE);

    $xml = qp($path, NULL, array('replace_entities' => TRUE))->html('<foo>&</foo>')->xml();
    $this->assertTrue(strpos($xml, '<foo>&amp;</foo>') !== FALSE);

    $xml = qp($path, NULL, array('replace_entities' => TRUE))->xhtml('<foo>&</foo>')->xml();
    $this->assertTrue(strpos($xml, '<foo>&amp;</foo>') !== FALSE);

    \QueryPath\Options::set(array('replace_entities' => TRUE));
    $this->assertTrue(strpos($xml, '<foo>&amp;</foo>') !== FALSE);
    \QueryPath\Options::set(array());
  }

  /**
   * @group basic
   */
  public function testFind() {
    $file = DATA_FILE;
    $qp = qp($file)->find('#head');
    $this->assertEquals(1, count($qp->get()));
    $this->assertEquals($qp->get(0)->tagName, 'head');

    $this->assertEquals('inner', qp($file)->find('.innerClass')->tag());

    $string = '<?xml version="1.0"?><root><a/>Test</root>';
    $qp = qp($string)->find('root');
    $this->assertEquals(1, count($qp->get()), 'Check tag.');
    $this->assertEquals($qp->get(0)->tagName, 'root');

    $string = '<?xml version="1.0"?><root class="findme">Test</root>';
    $qp = qp($string)->find('.findme');
    $this->assertEquals(1, count($qp->get()), 'Check class.');
    $this->assertEquals($qp->get(0)->tagName, 'root');
  }
  public function testFindInPlace() {
    $file = DATA_FILE;
    $qp = qp($file)->find('#head');
    $this->assertEquals(1, count($qp->get()));
    $this->assertEquals($qp->get(0)->tagName, 'head');

    $this->assertEquals('inner', qp($file)->find('.innerClass')->tag());

    $string = '<?xml version="1.0"?><root><a/>Test</root>';
    $qp = qp($string)->find('root');
    $this->assertEquals(1, count($qp->get()), 'Check tag.');
    $this->assertEquals($qp->get(0)->tagName, 'root');

    $string = '<?xml version="1.0"?><root class="findme">Test</root>';
    $qp = qp($string)->find('.findme');
    $this->assertEquals(1, count($qp->get()), 'Check class.');
    $this->assertEquals($qp->get(0)->tagName, 'root');
  }

  /**
   * @group basic
   */
  public function testTop() {
    $file = DATA_FILE;
    $qp = qp($file)->find('li');
    $this->assertGreaterThan(2, $qp->size());
    $this->assertEquals(1, $qp->top()->size());

    // Added for QP 2.0
    $xml = '<?xml version="1.0"?><root><u><l/><l/><l/></u><u/></root>';
    $qp = qp($xml, 'l');
    $this->assertEquals(3, $qp->size());
    $this->assertEquals(2, $qp->top('u')->size());
  }

  /**
   * @group basic
   */
  public function testAttr() {
    $file = DATA_FILE;

    $qp = qp($file)->find('#head');
    $this->assertEquals(1, $qp->size());
    $this->assertEquals($qp->get(0)->getAttribute('id'), $qp->attr('id'));

    $qp->attr('foo', 'bar');
    $this->assertEquals('bar', $qp->attr('foo'));

    $qp->attr(array('foo2' => 'bar', 'foo3' => 'baz'));
    $this->assertEquals('baz', $qp->attr('foo3'));

    // Check magic nodeType attribute:
    $this->assertEquals(XML_ELEMENT_NODE, qp($file)->find('#head')->attr('nodeType'));

    // Since QP 2.1
    $xml = '<?xml version="1.0"?><root><one a1="1" a2="2" a3="3"/></root>';
    $qp = qp($xml, 'one');
    $attrs = $qp->attr();
    $this->assertEquals(3, count($attrs), 'Three attributes');
    $this->assertEquals('1', $attrs['a1'], 'Attribute a1 has value 1.');
  }

  /**
   * @group basic
   */
  public function testHasAttr() {
    $xml = '<?xml version="1.0"?><root><div foo="bar"/></root>';

    $this->assertFalse(qp($xml, 'root')->hasAttr('foo'));
    $this->assertTrue(qp($xml, 'div')->hasAttr('foo'));

    $xml = '<?xml version="1.0"?><root><div foo="bar"/><div foo="baz"></div></root>';
    $this->assertTrue(qp($xml, 'div')->hasAttr('foo'));

    $xml = '<?xml version="1.0"?><root><div bar="bar"/><div foo="baz"></div></root>';
    $this->assertFalse(qp($xml, 'div')->hasAttr('foo'));

    $xml = '<?xml version="1.0"?><root><div bar="bar"/><div bAZ="baz"></div></root>';
    $this->assertFalse(qp($xml, 'div')->hasAttr('foo'));
  }

  public function testVal() {
    $qp = qp('<?xml version="1.0"?><foo><bar value="test"/></foo>', 'bar');
    $this->assertEquals('test', $qp->val());

    $qp = qp('<?xml version="1.0"?><foo><bar/></foo>', 'bar')->val('test');
    $this->assertEquals('test', $qp->attr('value'));
  }

  public function testCss() {
    $file = DATA_FILE;
    $this->assertEquals('foo: bar;', qp($file, 'unary')->css('foo', 'bar')->attr('style'));
    $this->assertEquals('foo: bar;', qp($file, 'unary')->css('foo', 'bar')->css());
    $this->assertEquals('foo: bar;', qp($file, 'unary')->css(array('foo' =>'bar'))->css());

    // Issue #28: Setting styles in sequence should not result in the second
    // style overwriting the first style:
    $qp = qp($file, 'unary')->css('color', 'blue')->css('background-color', 'white');

    $expects = 'color: blue;background-color: white;';
    $actual = $qp->css();
    $this->assertEquals(bin2hex($expects), bin2hex($actual), 'Two css calls should result in two attrs.');

    // Make sure array merges work.
    $qp = qp($file, 'unary')->css('a','a')->css(array('b'=>'b', 'c'=>'c'));
    $this->assertEquals('a: a;b: b;c: c;', $qp->css());

    // Make sure that second assignment overrides first assignment.
    $qp = qp($file, 'unary')->css('a','a')->css(array('b'=>'b', 'a'=>'c'));
    $this->assertEquals('a: c;b: b;', $qp->css());
  }

  public function testRemoveAttr() {
    $file = DATA_FILE;

    $qp = qp($file, 'inner')->removeAttr('class');
    $this->assertEquals(2, $qp->size());
    $this->assertFalse($qp->get(0)->hasAttribute('class'));

  }

  public function testEq() {
    $file = DATA_FILE;
    $qp = qp($file)->find('li')->eq(0);
    $this->assertEquals(1, $qp->size());
    $this->assertEquals($qp->attr('id'), 'one');
    $this->assertEquals(1, qp($file, 'inner')->eq(0)->size());
    $this->assertEquals(1, qp($file, 'li')->eq(0)->size());
    $this->assertEquals("Hello", qp($file, 'li')->eq(0)->text());
    $this->assertEquals("Last", qp($file, 'li')->eq(3)->text());
  }

  public function testIs() {
    $file = DATA_FILE;
    $this->assertTrue(qp($file)->find('#one')->is('#one'));
    $this->assertTrue(qp($file)->find('li')->is('#one'));

    $qp = qp($file)->find('#one');
    $ele = $qp->get(0);
    $this->assertTrue($qp->top('#one')->is($ele));

    $qp = qp($file)->find('#one');
    $ele = $qp->get(0);
    $ele2 = $qp->top('#two')->get(0);

    $list = new \SplDoublyLinkedList();
    $list->push($ele);
    $list->push($ele2);
    $this->assertEquals(2, count($list));
    //$this->assertEquals(2, )
    $this->assertTrue($qp->top('#one,#two')->is($list));

  }

  public function testIndex() {
    $xml = '<?xml version="1.0"?><foo><bar id="one"/><baz id="two"/></foo>';
    $qp = qp($xml, 'bar');
    $e1 = $qp->get(0);
    $this->assertEquals(0, $qp->find('bar')->index($e1));
    $this->assertFalse($qp->top()->find('#two')->index($e1));
  }

  public function testFilter() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file)->filter('li')->size());
    $this->assertEquals(2, qp($file, 'inner')->filter('li')->size());
    $this->assertEquals('inner-two', qp($file, 'inner')->filter('li')->eq(1)->attr('id'));
  }

  public function testFilterPreg() {
    $xml = '<?xml version="1.0"?><root><div id="one">Foo</div><div>Moo</div></root>';
    $qp = qp($xml, 'div')->filterPreg('/Foo/');

    $this->assertEquals(1, $qp->Size());

    // Check to make sure textContent is collected correctly.
    $xml = '<?xml version="1.0"?><root><div>Hello <i>World</i></div></root>';
    $qp = qp($xml, 'div')->filterPreg('/Hello\sWorld/');

    $this->assertEquals(1, $qp->Size());
  }

  public function testFilterLambda() {
    $file = DATA_FILE;
    // Get all evens:
    $l = 'return (($index + 1) % 2 == 0);';
    $this->assertEquals(2, qp($file, 'li')->filterLambda($l)->size());
  }

  public function filterCallbackFunction($index, $item) {
    return (($index + 1) % 2 == 0);
  }


  public function testFilterCallback() {
    $file = DATA_FILE;
    $cb = array($this, 'filterCallbackFunction');
    $this->assertEquals(2, qp($file, 'li')->filterCallback($cb)->size());
  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testFailedFilterCallback() {
    $file = DATA_FILE;
    $cb = array($this, 'noSuchFunction');
    qp($file, 'li')->filterCallback($cb)->size();
  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testFailedMapCallback() {
    $file = DATA_FILE;
    $cb = array($this, 'noSuchFunction');
    qp($file, 'li')->map($cb)->size();
  }


  public function testNot() {
    $file = DATA_FILE;

    // Test with selector
    $qp = qp($file, 'li:odd')->not('#one');
    $this->assertEquals(2, $qp->size());

    // Test with DOM Element
    $qp = qp($file, 'li');
    $el = $qp->branch()->filter('#one')->get(0);
    $this->assertTrue($el instanceof \DOMElement, "Is DOM element.");
    $this->assertEquals(4, $qp->not($el)->size());

    // Test with array of DOM Elements
    $qp = qp($file, 'li');
    $arr = $qp->get();
    $this->assertEquals(count($arr), $qp->size());
    array_shift($arr);
    $this->assertEquals(1, $qp->not($arr)->size());
  }

  public function testSlice() {
    $file = DATA_FILE;
    // There are five <li> elements
    $qp = qp($file, 'li')->slice(1);
    $this->assertEquals(4, $qp->size());

    // The first item in the matches should be #two.
    $this->assertEquals('two', $qp->attr('id'));

    // THe last item should be #five
    $this->assertEquals('five', $qp->eq(3)->attr('id'));

    // This should not throw an error.
    $this->assertEquals(4, qp($file, 'li')->slice(1, 9)->size());

    $this->assertEquals(0, qp($file, 'li')->slice(9)->size());

    // The first item should be #two, the last #three
    $qp = qp($file, 'li')->slice(1, 2);
    $this->assertEquals(2, $qp->size());
    $this->assertEquals('two', $qp->attr('id'));
    $this->assertEquals('three', $qp->eq(1)->attr('id'));
  }

  public function mapCallbackFunction($index, $item) {
    if ($index == 1) {
      return FALSE;
    }
    if ($index == 2) {
      return array(1, 2, 3);
    }
    return $index;
  }

  public function testMap() {
    $file = DATA_FILE;
    $fn = 'mapCallbackFunction';
    $this->assertEquals(7, qp($file, 'li')->map(array($this, $fn))->size());
  }

  public function eachCallbackFunction($index, $item) {
    if ($index < 2) {
      qp($item)->attr('class', 'test');
    }
    else {
      return FALSE;
    }
  }

  public function testEach() {
    $file = DATA_FILE;
    $fn = 'eachCallbackFunction';
    $res = qp($file, 'li')->each(array($this, $fn));
    $this->assertEquals(5, $res->size());
    $this->assertFalse($res->get(4)->getAttribute('class') === NULL);
    $this->assertEquals('test', $res->eq(1)->attr('class'));

    // Test when each runs out of things to test before returning.
    $res = qp($file, '#one')->each(array($this, $fn));
    $this->assertEquals(1, $res->size());
  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testEachOnInvalidCallback() {
    $file = DATA_FILE;
    $fn = 'eachCallbackFunctionFake';
    $res = qp($file, 'li')->each(array($this, $fn));
  }

  public function testEachLambda() {
    $file = DATA_FILE;
    $fn = 'qp($item)->attr("class", "foo");';
    $res = qp($file, 'li')->eachLambda($fn);
    $this->assertEquals('foo', $res->eq(1)->attr('class'));
  }

  public function testDeepest() {
    $str = '<?xml version="1.0" ?>
    <root>
      <one/>
      <one><two/></one>
      <one><two><three/></two></one>
      <one><two><three><four/></three></two></one>
      <one/>
      <one><two><three><banana/></three></two></one>
    </root>';
    $deepest = qp($str)->deepest();
    $this->assertEquals(2, $deepest->size());
    $this->assertEquals('four', $deepest->get(0)->tagName);
    $this->assertEquals('banana', $deepest->get(1)->tagName);

    $deepest = qp($str, 'one')->deepest();
    $this->assertEquals(2, $deepest->size());
    $this->assertEquals('four', $deepest->get(0)->tagName);
    $this->assertEquals('banana', $deepest->get(1)->tagName);

    $str = '<?xml version="1.0" ?>
    <root>
      CDATA
    </root>';
    $this->assertEquals(1, qp($str)->deepest()->size());
  }

  public function testTag() {
    $file = DATA_FILE;
    $this->assertEquals('li', qp($file, 'li')->tag());
  }

  public function testAppend() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file,'unary')->append('<test/>')->find(':root > unary > test')->size());
    $qp = qp($file,'#inner-one')->append('<li id="appended"/>');

    $appended = $qp->find('#appended');
    $this->assertEquals(1, $appended->size());
    $this->assertNull($appended->get(0)->nextSibling);

    $this->assertEquals(2, qp($file, 'inner')->append('<test/>')->top()->find('test')->size());
    $this->assertEquals(2, qp($file, 'inner')->append(qp('<?xml version="1.0"?><test/>'))->top()->find('test')->size());

    // Issue #6: This seems to break on Debian Etch systems... no idea why.
    $this->assertEquals('test', qp()->append('<test/>')->top()->tag());

    // Issue #7: Failure issues warnings
    // This seems to be working as expected -- libxml emits
    // parse errors.
    //$this->assertEquals(NULL, qp()->append('<test'));

    // Test loading SimpleXML.
    $simp = simplexml_load_file($file);
    $qp = qp('<?xml version="1.0"?><foo/>')->append($simp);
    $this->assertEquals(1, $qp->find('root')->size());

    // Test with replace entities turned on:
    $qp = qp($file, 'root', array('replace_entities' => TRUE))->append('<p>&raquo;</p>');
    // Note that we are using a UTF-8 » character, not an ASCII 187. This seems to cause
    // problems on some Windows IDEs. So here we do it the ugly way.
    $utf8raquo = '<p>' . mb_convert_encoding(chr(187), 'utf-8', 'iso-8859-1') . '</p>';
    //$this->assertEquals('<p>»</p>', $qp->find('p')->html(), 'Entities are decoded to UTF-8 correctly.');
    $this->assertEquals($utf8raquo, $qp->find('p')->html(), 'Entities are decoded to UTF-8 correctly.');

    // Test with empty, mainly to make sure it doesn't explode.
    $this->assertTrue(qp($file)->append('') instanceof DOMQuery);
  }

  /**
   * @expectedException \QueryPath\ParseException
   */
  public function testAppendBadMarkup() {
    $file = DATA_FILE;
    try{
      qp($file, 'root')->append('<foo><bar></foo>');
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }
  }

  /**
    * @expectedException \QueryPath\Exception
    */
   public function testAppendBadObject() {
     $file = DATA_FILE;
     try{
       qp($file, 'root')->append(new \stdClass);
     }
     catch (Exception $e) {
       //print $e->getMessage();
       throw $e;
     }
   }

  public function testAppendTo() {
    $file = DATA_FILE;
    $dest = qp('<?xml version="1.0"?><root><dest/></root>', 'dest');
    $qp = qp($file,'li')->appendTo($dest);
    $this->assertEquals(5, $dest->find(':root li')->size());
  }

  public function testPrepend() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file,'unary')->prepend('<test/>')->find(':root > unary > test')->size());
    $qp = qp($file,'#inner-one')->prepend('<li id="appended"/>')->find('#appended');
    $this->assertEquals(1, $qp->size());
    $this->assertNull($qp->get(0)->previousSibling);

    // Test repeated insert
    $this->assertEquals(2, qp($file,'inner')->prepend('<test/>')->top()->find('test')->size());
  }

  public function testPrependTo() {
    $file = DATA_FILE;
    $dest = qp('<?xml version="1.0"?><root><dest/></root>', 'dest');
    $qp = qp($file,'li')->prependTo($dest);
    $this->assertEquals(5, $dest->find(':root li')->size());
  }

  public function testBefore() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file,'unary')->before('<test/>')->find(':root > test ~ unary')->size());
    $this->assertEquals(1, qp($file,'unary')->before('<test/>')->top('head ~ test')->size());
    $this->assertEquals('unary', qp($file,'unary')->before('<test/>')->top(':root > test')->get(0)->nextSibling->tagName);

    // Test repeated insert
    $this->assertEquals(2, qp($file,'inner')->before('<test/>')->top()->find('test')->size());
  }

  public function testAfter() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file,'unary')->after('<test/>')->top(':root > unary ~ test')->size());
    $this->assertEquals('unary', qp($file,'unary')->after('<test/>')->top(':root > test')->get(0)->previousSibling->tagName);

    $this->assertEquals(2, qp($file,'inner')->after('<test/>')->top()->find('test')->size());

  }

  public function testInsertBefore() {
    $file = DATA_FILE;
    $dest = qp('<?xml version="1.0"?><root><dest/></root>', 'dest');
    $qp = qp($file,'li')->insertBefore($dest);
    $this->assertEquals(5, $dest->top(':root > li')->size());
    $this->assertEquals('li', $dest->end()->find('dest')->get(0)->previousSibling->tagName);
  }
  public function testInsertAfter() {
    $file = DATA_FILE;
    $dest = qp('<?xml version="1.0"?><root><dest/></root>', 'dest');
    $qp = qp($file,'li')->insertAfter($dest);
    //print $dest->get(0)->ownerDocument->saveXML();
    $this->assertEquals(5, $dest->top(':root > li')->size());
  }
  public function testReplaceWith() {
    $file = DATA_FILE;
    $qp = qp($file,'unary')->replaceWith('<test><foo/></test>')->top('test');
    //print $qp->get(0)->ownerDocument->saveXML();
    $this->assertEquals(1, $qp->size());
  }

  public function testReplaceAll() {
    $qp1 = qp('<?xml version="1.0"?><root><l/><l/></root>');
    $doc = qp('<?xml version="1.0"?><bob><m/><m/></bob>')->get(0)->ownerDocument;

    $qp2 = $qp1->find('l')->replaceAll('m', $doc);

    $this->assertEquals(2, $qp2->top()->find('l')->size());
  }

  public function testUnwrap() {

    // Unwrap center, and make sure junk goes away.
    $xml = '<?xml version="1.0"?><root><wrapper><center/><junk/></wrapper></root>';
    $qp = qp($xml, 'center')->unwrap();
    $this->assertEquals('root', $qp->top('center')->parent()->tag());
    $this->assertEquals(0, $qp->top('junk')->size());

    // Make sure it works on two nodes in the same parent.
    $xml = '<?xml version="1.0"?><root><wrapper><center id="1"/><center id="2"/></wrapper></root>';
    $qp = qp($xml, 'center')->unwrap();

    // Make sure they were unwrapped
    $this->assertEquals('root', $qp->top('center')->parent()->tag());

    // Make sure both get copied.
    $this->assertEquals(2, $qp->top('center')->size());

    // Make sure they are in order.
    $this->assertEquals('2', $qp->top('center:last')->attr('id'));

    // Test on root element.
    $xml = '<?xml version="1.0"?><root><center/></root>';
    $qp = qp($xml, 'center')->unwrap();
    $this->assertEquals('center', $qp->top()->tag());

  }

  /**
   * @expectedException \QueryPath\Exception
   */
  public function testFailedUnwrap() {
    // Cannot unwrap the root element.
    $xml = '<?xml version="1.0"?><root></root>';
    $qp = qp($xml, 'root')->unwrap();
    $this->assertEquals('center', $qp->top()->tag());
  }

  public function testWrap() {
    $file = DATA_FILE;
    $xml = qp($file,'unary')->wrap('');
    $this->assertTrue($xml instanceof DOMQuery);

    $xml = qp($file,'unary')->wrap('<test id="testWrap"></test>')->get(0)->ownerDocument->saveXML();
    $this->assertEquals(1, qp($xml, '#testWrap')->get(0)->childNodes->length);

    $xml = qp($file,'li')->wrap('<test class="testWrap"></test>')->get(0)->ownerDocument->saveXML();
    $this->assertEquals(5, qp($xml, '.testWrap')->size());

    $xml = qp($file,'li')->wrap('<test class="testWrap"><inside><center/></inside></test>')->get(0)->ownerDocument->saveXML();
    $this->assertEquals(5, qp($xml, '.testWrap > inside > center > li')->size());
  }

  public function testWrapAll() {
    $file = DATA_FILE;

    $xml = qp($file,'unary')->wrapAll('');
    $this->assertTrue($xml instanceof DOMQuery);

    $xml = qp($file,'unary')->wrapAll('<test id="testWrap"></test>')->get(0)->ownerDocument->saveXML();
    $this->assertEquals(1, qp($xml, '#testWrap')->get(0)->childNodes->length);

    $xml = qp($file,'li')->wrapAll('<test class="testWrap"><inside><center/></inside></test>')->get(0)->ownerDocument->saveXML();
    $this->assertEquals(5, qp($xml, '.testWrap > inside > center > li')->size());

  }

  public function testWrapInner() {
    $file = DATA_FILE;

    $this->assertTrue(qp($file,'#inner-one')->wrapInner('') instanceof DOMQuery);

    $xml = qp($file,'#inner-one')->wrapInner('<test class="testWrap"></test>')->get(0)->ownerDocument->saveXML();
    // FIXME: 9 includes text nodes. Should fix this.
    $this->assertEquals(9, qp($xml, '.testWrap')->get(0)->childNodes->length);
  }

  public function testRemove() {
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $start = $qp->size();
    $finish = $qp->remove()->size();
    $this->assertEquals($start, $finish);
    $this->assertEquals(0, $qp->find(':root li')->size());

    // Test for Issue #55
    $data = '<?xml version="1.0"?><root><a>test</a><b> FAIL</b></root>';
    $qp = qp($data);
    $rem = $qp->remove('b');


    $this->assertEquals(' FAIL', $rem->text());
    $this->assertEquals('test', $qp->text());

    // Test for Issue #63
    $qp = qp($data);
    $rem = $qp->remove('noSuchElement');
    $this->assertEquals(0, count($rem));
  }

  public function testHasClass() {
    $file = DATA_FILE;
    $this->assertTrue(qp($file, '#inner-one')->hasClass('innerClass'));

    $file = DATA_FILE;
    $this->assertFalse(qp($file, '#inner-one')->hasClass('noSuchClass'));
  }

  public function testAddClass() {
    $file = DATA_FILE;
    $this->assertTrue(qp($file, '#inner-one')->addClass('testClass')->hasClass('testClass'));
  }
  public function testRemoveClass() {
    $file = DATA_FILE;
    // The add class tests to make sure that this works with multiple values.
    $this->assertFalse(qp($file, '#inner-one')->removeClass('innerClass')->hasClass('innerClass'));
    $this->assertTrue(qp($file, '#inner-one')->addClass('testClass')->removeClass('innerClass')->hasClass('testClass'));
  }

  public function testAdd() {
    $file = DATA_FILE;
    $this->assertEquals(7, qp($file, 'li')->add('inner')->size());
  }

  public function testEnd() {
    $file = DATA_FILE;
    $this->assertEquals(2, qp($file, 'inner')->find('li')->end()->size());
  }

  public function testAndSelf() {
    $file = DATA_FILE;
    $this->assertEquals(7, qp($file, 'inner')->find('li')->andSelf()->size());
  }

  public function testChildren() {
    $file = DATA_FILE;
    $this->assertEquals(5, qp($file, 'inner')->children()->size());
    foreach (qp($file, 'inner')->children('li') as $kid) {
      $this->assertEquals('li', $kid->tag());
    }
    $this->assertEquals(5, qp($file, 'inner')->children('li')->size());
    $this->assertEquals(1, qp($file, ':root')->children('unary')->size());

    // For #112: testing children() to match jQuery behavior.
    $html = '<?xml version="1.0"?><html><body><header><section>test</section></header></body></html>';
    $qp = qp($html, 'body');
    $this->assertEquals(1, $qp->children('header')->size());
    $this->assertEquals(0, $qp->children('header>section')->size());
    $this->assertEquals(1, $qp->children('html>body>header')->size());

    $xml = '<?xml version="1.0"?><test><p>a</p><p>b</p><b><p>c</p></b></test>';
    $qp = qp($xml, 'test');
    $this->assertEquals(3, $qp->children()->size());
    $this->assertEquals(2, $qp->children('p')->size());
    $this->assertEquals(2, $qp->children('>p')->size());
    $this->assertEquals(0, $qp->children('b>p')->size());
    $this->assertEquals(2, $qp->children('test>p')->size());

    $xml = '<?xml version="1.0"?><test><d id="first"><d><d><a>Hi</a></d></d></d></test>';
    $qp = qp($xml, 'test');
    $this->assertEquals(0, $qp->children('a')->size());
    $this->assertEquals(0, $qp->children('d')->children('a')->size());
  }
  public function testRemoveChildren() {
    $file = DATA_FILE;
    $this->assertEquals(0, qp($file, '#inner-one')->removeChildren()->find('li')->size());
  }

  public function testContents() {
    $file = DATA_FILE;
    $this->assertGreaterThan(5, qp($file, 'inner')->contents()->size());
    // Two cdata nodes and one element node.
    $this->assertEquals(3, qp($file, '#inner-two')->contents()->size());

    // Issue #51: Under certain recursive conditions, this returns error.
    // Warning: Whitespace is important in the markup beneath.
    $xml = '<html><body><div>Hello
        <div>how are you
          <div>fine thank you
            <div>and you ?</div>
          </div>
        </div>
      </div>
    </body></html>';
    $cr = $this->contentsRecurse(qp($xml));
    $this->assertEquals(14, count($cr), implode("\n", $cr));
  }

  /**
   * Helper function for testContents().
   * Based on problem reported in issue 51.
   */
  private function contentsRecurse($source, &$pack = array()) {
    //static $i = 0;
    //static $filter = "%d. Node type: %d, Content: '%s'\n";
    $children = $source->contents();
    //$node = $source->get(0);
    $pack[] = 1; //sprintf($filter, ++$i, $node->nodeType, $source->html());

    foreach ($children as $child) {
      $pack += $this->contentsRecurse($child, $pack);
    }

    return $pack;
  }

  public function testSiblings() {
    $file = DATA_FILE;
    $this->assertEquals(3, qp($file, '#one')->siblings()->size());
    $this->assertEquals(2, qp($file, 'unary')->siblings('inner')->size());
  }

  public function testXinclude() {

  }

  public function testHTML() {
    $file = DATA_FILE;
    $qp = qp($file, 'unary');
    $html = '<b>test</b>';
    $this->assertEquals($html, $qp->html($html)->find('b')->html());

    $html = '<html><head><title>foo</title></head><body>bar</body></html>';
    // We expect a DocType to be prepended:
    $this->assertEquals('<!DOCTYPE', substr(qp($html)->html(), 0, 9));

    // Check that HTML is not added to empty finds. Note the # is for a special
    // case.
    $this->assertEquals('', qp($html, '#nonexistant')->html('<p>Hello</p>')->html());
    $this->assertEquals('', qp($html, 'nonexistant')->html('<p>Hello</p>')->html());

    // We expect NULL if the document is empty.
    $this->assertNull(qp()->html());

    // Non-DOMNodes should not be rendered:
    $fn = 'mapCallbackFunction';
    $this->assertNull(qp($file, 'li')->map(array($this, $fn))->html());
  }

  public function testInnerHTML() {
    $html = '<html><head></head><body><div id="me">Test<p>Again</p></div></body></html>';

    $this->assertEquals('Test<p>Again</p>', qp($html,'#me')->innerHTML());
  }

  public function testInnerXML() {
    $html = '<?xml version="1.0"?><root><div id="me">Test<p>Again1</p></div></root>';
    $test = 'Test<p>Again1</p>';

    $this->assertEquals($test, qp($html,'#me')->innerXML());

    $html = '<?xml version="1.0"?><root><div id="me">Test<p>Again2<br/></p><![CDATA[Hello]]><?pi foo ?></div></root>';
    $test = 'Test<p>Again2<br/></p><![CDATA[Hello]]><?pi foo ?>';

    $this->assertEquals($test, qp($html,'#me')->innerXML());

    $html = '<?xml version="1.0"?><root><div id="me"/></root>';
    $test = '';
    $this->assertEquals($test, qp($html,'#me')->innerXML());

    $html = '<?xml version="1.0"?><root id="me">test</root>';
    $test = 'test';
    $this->assertEquals($test, qp($html,'#me')->innerXML());
  }

  public function testInnerXHTML() {
    $html = '<?xml version="1.0"?><html><head></head><body><div id="me">Test<p>Again</p></div></body></html>';

    $this->assertEquals('Test<p>Again</p>', qp($html,'#me')->innerXHTML());

    // Regression for issue #10: Tags should not be unary (e.g. we want <script></script>, not <script/>)
    $xml = '<html><head><title>foo</title></head><body><div id="me">Test<p>Again<br/></p></div></body></html>';
    // Look for a closing </br> tag
    $regex = '/<\/br>/';
    $this->assertRegExp($regex, qp($xml, '#me')->innerXHTML(), 'BR should have a closing tag.');
  }

  public function testXML() {
    $file = DATA_FILE;
    $qp = qp($file, 'unary');
    $xml = '<b>test</b>';
    $this->assertEquals($xml, $qp->xml($xml)->find('b')->xml());

    $xml = '<html><head><title>foo</title></head><body>bar</body></html>';
    // We expect an XML declaration to be prepended:
    $this->assertEquals('<?xml', substr(qp($xml, 'html')->xml(), 0, 5));

    // We don't want an XM/L declaration if xml(TRUE).
    $xml = '<?xml version="1.0"?><foo/>';
    $this->assertFalse(strpos(qp($xml)->xml(TRUE), '<?xml'));

    // We expect NULL if the document is empty.
    $this->assertNull(qp()->xml());

    // Non-DOMNodes should not be rendered:
    $fn = 'mapCallbackFunction';
    $this->assertNull(qp($file, 'li')->map(array($this, $fn))->xml());
  }

  public function testXHTML() {
    // throw new Exception();

    $file = DATA_FILE;
    $qp = qp($file, 'unary');
    $xml = '<b>test</b>';
    $this->assertEquals($xml, $qp->xml($xml)->find('b')->xhtml());

    $xml = '<html><head><title>foo</title></head><body>bar</body></html>';
    // We expect an XML declaration to be prepended:
    $this->assertEquals('<?xml', substr(qp($xml, 'html')->xhtml(), 0, 5));

    // We don't want an XM/L declaration if xml(TRUE).
    $xml = '<?xml version="1.0"?><foo/>';
    $this->assertFalse(strpos(qp($xml)->xhtml(TRUE), '<?xml'));

    // We expect NULL if the document is empty.
    $this->assertNull(qp()->xhtml());

    // Non-DOMNodes should not be rendered:
    $fn = 'mapCallbackFunction';
    $this->assertNull(qp($file, 'li')->map(array($this, $fn))->xhtml());

    // Regression for issue #10: Tags should not be unary (e.g. we want <script></script>, not <script/>)
    $xml = '<html><head><title>foo</title></head>
      <body>
      bar<br/><hr width="100">
      <script></script>
      <script>
      alert("Foo");
      </script>
      <frameset id="fooframeset"></frameset>
      </body></html>';

    $xhtml = qp($xml)->xhtml();

    //throw new Exception($xhtml);

    // Look for a properly formatted BR unary tag:
    $regex = '/<br \/>/';
    $this->assertRegExp($regex, $xhtml, 'BR should have a closing tag.');

    // Look for a properly formatted HR tag:
    $regex = '/<hr width="100" \/>/';
    $this->assertRegExp($regex, $xhtml, 'BR should have a closing tag.');

    // Ensure that script tag is not collapsed:
    $regex = '/<script><\/script>/';
    $this->assertRegExp($regex, $xhtml, 'BR should have a closing tag.');

    // Ensure that frameset tag is not collapsed (it looks like <frame>):
    $regex = '/<frameset id="fooframeset"><\/frameset>/';
    $this->assertRegExp($regex, $xhtml, 'BR should have a closing tag.');

    // Ensure that script gets wrapped in CDATA:
    $find = '/* <![CDATA[ ';
    $this->assertTrue(strpos($xhtml, $find) > 0, 'CDATA section should be escaped.');

    // Regression: Make sure it parses.
    $xhtml = qp('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head></head><body><br /></body></html>')->xhtml();

    qp($xhtml);

  }

  public function testWriteXML() {
    $xml = '<?xml version="1.0"?><html><head><title>foo</title></head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeXML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect an XML declaration at the top.
    $this->assertEquals('<?xml', substr($out, 0, 5));

    $xml = '<?xml version="1.0"?><html><head><script>
    <!--
    1 < 2;
    -->
    </script>
    <![CDATA[This is CDATA]]>
    <title>foo</title></head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeXML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect an XML declaration at the top.
    $this->assertEquals('<?xml', substr($out, 0, 5));

    // Test writing to a file:
    $name = './' . __FUNCTION__ . '.xml';
    qp($xml)->writeXML($name);
    $this->assertTrue(file_exists($name));
    $this->assertTrue(qp($name) instanceof DOMQuery);
    unlink($name);
  }

  public function testWriteXHTML() {
    $xml = '<?xml version="1.0"?><html><head><title>foo</title></head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeXHTML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect an XML declaration at the top.
    $this->assertEquals('<?xml', substr($out, 0, 5));

    $xml = '<?xml version="1.0"?><html><head><script>
    <!--
    1 < 2;
    -->
    </script>
    <![CDATA[This is CDATA]]>
    <title>foo</title></head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'html')->writeXHTML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect an XML declaration at the top.
    $this->assertEquals('<?xml', substr($out, 0, 5));

    // Test writing to a file:
    $name = './' . __FUNCTION__ . '.xml';
    qp($xml)->writeXHTML($name);
    $this->assertTrue(file_exists($name));
    $this->assertTrue(qp($name) instanceof DOMQuery);
    unlink($name);

    // Regression for issue #10 (keep closing tags in XHTML)
    $xhtml = '<?xml version="1.0"?><html><head><title>foo</title><script></script><br/></head><body>bar</body></html>';
    if (!ob_start()) die ("Could not start OB.");
    qp($xhtml, 'html')->writeXHTML();
    $out = ob_get_contents();
    ob_end_clean();

    $pattern = '/<\/script>/';
    $this->assertRegExp($pattern, $out, 'Should be closing script tag.');

    $pattern = '/<\/br>/';
    $this->assertRegExp($pattern, $out, 'Should be closing br tag.');
  }

  /**
   * @expectedException \QueryPath\IOException
   */
  public function testFailWriteXML() {
    try {
      qp()->writeXML('./test/no-writing.xml');
    }
    catch (Exception $e) {
      //print $e->getMessage();
      throw $e;
    }

  }

  /**
   * @expectedException \QueryPath\IOException
   */
  public function testFailWriteXHTML() {
    try {
      qp()->writeXHTML('./test/no-writing.xml');
    }
    catch (\QueryPath\IOException $e) {
      //print $e->getMessage();
      throw $e;
    }

  }

  /**
   * @expectedException \QueryPath\IOException
   */
  public function testFailWriteHTML() {
    try {
      qp('<?xml version="1.0"?><foo/>')->writeXML('./test/no-writing.xml');
    }
    catch (\QueryPath\IOException $e) {
      // print $e->getMessage();
      throw $e;
    }

  }

  public function testWriteHTML() {
    $xml = '<html><head><title>foo</title></head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeHTML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect a doctype declaration at the top.
    $this->assertEquals('<!DOC', substr($out, 0, 5));

    $xml = '<html><head><title>foo</title>
    <script><!--
    var foo = 1 < 5;
    --></script>
    </head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeHTML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect a doctype declaration at the top.
    $this->assertEquals('<!DOC', substr($out, 0, 5));

    $xml = '<html><head><title>foo</title>
    <script><![CDATA[
    var foo = 1 < 5;
    ]]></script>
    </head><body>bar</body></html>';

    if (!ob_start()) die ("Could not start OB.");
    qp($xml, 'tml')->writeHTML();
    $out = ob_get_contents();
    ob_end_clean();

    // We expect a doctype declaration at the top.
    $this->assertEquals('<!DOC', substr($out, 0, 5));

    // Test writing to a file:
    $name = './' . __FUNCTION__ . '.html';
    qp($xml)->writeXML($name);
    $this->assertTrue(file_exists($name));
    $this->assertTrue(qp($name) instanceof DOMQuery);
    unlink($name);
  }

  public function testText() {
    $xml = '<?xml version="1.0"?><root><div>Text A</div><div>Text B</div></root>';
    $this->assertEquals('Text AText B', qp($xml)->text());
    $this->assertEquals('Foo', qp($xml, 'div')->eq(0)->text('Foo')->text());
  }

  public function testTextAfter() {
    $xml = '<?xml version="1.0"?><root><br/>After<foo/><br/>After2<div/>After3</root>';
    $this->assertEquals('AfterAfter2', qp($xml, 'br')->textAfter());
    $this->assertEquals('Blarg', qp($xml, 'foo')->textAfter('Blarg')->top('foo')->textAfter());
  }

  public function testTextBefore() {
    $xml = '<?xml version="1.0"?><root>Before<br/><foo/>Before2<br/>Before3<div/></root>';
    $this->assertEquals('BeforeBefore2', qp($xml, 'br')->textBefore());
    $this->assertEquals('Blarg', qp($xml, 'foo')->textBefore('Blarg')->top('foo')->textBefore());

  }

  public function testTextImplode() {
    $xml = '<?xml version="1.0"?><root><div>Text A</div><div>Text B</div></root>';
    $this->assertEquals('Text A, Text B', qp($xml, 'div')->textImplode());
    $this->assertEquals('Text A--Text B', qp($xml, 'div')->textImplode('--'));

    $xml = '<?xml version="1.0"?><root><div>Text A </div><div>Text B</div></root>';
    $this->assertEquals('Text A , Text B', qp($xml, 'div')->textImplode());

    $xml = '<?xml version="1.0"?><root><div>Text A </div>
    <div>
    </div><div>Text B</div></root>';
    $this->assertEquals('Text A , Text B', qp($xml, 'div')->textImplode(', ', TRUE));

    // Test with empties
    $xml = '<?xml version="1.0"?><root><div>Text A</div><div> </div><div>Text B</div></root>';
    $this->assertEquals('Text A- -Text B', qp($xml, 'div')->textImplode('-', FALSE));
  }

  public function testChildrenText() {
    $xml = '<?xml version="1.0"?><root><wrapper>
    NOT ME!
    <div>Text A </div>
    <div>
    </div><div>Text B</div></wrapper></root>';
    $this->assertEquals('Text A , Text B', qp($xml, 'div')->childrenText(', ', TRUE), 'Just inner text.');
  }

  public function testNext() {
    $file = DATA_FILE;
    $this->assertEquals('inner', qp($file, 'unary')->next()->tag());
    $this->assertEquals('foot', qp($file, 'inner')->next()->eq(1)->tag());

    $this->assertEquals('foot', qp($file, 'unary')->next('foot')->tag());

    // Regression test for issue eabrand identified:

    $qp = qp(\QueryPath::HTML_STUB, 'body')->append('<div></div><p>Hello</p><p>Goodbye</p>')
      ->children('p')
      ->after('<p>new paragraph</p>');

    $testarray = array('new paragraph', 'Goodbye', 'new paragraph');

    //throw new Exception($qp->top()->xml());

    $qp = $qp->top('p:first-of-type');
    $this->assertEquals('Hello', $qp->text(), "Test First P " . $qp->top()->html());
    $i = 0;
    while($qp->next('p')->html() != null) {
      $qp = $qp->next('p');
      $this->assertEquals(1, count($qp));
      $this->assertEquals($testarray[$i], $qp->text(), $i . " didn't match " . $qp->top()->xml() );
      $i++;
    }
    $this->assertEquals(3, $i);
//    $this->assertEquals('new paragraph', $qp->next()->text(), "Test Newly Added P");
//    $this->assertEquals('Goodbye', $qp->next()->text(), "Test third P");
//    $this->assertEquals('new paragraph', $qp->next()->text(), "Test Other Newly Added P");
  }
  public function testPrev() {
    $file = DATA_FILE;
    $this->assertEquals('head', qp($file, 'unary')->prev()->tag());
    $this->assertEquals('inner', qp($file, 'inner')->prev()->eq(1)->tag());
    $this->assertEquals('head', qp($file, 'foot')->prev('head')->tag());
  }
  public function testNextAll() {
    $file = DATA_FILE;
    $this->assertEquals(3, qp($file, '#one')->nextAll()->size());
    $this->assertEquals(2, qp($file, 'unary')->nextAll('inner')->size());
  }
  public function testPrevAll() {
    $file = DATA_FILE;
    $this->assertEquals(3, qp($file, '#four')->prevAll()->size());
    $this->assertEquals(2, qp($file, 'foot')->prevAll('inner')->size());
  }
  public function testParent() {
    $file = DATA_FILE;
    $this->assertEquals('root', qp($file, 'unary')->parent()->tag());
    $this->assertEquals('root', qp($file, 'li')->parent('root')->tag());
    $this->assertEquals(2, qp($file, 'li')->parent()->size());
  }
  public function testClosest() {
    $file = DATA_FILE;
    $this->assertEquals('root', qp($file, 'li')->parent('root')->tag());

    $xml = '<?xml version="1.0"?>
    <root>
      <a class="foo">
        <b/>
      </a>
      <b class="foo"/>
    </root>';
    $this->assertEquals(2, qp($xml, 'b')->closest('.foo')->size());
  }

  public function testParents() {
    $file = DATA_FILE;

    // Three: two inners and a root.
    $this->assertEquals(3, qp($file, 'li')->parents()->size());
    $this->assertEquals('root', qp($file, 'li')->parents('root')->tag());
  }

  public function testCloneAll() {
    $file = DATA_FILE;

    // Shallow test
    $qp = qp($file, 'unary');
    $one = $qp->get(0);
    $two = $qp->cloneAll()->get(0);
    $this->assertTrue($one !== $two);
    $this->assertEquals('unary', $two->tagName);

    // Deep test: make sure children are also cloned.
    $qp = qp($file, 'inner');
    $one = $qp->find('li')->get(0);
    $two = $qp->top('inner')->cloneAll(TRUE)->findInPlace('li')->get(0);
    $this->assertEquals('li', $two->tagName);
    $this->assertTrue($one !== $two);
  }

  public function testBranch() {
    $qp = qp(\QueryPath::HTML_STUB);
    $branch = $qp->branch();
    $branch->top('title')->text('Title');
    $qp->top('title')->text('FOOOOO')->top();
    $qp->find('body')->text('This is the body');

    $this->assertEquals($qp->top('title')->text(), $branch->top('title')->text(), $branch->top()->html());

    $qp = qp(\QueryPath::HTML_STUB);
    $branch = $qp->branch('title');
    $branch->find('title')->text('Title');
    $qp->find('body')->text('This is the body');
    $this->assertEquals($qp->top()->find('title')->text(), $branch->text());
  }

  public function testXpath() {
    $file = DATA_FILE;

    $this->assertEquals('head', qp($file)->xpath("//*[@id='head']")->tag());
  }

  public function test__clone() {
    $file = DATA_FILE;

    $qp = qp($file, 'inner:first-of-type');
    $qp2 = clone $qp;
    $this->assertFalse($qp === $qp2);
    $qp2->findInPlace('li')->attr('foo', 'bar');
    $this->assertEquals('', $qp->find('li')->attr('foo'));
    $this->assertEquals('bar', $qp2->attr('foo'), $qp2->top()->xml());
  }

  public function testStub() {
    $this->assertEquals(1, qp(\QueryPath::HTML_STUB)->find('title')->size());
  }

  public function testIterator() {

    $qp = qp(\QueryPath::HTML_STUB, 'body')->append('<li/><li/><li/><li/>');

    $this->assertEquals(4, $qp->find('li')->size());
    $i = 0;
    foreach ($qp->find('li') as $li) {
      ++$i;
      $li->text('foo');
    }
    $this->assertEquals(4, $i);
    $this->assertEquals('foofoofoofoo', $qp->top()->find('li')->text());
  }

  public function testModeratelySizedDocument() {

    $this->assertEquals(1, qp(MEDIUM_FILE)->size());

    $contents = file_get_contents(MEDIUM_FILE);
    $this->assertEquals(1, qp($contents)->size());
  }

  /**
   * @deprecated
   */
  public function testSize() {
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $this->assertEquals(5, $qp->size());
  }

  public function testCount() {
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $this->assertEquals(5, $qp->count());

    // Test that this is exposed to PHP's Countable logic.
    $this->assertEquals(5, count(qp($file, 'li')));

  }

  public function testLength() {

    // Test that the length attribute works exactly the same as size.
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $this->assertEquals(5, $qp->length);


  }

  public function testDocument() {
    $file = DATA_FILE;
    $doc1 = new \DOMDocument('1.0');
    $doc1->load($file);
    $qp = qp($doc1);

    $this->assertEquals($doc1, $qp->document());

    // Ensure that adding to the DOMDocument is accessible to QP:
    $ele = $doc1->createElement('testDocument');
    $doc1->documentElement->appendChild($ele);

    $this->assertEquals(1, $qp->find('testDocument')->size());
  }

  /*
  public function test__get() {
    // Test that other properties are not interferred with by __get().
    $file = DATA_FILE;
    $options = array('QueryPath_class' => 'QueryPathExtended');
    $foo = qp($file,'li', $options)->foo;

    $this->assertEquals('bar', $foo);
  }
  */

  /**
   * @  expectedException \QueryPath\Exception
   */
   /*
  public function testFailed__get() {
    // This should generate an error because 'last' is protected.
    qp(DATA_FILE)->last;
  }
  */

  public function testDetach() {
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $start = $qp->size();
    $finish = $qp->detach()->size();
    $this->assertEquals($start, $finish);
    $this->assertEquals(0, $qp->find(':root li')->size());
  }

  public function testAttach() {
    $file = DATA_FILE;
    $qp = qp($file, 'li');
    $start = $qp->size();
    $finish = $qp->detach()->size();
    $dest = qp('<?xml version="1.0"?><root><dest/></root>', 'dest');
    $qp = $qp->attach($dest);
    $this->assertEquals(5, $dest->find(':root li')->size());
  }

  public function testEmptyElement() {
    $file = DATA_FILE;
    $this->assertEquals(0, qp($file, '#inner-two')->emptyElement()->find('li')->size());
    $this->assertEquals('<inner id="inner-two"/>', qp($file, '#inner-two')->emptyElement()->html());

    // Make sure text children get wiped out, too.
    $this->assertEquals('', qp($file, 'foot')->emptyElement()->text());
  }

  public function testHas() {
    $file = DATA_FILE;

    // Test with DOMNode object
    $qp = qp($file, 'foot');
    $selector = $qp->get(0);
    $qp = $qp->top('root')->has($selector);

    // This should have one element named 'root'.
    $this->assertEquals(1, $qp->size(), 'One element is a parent of foot');
    $this->assertEquals('root', $qp->tag(), 'Root has foot.');

    // Test with CSS selector
    $qp = qp($file, 'root')->has('foot');

    // This should have one element named 'root'.
    $this->assertEquals(1, $qp->size(), 'One element is a parent of foot');
    $this->assertEquals('root', $qp->tag(), 'Root has foot.');

    // Test multiple matches.
    $qp = qp($file, '#docRoot, #inner-two')->has('#five');
    $this->assertEquals(2, $qp->size(), 'Two elements are parents of #five');
    $this->assertEquals('inner', $qp->get(0)->tagName, 'Inner has li.');

    /*
    $this->assertEquals(qp($file, '#one')->children()->get(), qp($file, '#inner-one')->has($selector)->get(), "Both should be empty/false");
    $qp = qp($file, 'root')->children("inner");
    $selector = qp($file, '#two');
    $this->assertNotEquals(qp($file, '#head'), qp($file, '#inner-one')->has($selector));
    $this->assertEquals(qp($file, 'root'), qp($file, 'root')->has($selector), "Should both have 1 element - root");
    */
  }

  public function testNextUntil() {
    $file = DATA_FILE;
    $this->assertEquals(3, qp($file, '#one')->nextUntil()->size());
    $this->assertEquals(2, qp($file, 'li')->nextUntil('#three')->size());
  }

  public function testPrevUntil() {
    $file = DATA_FILE;
    $this->assertEquals(3, qp($file, '#four')->prevUntil()->size());
    $this->assertEquals(2, qp($file, 'foot')->prevUntil('unary')->size());
  }

  public function testEven() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, 'inner')->even()->size());
    $this->assertEquals(2, qp($file, 'li')->even()->size());
  }

  public function testOdd() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, 'inner')->odd()->size());
    $this->assertEquals(3, qp($file, 'li')->odd()->size());
  }

  public function testFirst() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, 'inner')->first()->size());
    $this->assertEquals(1, qp($file, 'li')->first()->size());
    $this->assertEquals("Hello", qp($file, 'li')->first()->text());
  }

  public function testFirstChild() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, '#inner-one')->firstChild()->size());
    $this->assertEquals("Hello", qp($file, '#inner-one')->firstChild()->text());
  }

  public function testLast() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, 'inner')->last()->size());
    $this->assertEquals(1, qp($file, 'li')->last()->size());
    $this->assertEquals('', qp($file, 'li')->last()->text());
  }

  public function testLastChild() {
    $file = DATA_FILE;
    $this->assertEquals(1, qp($file, '#inner-one')->lastChild()->size());
    $this->assertEquals("Last", qp($file, '#inner-one')->lastChild()->text());
  }

  public function testParentsUntil() {
    $file = DATA_FILE;

    // Three: two inners and a root.
    $this->assertEquals(3, qp($file, 'li')->parentsUntil()->size());
    $this->assertEquals(2, qp($file, 'li')->parentsUntil('root')->size());
  }

  public function testSort() {
    $xml = '<?xml version="1.0"?><r><s/><i>1</i><i>5</i><i>2</i><i>1</i><e/></r>';

    // Canary.
    $qp = qp($xml, 'i');
    $expect = array(1, 5, 2, 1);
    foreach($qp as $item) {
      $this->assertEquals(array_shift($expect), $item->text());
    }

    // Test simple ordering.
    $comp = function (\DOMNode $a, \DOMNode $b) {
      if ($a->textContent == $b->textContent) {
        return 0;
      }
      return $a->textContent > $b->textContent ? 1 : -1;
    };
    $qp = qp($xml, 'i')->sort($comp);
    $expect = array(1, 1, 2, 5);
    foreach($qp as $item) {
      $this->assertEquals(array_shift($expect), $item->text());
    }

    $comp = function (\DOMNode $a, \DOMNode $b) {
      $qpa = qp($a);
      $qpb = qp($b);

      if ($qpa->text() == $qpb->text()) {
        return 0;
      }
      return $qpa->text()> $qpb->text()? 1 : -1;
    };
    $qp = qp($xml, 'i')->sort($comp);
    $expect = array(1, 1, 2, 5);
    foreach($qp as $item) {
      $this->assertEquals(array_shift($expect), $item->text());
    }

    // Test DOM re-ordering
    $comp = function (\DOMNode $a, \DOMNode $b) {
      if ($a->textContent == $b->textContent) {
        return 0;
      }
      return $a->textContent > $b->textContent ? 1 : -1;
    };
    $qp = qp($xml, 'i')->sort($comp, TRUE);
    $expect = array(1, 1, 2, 5);
    foreach($qp as $item) {
      $this->assertEquals(array_shift($expect), $item->text());
    }
    $res = $qp->top()->xml();
    $expect_xml = '<?xml version="1.0"?><r><s/><i>1</i><i>1</i><i>2</i><i>5</i><e/></r>';
    $this->assertXmlStringEqualsXmlString($expect_xml, $res);
  }

  /**
   * Regression test for issue #14.
   */
  public function testRegressionFindOptimizations() {
    $xml = '<?xml version="1.0"?><root>
      <item id="outside">
        <item>
          <item id="inside">Test</item>
        </item>
      </item>
    </root>';

    // From inside, should not be able to find outside.
    $this->assertEquals(0, qp($xml, '#inside')->find('#outside')->size());

    $xml = '<?xml version="1.0"?><root>
      <item class="outside">
        <item>
          <item class="inside">Test</item>
        </item>
      </item>
    </root>';
    // From inside, should not be able to find outside.
    $this->assertEquals(0, qp($xml, '.inside')->find('.outside')->size());
  }

  public function testDataURL() {

    $text = 'Hi!'; // Base-64 encoded value would be SGkh
    $xml = '<?xml version="1.0"?><root><item/></root>';

    $qp = qp($xml, 'item')->dataURL('secret', $text, 'text/plain');

    $this->assertEquals(1, $qp->top('item[secret]')->size(), 'One attr should be added.');

    $this->assertEquals('data:text/plain;base64,SGkh', $qp->attr('secret'), 'Attr value should be data URL.');

    $result = $qp->dataURL('secret');
    $this->assertEquals(2, count($result), 'Should return two-array.');
    $this->assertEquals($text, $result['data'] , 'Should return original data, decoded.');
    $this->assertEquals('text/plain', $result['mime'], 'Should return the original MIME');
  }

  public function testEncodeDataURL() {
    $data = \QueryPath::encodeDataURL('Hi!', 'text/plain');
    $this->assertEquals('data:text/plain;base64,SGkh', $data);
  }
}

/**
 * A simple mock for testing qp()'s abstract factory.
 *
 * @ingroup querypath_tests
 */
class QueryPathExtended extends DOMQuery {
  public $foo = 'bar';
  public function foonator() {
    return TRUE;
  }
}

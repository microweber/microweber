<?php
/** @file
 * CSS Event handling tests
 */
namespace QueryPath\Tests;

require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\Token;
use \QueryPath\CSS\QueryPathEventHandler;
use \QueryPath\CSS\Parser;
use \QueryPath\CSS\EventHandler;

/**
 * Tests for QueryPathEventHandler class.
 * @ingroup querypath_tests
 * @group deprecated
 */
class QueryPathEventHandlerTest extends TestCase {


  var $xml = '<?xml version="1.0" ?>
  <html>
  <head>
    <title>This is the title</title>
  </head>
  <body>
    <div id="one">
      <div id="two" class="class-one">
        <div id="three">
        Inner text.
        </div>
      </div>
    </div>
    <span class="class-two">Nada</span>
    <p><p><p><p><p><p><p class="Odd"><p>8</p></p></p></p></p></p></p></p>
    <ul>
      <li class="Odd" id="li-one">Odd</li>
      <li class="even" id="li-two">Even</li>
      <li class="Odd" id="li-three">Odd</li>
      <li class="even" id="li-four">Even</li>
      <li class="Odd" id="li-five">Odd</li>
      <li class="even" id="li-six">Even</li>
      <li class="Odd" id="li-seven">Odd</li>
      <li class="even" id="li-eight">Even</li>
      <li class="Odd" id="li-nine">Odd</li>
      <li class="even" id="li-ten">Even</li>
    </ul>
  </body>
  </html>
  ';

  private function firstMatch($matches) {
    $matches->rewind();
    return $matches->current();
  }
  private function nthMatch($matches, $n = 0) {
    foreach ($matches as $m) {
      if ($matches->key() == $n) return $m;
    }
  }

  public function testGetMatches() {
    // Test root element:
    $xml = '<?xml version="1.0" ?><test><inside/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test handing it a DOM Document
    $handler = new QueryPathEventHandler($doc);
    $matches = $handler->getMatches();
    $this->assertTrue($matches->count() == 1);
    $match = $this->firstMatch($matches);
    $this->assertEquals('test', $match->tagName);

    // Test handling single element
    $root = $doc->documentElement;
    $handler = new QueryPathEventHandler($root);
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('test', $match->tagName);

    // Test handling a node list
    $eles = $doc->getElementsByTagName('inside');
    $handler = new QueryPathEventHandler($eles);
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('inside', $match->tagName);

    // Test handling an array of elements
    $eles = $doc->getElementsByTagName('inside');
    $array = array();
    foreach ($eles as $ele) $array[] = $ele;
    $handler = new QueryPathEventHandler($array);
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('inside', $match->tagName);
  }

  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testEmptySelector() {
    $xml = '<?xml version="1.0" ?><t:test xmlns:t="urn:foo/bar"><t:inside id="first"/>Text<t:inside/><inside/></t:test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Basic test
    $handler = new QueryPathEventHandler($doc);
    $handler->find('');
    $matches = $handler->getMatches();
    $this->assertEquals(0, $matches->count());
  }

  public function testElementNS() {
    $xml = '<?xml version="1.0" ?><t:test xmlns:t="urn:foo/bar"><t:inside id="first"/>Text<t:inside/><inside/></t:test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Basic test
    $handler = new QueryPathEventHandler($doc);
    $handler->find('t|inside');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('t:inside', $match->tagName);

    // Basic test
    $handler = new QueryPathEventHandler($doc);
    $handler->find('t|test');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('t:test', $match->tagName);
  }


  /**
   * @expectedException \QueryPath\CSS\ParseException
   */
  public function testFailedElementNS() {
    $xml = '<?xml version="1.0" ?><t:test xmlns:t="urn:foo/bar"><t:inside id="first"/>Text<t:inside/><inside/></t:test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('myns\:mytest');
  }

  public function testElement() {
    $xml = '<?xml version="1.0" ?><test><inside id="first"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Basic test
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('inside', $match->tagName);

    $doc = new \DomDocument();
    $doc->loadXML($this->xml);

    // Test getting nested
    $handler = new QueryPathEventHandler($doc);
    $handler->find('div');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('div', $match->tagName);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test getting a list
    $handler = new QueryPathEventHandler($doc);
    $handler->find('li');
    $matches = $handler->getMatches();
    $this->assertEquals(10, $matches->count());
    $match = $this->firstMatch($matches);
    //$this->assertEquals('div', $match->tagName);
    $this->assertEquals('li-one', $match->getAttribute('id'));

    // Test getting the root element
    $handler = new QueryPathEventHandler($doc);
    $handler->find('html');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('html', $match->tagName);
  }

  public function testElementId() {
    // Test root element:
    $xml = '<?xml version="1.0" ?><test><inside id="first"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#first');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('inside', $match->tagName);

    // Test a search with restricted scope:
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside#first');
    $matches = $handler->getMatches();

    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('inside', $match->tagName);

  }

  public function testAnyElementInNS() {
    $xml = '<?xml version="1.0" ?><ns1:test xmlns:ns1="urn:foo/bar"><ns1:inside/>Text<ns1:inside/></ns1:test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test handing it a DOM Document
    $handler = new QueryPathEventHandler($doc);
    $handler->find('ns1|*');
    $matches = $handler->getMatches();

    $this->assertEquals(3, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('ns1:test', $match->tagName);

    // Test Issue #30:
    $xml = '<?xml version="1.0" ?>
    <ns1:test xmlns:ns1="urn:foo/bar">
      <ns1:inside>
        <ns1:insideInside>Test</ns1:insideInside>
      </ns1:inside>
    </ns1:test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('ns1|test>*');
    $matches = $handler->getMatches();

    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('ns1:inside', $match->tagName);
  }

  public function testAnyElement() {
    $xml = '<?xml version="1.0" ?><test><inside/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test handing it a DOM Document
    $handler = new QueryPathEventHandler($doc);
    $handler->find('*');
    $matches = $handler->getMatches();

    $this->assertEquals(3, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('test', $match->tagName);

    $doc = new \DomDocument();
    $doc->loadXML($this->xml);

    // Test handing it a DOM Document
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#two *');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('three', $match->getAttribute('id'));

    // Regression for issue #30
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#one>*');
    $matches = $handler->getMatches();

    $this->assertEquals(1, $matches->count(), 'Should match just top div.');
    $match = $this->firstMatch($matches);
    $this->assertEquals('two', $match->getAttribute('id'), 'Should match ID #two');
  }

  public function testElementClass() {
    $xml = '<?xml version="1.0" ?><test><inside class="foo" id="one"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test basic class
    $handler = new QueryPathEventHandler($doc);
    $handler->find('.foo');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test class in element
    $doc = new \DomDocument();
    $doc->loadXML($this->xml);
    $handler = new QueryPathEventHandler($doc);
    $handler->find('li.Odd');
    $matches = $handler->getMatches();
    $this->assertEquals(5, $matches->count());
    $match = $this->nthMatch($matches, 4);
    $this->assertEquals('li-nine', $match->getAttribute('id'));

    // Test ID/class combo
    $handler = new QueryPathEventHandler($doc);
    $handler->find('.Odd#li-nine');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('li-nine', $match->getAttribute('id'));

  }

  public function testDirectDescendant() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <inside class="foo" id="one"/>
      Text
      <inside id="two">
        <inside id="inner-one"/>
      </inside>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test direct descendent
    $handler = new QueryPathEventHandler($doc);
    $handler->find('test > inside');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->nthMatch($matches, 1);
    $this->assertEquals('two', $match->getAttribute('id'));

  }

  public function testAttribute() {
    $xml = '<?xml version="1.0" ?><test><inside id="one" name="antidisestablishmentarianism"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test match on attr name
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

     // Test broken form
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[@name]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test match on attr name and equals value
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name="antidisestablishmentarianism"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test match on containsInString
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name*="disestablish"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test match on beginsWith
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name^="anti"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test match on endsWith
    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name$="ism"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test containsWithSpace
    $xml = '<?xml version="1.0" ?><test><inside id="one" name="anti dis establishment arian ism"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name~="dis"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    // Test containsWithHyphen
    $xml = '<?xml version="1.0" ?><test><inside id="one" name="en-us"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside[name|="us"]');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

  }

  public function testPseudoClassLang() {

    $xml = '<?xml version="1.0" ?><test><inside lang="en-us" id="one"/>Text<inside/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':lang(en-us)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside:lang(en)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside:lang(us)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    $xml = '<?xml version="1.0" ?><test><inside lang="en-us" id="one"/>Text<inside lang="us" id="two"/></test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':lang(us)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->nthMatch($matches, 1);
    $this->assertEquals('two', $match->getAttribute('id'));

    $xml = '<?xml version="1.0" ?>
    <test xmlns="http://aleph-null.tv/xml" xmlns:xml="http://www.w3.org/XML/1998/namespace">
     <inside lang="en-us" id="one"/>Text
     <inside xml:lang="en-us" id="two"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':lang(us)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $match = $this->nthMatch($matches, 1);
    $this->assertEquals('two', $match->getAttribute('id'));
  }

  public function testPseudoClassEnabledDisabledChecked() {
    $xml = '<?xml version="1.0" ?>
    <test>
     <inside enabled="enabled" id="one"/>Text
     <inside disabled="disabled" id="two"/>
     <inside checked="FOOOOO" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':enabled');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('one', $match->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':disabled');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('two', $match->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find(':checked()');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $match = $this->firstMatch($matches);
    $this->assertEquals('three', $match->getAttribute('id'));
  }

  public function testPseudoClassLink() {
    $xml = '<?xml version="1.0"?><a><b href="foo"/><c href="foo"/></a>';
    $qp = qp($xml, ':link');
    $this->assertEquals(2, $qp->size());
  }

  public function testPseudoClassXReset() {
    $xml = '<?xml version="1.0" ?>
    <test>
     <inside enabled="enabled" id="one"/>Text
     <inside disabled="disabled" id="two"/>
     <inside checked="FOOOOO" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('inside');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $handler->find(':x-reset');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('test', $this->firstMatch($matches)->tagName);
  }

  public function testPseudoClassRoot() {
    $xml = '<?xml version="1.0" ?>
    <test>
     <inside enabled="enabled" id="one"/>Text
     <inside disabled="disabled" id="two"/>
     <inside checked="FOOOOO" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);
    $start = $doc->getElementsByTagName('inside');

    // Start "deep in the doc" and traverse backward.
    $handler = new QueryPathEventHandler($start);
    $handler->find(':root');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('test', $this->firstMatch($matches)->tagName);
  }

  // Test removed so I can re-declare
  // listPeerElements as private.
  public function xtestListPeerElements() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four"/>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test full list
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#one');
    $matches = $handler->getMatches();
    $peers = $handler->listPeerElements($this->firstMatch($matches));
    $this->assertEquals(6, count($peers));
  }
  /*
  public function testChildAtIndex() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four"/>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test full list
    $handler = new QueryPathEventHandler($doc);
    $handler->find('test:child-at-index(1)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->nthMatch($matches, 1)->getAttribute('id'));
  }*/

  public function testPseudoClassNthChild() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four"/>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test full list
    $handler = new QueryPathEventHandler($doc);
    $handler->find(':root :even');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('four', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test restricted to specific element
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:even');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('four', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test restricted to specific element, odd this time
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:odd');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(odd)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(odd)');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(2n+1)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(2n+1)');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(2n) (even)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(2n)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('four', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Not totally sure what should be returned here
    // Test nth-child(-2n)
    // $handler = new QueryPathEventHandler($doc);
    //     $handler->find('i:nth-child(-2n)');
    //     $matches = $handler->getMatches();
    //     $this->assertEquals(2, $matches->count());
    //     $this->assertEquals('four', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(2n-1) (odd, equiv to 2n + 1)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(2n-1)');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(4n) (every fourth row)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(4n)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('four', $this->nthMatch($matches, 0)->getAttribute('id'));

    // Test nth-child(4n+1) (first of every four rows)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(4n+1)');
    $matches = $handler->getMatches();
    // Should match rows one and five
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('five', $this->nthMatch($matches, 1)->getAttribute('id'));

    // Test nth-child(1) (First row)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(1)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    // Test nth-child(0n-0) (Empty list)
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(0n-0)');
    $matches = $handler->getMatches();
    $this->assertEquals(0, $matches->count());

    // Test nth-child(-n+3) (First three lines)
    // $handler = new QueryPathEventHandler($doc);
    // $handler->find('i:nth-child(-n+3)');
    // $matches = $handler->getMatches();
    // $this->assertEquals(3, $matches->count());

    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three">
        <i class="odd" id="inner-one"/>
        <i class="even" id="inner-two"/>
      </i>
      <i class="even" id="four"/>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test nested items.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-child(odd)');
    $matches = $handler->getMatches();
    $this->assertEquals(4, $matches->count());
    $matchIDs = array();
    foreach ($matches as $m) {
      $matchIDs[] = $m->getAttribute('id');
    }
//    $matchIDs = sort($matchIDs);
    $this->assertEquals(array('one', 'three', 'inner-one', 'five'), $matchIDs);
    //$this->assertEquals('inner-one', $matches[3]->getAttribute('id'));

  }
  public function testPseudoClassOnlyChild() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:only-child');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="odd" id="two"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:only-child');
    $matches = $handler->getMatches();
    $this->assertEquals(0, $matches->count());
    //$this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassOnlyOfType() {
    // TODO: Added this late (it was missing in original test),
    // and I'm not sure if the assumed behavior is correct.

    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:only-of-type');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));


    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="odd" id="two"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:only-of-type');
    $matches = $handler->getMatches();
    $this->assertEquals(0, $matches->count());
  }

  public function testPseudoClassFirstChild() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four">
        <i class="odd" id="inner-one"/>
        <i class="even" id="inner-two"/>
        <i class="odd" id="inner-three"/>
        <i class="even" id="inner-four"/>
      </i>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#four > i:first-child');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('inner-one', $this->firstMatch($matches)->getAttribute('id'));

    // Test for two last children
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:first-child');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-one', $this->nthMatch($matches, 1)->getAttribute('id'));
  }

  public function testPseudoClassLastChild() {
    //print '----' . PHP_EOL;
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four">
        <i class="odd" id="inner-one"/>
        <i class="even" id="inner-two"/>
        <i class="odd" id="inner-three"/>
        <i class="even" id="inner-four"/>
      </i>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test single last child.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#four > i:last-child');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('inner-four', $this->nthMatch($matches, 0)->getAttribute('id'));

    // Test for two last children
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:last-child');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-four', $this->nthMatch($matches, 0)->getAttribute('id'));
    $this->assertEquals('five', $this->nthMatch($matches, 1)->getAttribute('id'));
  }

  public function testPseudoClassNthLastChild() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
      <i class="even" id="four">
        <i class="odd" id="inner-one"/>
        <i class="even" id="inner-two"/>
        <i class="odd" id="inner-three"/>
        <i class="even" id="inner-four"/>
      </i>
      <i class="odd" id="five"/>
      <e class="even" id="six"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#four > i:nth-last-child(odd)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-two', $this->nthMatch($matches, 0)->getAttribute('id'));
    $this->assertEquals('inner-four', $this->nthMatch($matches, 1)->getAttribute('id'));

    // According to spec, this should be last two elements.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#four > i:nth-last-child(-1n+2)');
    $matches = $handler->getMatches();
    //print $this->firstMatch($matches)->getAttribute('id');
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-three', $this->nthMatch($matches, 0)->getAttribute('id'));
    $this->assertEquals('inner-four', $this->nthMatch($matches, 1)->getAttribute('id'));
  }

  public function testPseudoClassFirstOfType() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <n class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:first-of-type(odd)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassNthFirstOfType() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <n class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:first-of-type(1)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassLastOfType() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <n class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:last-of-type(odd)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('three', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoNthClassLastOfType() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <n class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-last-of-type(1)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('three', $this->firstMatch($matches)->getAttribute('id'));


    // Issue #56: an+b not working.
    $xml = '<?xml version="1.0"?>
    <root>
    <div>I am the first div.</div>
    <div>I am the second div.</div>
    <div>I am the third div.</div>
    <div>I am the fourth div.</div>
    <div id="five">I am the fifth div.</div>
    <div id="six">I am the sixth div.</div>
    <div id="seven">I am the seventh div.</div>
    </root>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('div:nth-last-of-type(-n+3)');
    $matches = $handler->getMatches();

    $this->assertEquals(3, $matches->count());
    $this->assertEquals('five', $this->firstMatch($matches)->getAttribute('id'));

  }

  public function testPseudoClassEmpty() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <n class="odd" id="one"/>
      <i class="even" id="two"></i>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('n:empty');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:empty');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassFirst() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:first');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassLast() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:last');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('three', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassGT() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:gt(1)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }
  public function testPseudoClassLT() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    // Test alternate rows from the end.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:lt(3)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('one', $this->nthMatch($matches,0)->getAttribute('id'));
    $this->assertEquals('two', $this->nthMatch($matches,1)->getAttribute('id'));
  }
  public function testPseudoClassNTH() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth(2)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:eq(2)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }
  public function testPseudoClassNthOfType() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <i class="odd" id="one"/>
      <i class="even" id="two"/>
      <i class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('i:nth-of-type(2)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassFormElements() {
    $form = array('text', 'radio', 'checkbox', 'button', 'password');
    $xml = '<?xml version="1.0" ?>
    <test>
      <input type="%s" class="odd" id="one"/>
    </test>';

    foreach ($form as $item) {
      $doc = new \DomDocument();
      $doc->loadXML(sprintf($xml, $item));

      $handler = new QueryPathEventHandler($doc);
      $handler->find(':' . $item);
      $matches = $handler->getMatches();
      $this->assertEquals(1, $matches->count());
      $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
    }
  }

  public function testPseudoClassHeader() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <h1 class="odd" id="one"/>
      <h2 class="even" id="two"/>
      <h6 class="odd" id="three"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('test :header');
    $matches = $handler->getMatches();
    $this->assertEquals(3, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 2)->getAttribute('id'));
  }

  public function testPseudoClassContains() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <p id="one">This is text.</p>
      <p id="two"><i>More text</i></p>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains(This is text.)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('* :contains(More text)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count(), 'Matches two instance of same text?');
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains("This is text.")');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count(), 'Quoted text matches unquoted pcdata');
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains(\\\'This is text.\\\')');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count(), 'One match for quoted string.');
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    // Test for issue #32
    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains(text)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count(), 'Two matches for fragment of string.');
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

  }

  public function testPseudoClassContainsExactly() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <p id="one">This is text.</p>
      <p id="two"><i>More text</i></p>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains(This is text.)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('* :contains(More text)');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count(), 'Matches two instance of same text.');
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains("This is text.")');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count(), 'Quoted text matches unquoted pcdata');
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('p:contains(\\\'This is text.\\\')');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count(), 'One match for quoted string.');
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassHas() {
    $xml = '<?xml version="1.0" ?>
    <test>
      <outer id="one">
        <inner/>
      </outer>
      <outer id="two"/>
    </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('outer:has(inner)');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoClassNot() {
     $xml = '<?xml version="1.0" ?>
      <test>
        <outer id="one">
          <inner/>
        </outer>
        <outer id="two" class="notMe"/>
      </test>';
      $doc = new \DomDocument();
      $doc->loadXML($xml);

      $handler = new QueryPathEventHandler($doc);
      $handler->find('outer:not(#one)');
      $matches = $handler->getMatches();
      $this->assertEquals(1, $matches->count());
      $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

      $handler = new QueryPathEventHandler($doc);
      $handler->find('outer:not(inner)');
      $matches = $handler->getMatches();
      $this->assertEquals(1, $matches->count());
      $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

      $handler = new QueryPathEventHandler($doc);
      $handler->find('outer:not(.notMe)');
      $matches = $handler->getMatches();
      $this->assertEquals(1, $matches->count());
      $this->assertEquals('one', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testPseudoElement() {
    $xml = '<?xml version="1.0" ?>
      <test>
        <outer id="one">Texts

        More text</outer>
        <outer id="two" class="notMe"/>
      </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('outer::first-letter');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('T', $this->firstMatch($matches)->textContent);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('outer::first-line');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('Texts', $this->firstMatch($matches)->textContent);
  }

  public function testAdjacent() {
    $xml = '<?xml version="1.0" ?>
      <test>
        <li id="one"/><li id="two"/><li id="three">
          <li id="inner-one">
            <li id="inner-inner-one"/>
            <li id="inner-inner-one"/>
          </li>
          <li id="inner-two"/>
        </li>
        <li id="four"/>
        <li id="five"/>
      </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#one + li');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('two', $this->firstMatch($matches)->getAttribute('id'));

    // Tell it to ignore whitespace nodes.
    $doc->loadXML($xml, LIBXML_NOBLANKS);

    // Test with whitespace sensitivity weakened.
    $handler = new QueryPathEventHandler($doc);
    $handler->find('#four + li');
    $matches = $handler->getMatches();
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('five', $this->firstMatch($matches)->getAttribute('id'));
  }
  public function testAnotherSelector() {
    $xml = '<?xml version="1.0" ?>
      <test>
        <li id="one"/><li id="two"/><li id="three">
          <li id="inner-one">
            <li id="inner-inner-one"/>
            <li id="inner-inner-one"/>
          </li>
          <li id="inner-two"/>
        </li>
        <li id="four"/>
        <li id="five"/>
      </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#one, #two');
    $matches = $handler->getMatches();
    //print $this->firstMatch($matches)->getAttribute('id') . PHP_EOL;
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('two', $this->nthMatch($matches, 1)->getAttribute('id'));

  }
  public function testSibling() {
    $xml = '<?xml version="1.0" ?>
      <test>
        <li id="one"/><li id="two"/><li id="three">
          <li id="inner-one">
            <li id="inner-inner-one"/>
            <il id="inner-inner-two"/>
            <li id="dont-match-me"/>
          </li>
          <li id="inner-two"/>
        </li>
        <li id="four"/>
        <li id="five"/>
      </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#one ~ li');
    $matches = $handler->getMatches();
    //print $this->firstMatch($matches)->getAttribute('id') . PHP_EOL;
    $this->assertEquals(4, $matches->count());
    $this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#two ~ li');
    $matches = $handler->getMatches();
    //print $this->firstMatch($matches)->getAttribute('id') . PHP_EOL;
    $this->assertEquals(3, $matches->count());
    //$this->assertEquals('three', $this->nthMatch($matches, 1)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('#inner-one > li ~ il');
    $matches = $handler->getMatches();
    //print $this->firstMatch($matches)->getAttribute('id') . PHP_EOL;
    $this->assertEquals(1, $matches->count());
    $this->assertEquals('inner-inner-two', $this->firstMatch($matches)->getAttribute('id'));
  }

  public function testAnyDescendant() {
    $xml = '<?xml version="1.0" ?>
      <test>
        <li id="one"/><li id="two"/><li id="three">
          <li id="inner-one" class="foo">
            <li id="inner-inner-one" class="foo"/>
            <il id="inner-inner-two"/>
            <li id="dont-match-me"/>
          </li>
          <li id="inner-two"/>
        </li>
        <li id="four"/>
        <li id="five"/>
      </test>';
    $doc = new \DomDocument();
    $doc->loadXML($xml);

    $handler = new QueryPathEventHandler($doc);
    $handler->find('*');
    $matches = $handler->getMatches();
    $this->assertEquals(11, $matches->count());

    $handler = new QueryPathEventHandler($doc);
    $handler->find('*.foo');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-inner-one', $this->nthMatch($matches, 1)->getAttribute('id'));

    $handler = new QueryPathEventHandler($doc);
    $handler->find('test > li *.foo');
    $matches = $handler->getMatches();
    $this->assertEquals(2, $matches->count());
    $this->assertEquals('inner-inner-one', $this->nthMatch($matches, 1)->getAttribute('id'));
  }
}

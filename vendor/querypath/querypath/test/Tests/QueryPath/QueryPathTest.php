<?php
namespace QueryPath\Tests;

require_once 'PHPUnit/Autoload.php';
require_once __DIR__ . '/TestCase.php';
//require_once __DIR__ . '/../../../src/qp.php';
require_once __DIR__ . '/../../../src/QueryPath.php';

class QueryPathTest extends TestCase {

  public function testWith() {
    $qp = \QueryPath::with(\QueryPath::XHTML_STUB);

    $this->assertInstanceOf('\QueryPath\DOMQuery', $qp);

  }

  public function testWithHTML() {
    $qp = \QueryPath::with(\QueryPath::HTML_STUB);

    $this->assertInstanceOf('\QueryPath\DOMQuery', $qp);
  }

  public function testWithXML() {
    $qp = \QueryPath::with(\QueryPath::XHTML_STUB);

    $this->assertInstanceOf('\QueryPath\DOMQuery', $qp);
  }

  public function testEnable() {
    \QueryPath::enable('\QueryPath\Tests\DummyExtension');

    $qp = \QueryPath::with(\QueryPath::XHTML_STUB);

    $this->assertTrue($qp->grrrrrrr());

  }

}

class DummyExtension implements \QueryPath\Extension {

  public function __construct(\QueryPath\Query $qp) {
    $this->qp = $qp;
  }

  public function grrrrrrr() {
    return TRUE;
  }

}

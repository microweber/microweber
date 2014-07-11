<?php
/** @file
 * CSS Event handling tests
 */
namespace QueryPath\Tests;

require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\DOMTraverser\Util;

/**
 * @ingroup querypath_tests
 * @group CSS
 */
class UtilTest extends TestCase {
  public function testRemoveQuotes() {
    $this->assertEquals('foo', Util::removeQuotes('"foo"'));
    $this->assertEquals('foo', Util::removeQuotes("'foo'"));
    $this->assertEquals('"foo\'', Util::removeQuotes("\"foo'"));
    $this->assertEquals('f"o"o', Util::removeQuotes('f"o"o'));

  }
  public function testParseAnB() {
    // even
    $this->assertEquals(array(2, 0), Util::parseAnB('even'));
    // odd
    $this->assertEquals(array(2, 1), Util::parseAnB('odd'));
    // 5
    $this->assertEquals(array(0, 5), Util::parseAnB('5'));
    // +5
    $this->assertEquals(array(0, 5), Util::parseAnB('+5'));
    // n
    $this->assertEquals(array(1, 0), Util::parseAnB('n'));
    // 2n
    $this->assertEquals(array(2, 0), Util::parseAnB('2n'));
    // -234n
    $this->assertEquals(array(-234, 0), Util::parseAnB('-234n'));
    // -2n+1
    $this->assertEquals(array(-2, 1), Util::parseAnB('-2n+1'));
    // -2n + 1
    $this->assertEquals(array(-2, 1), Util::parseAnB(' -2n + 1   '));
    // +2n-1
    $this->assertEquals(array(2, -1), Util::parseAnB('2n-1'));
    $this->assertEquals(array(2, -1), Util::parseAnB('2n   -   1'));
    // -n + 3
    $this->assertEquals(array(-1, 3), Util::parseAnB('-n+3'));

    // Test invalid values
    $this->assertEquals(array(0, 0), Util::parseAnB('obviously + invalid'));
  }
}

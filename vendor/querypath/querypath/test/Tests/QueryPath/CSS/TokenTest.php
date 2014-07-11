<?php
/** @file
 * CSS Event handling tests
 */
namespace QueryPath\Tests;

require_once __DIR__ . '/../TestCase.php';

use \QueryPath\CSS\Token;

/**
 * @ingroup querypath_tests
 * @group CSS
 */
class TokenTest extends TestCase {
  public function testName() {

    $this->assertEquals('character', (Token::name(0)));
    $this->assertEquals('a legal non-alphanumeric character', (Token::name(99)));
    $this->assertEquals('end of file', (Token::name(FALSE)));
    $this->assertEquals(0, strpos(Token::name(22),'illegal character'));
  }
}

<?php
/**
 * @file
 *
 * The master test case.
 */

namespace QueryPath\Tests;

require_once 'PHPUnit/Autoload.php';
require_once __DIR__ . '/../../../src/qp.php';

class TestCase extends \PHPUnit_Framework_TestCase {
  const DATA_FILE =  'test/data.xml';
  public static function setUpBeforeClass(){
  }

  public function testFoo() {
    // Placeholder. Why is PHPUnit emitting warnings about no tests?
  }
}

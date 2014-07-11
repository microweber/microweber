<?php
/**
 * Tests for the QueryPath library.
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
namespace QueryPath\Tests;
require_once 'PHPUnit/Autoload.php';
require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/../../../src/QueryPath/Extension.php';
//require_once __DIR__ . '/../../../src/QueryPath.php';
//require_once 'QueryPathTest.php';

use \QueryPath\Extension;
use \QueryPath\ExtensionRegistry;

/**
 * 
 */
//define('self::DATA_FILE', 'test/data.xml');

/**
 * Run all of the usual tests, plus some extras, with some extensions loaded.
 * @ingroup querypath_tests
 * @group extension
 */
class QueryPathExtensionTest extends TestCase {

  public static function setUpBeforeClass() {
    ExtensionRegistry::extend('\QueryPath\Tests\StubExtensionOne');
    ExtensionRegistry::extend('\QueryPath\Tests\StubExtensionTwo');
  }

  public function testExtensions() {
   $this->assertNotNull(qp());
  }

  public function testHasExtension() {
   $this->assertTrue(ExtensionRegistry::hasExtension('\QueryPath\Tests\StubExtensionOne'));
  }

  public function testStubToe() {
   $this->assertEquals(1, qp(self::DATA_FILE, 'unary')->stubToe()->top(':root > toe')->size());
  }

  public function testStuble() {
   $this->assertEquals('arg1arg2', qp(self::DATA_FILE)->stuble('arg1', 'arg2'));
  }

 /**
  * @expectedException \QueryPath\Exception
  */
 public function testNoRegistry() {
   ExtensionRegistry::$useRegistry = FALSE;
   try {
    qp(self::DATA_FILE)->stuble('arg1', 'arg2'); 
   }
   catch (\QueryPath\Exception $e) {
     ExtensionRegistry::$useRegistry = TRUE;
     throw $e;
   }

 }

 public function testExtend() {
   $this->assertFalse(ExtensionRegistry::hasExtension('\QueryPath\Tests\StubExtensionThree'));
   ExtensionRegistry::extend('\QueryPath\Tests\StubExtensionThree');
   $this->assertTrue(ExtensionRegistry::hasExtension('\QueryPath\Tests\StubExtensionThree'));
 }

 public function tearDown() {
   ExtensionRegistry::$useRegistry = TRUE;
 }

 /**
  * @expectedException \QueryPath\Exception
  */
 public function testAutoloadExtensions() {
   // FIXME: This isn't really much of a test.
   ExtensionRegistry::autoloadExtensions(FALSE);
   try {
    qp()->stubToe();
   }
   catch (Exception $e) {
     ExtensionRegistry::autoloadExtensions(TRUE);
     throw $e;
   }
 }
 
 /**
  * @expectedException \QueryPath\Exception
  */
 public function testCallFailure() {
   qp()->foo();
 }
 
 // This does not (and will not) throw an exception.
 // /**
 //   * @expectedException QueryPathException
 //   */
 //  public function testExtendNoSuchClass() {
 //    ExtensionRegistry::extend('StubExtensionFour');
 //  }
 
}
// Create a stub extension:
/**
 * Create a stub extension
 *
 * @ingroup querypath_tests
 */
class StubExtensionOne implements Extension {
  private $qp = NULL;
  public function __construct(\QueryPath\Query $qp) {
    $this->qp = $qp;
  }

  public function stubToe() {
    $this->qp->top()->append('<toe/>')->end();
    return $this->qp;
  }
}
/**
 * Create a stub extension
 *
 * @ingroup querypath_tests
 */
class StubExtensionTwo implements Extension {
  private $qp = NULL;
  public function __construct(\QueryPath\Query $qp) {
    $this->qp = $qp;
  }
  public function stuble($arg1, $arg2) {
    return $arg1 . $arg2;
  }
}
/**
 * Create a stub extension
 *
 * @ingroup querypath_tests
 */
class StubExtensionThree implements Extension {
  private $qp = NULL;
  public function __construct(\QueryPath\Query $qp) {
    $this->qp = $qp;
  }
  public function stuble($arg1, $arg2) {
    return $arg1 . $arg2;
  }
}

//ExtensionRegistry::extend('StubExtensionOne');
//ExtensionRegistry::extend('StubExtensionTwo');

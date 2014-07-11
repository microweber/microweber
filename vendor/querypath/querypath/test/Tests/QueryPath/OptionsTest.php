<?php
/**
 * Tests for the QueryPath library.
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
namespace QueryPath\Tests;
require_once __DIR__ . '/TestCase.php';
use \QueryPath\Options;

/**
 * @ingroup querypath_tests
 */
class OptionsTest extends TestCase {

  public function testOptions() {
    $expect = array('test1' => 'val1', 'test2' => 'val2');
    $options = array('test1' => 'val1', 'test2' => 'val2');

    Options::set($options);

    $results = Options::get();
    $this->assertEquals($expect, $results);

    $this->assertEquals('val1', $results['test1']);
  }

  public function testQPOverrideOrder() {
    $expect = array('test1' => 'val3', 'test2' => 'val2');
    $options = array('test1' => 'val1', 'test2' => 'val2');

    Options::set($options);
    $qpOpts = qp(NULL, NULL, array('test1'=>'val3', 'replace_entities' => TRUE))->getOptions();

    $this->assertEquals($expect['test1'], $qpOpts['test1']);
    $this->assertEquals(TRUE, $qpOpts['replace_entities']);
    $this->assertNull($qpOpts['parser_flags']);
    $this->assertEquals($expect['test2'], $qpOpts['test2']);
  }

  public function testQPHas() {
    $options = array('test1' => 'val1', 'test2' => 'val2');

    Options::set($options);
    $this->assertTrue(Options::has('test1'));
    $this->assertFalse(Options::has('test3'));
  }
  public function testQPMerge() {
    $options = array('test1' => 'val1', 'test2' => 'val2');
    $options2 = array('test1' => 'val3', 'test4' => 'val4');

    Options::set($options);
    Options::merge($options2);

    $results = Options::get();
    $this->assertTrue(Options::has('test4'));
    $this->assertEquals('val3', $results['test1']);
  }

}

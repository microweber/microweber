<?php
/** @file
 * Tests for the QueryPath library.
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
namespace QueryPath\Tests;
require_once __DIR__ . '/TestCase.php';

/**
 * @ingroup querypath_tests
 */
class EntitiesTest extends TestCase {
  public function testReplaceEntity() {
    $entity = 'amp';
    $this->assertEquals('38', \QueryPath\Entities::replaceEntity($entity));

    $entity = 'lceil';
    $this->assertEquals('8968', \QueryPath\Entities::replaceEntity($entity));
  }

  public function testReplaceAllEntities() {
    $test = '<?xml version="1.0"?><root>&amp;&copy;&#38;& nothing.</root>';
    $expect = '<?xml version="1.0"?><root>&#38;&#169;&#38;&#38; nothing.</root>';
    $this->assertEquals($expect, \QueryPath\Entities::replaceAllEntities($test));

    $test = '&&& ';
    $expect = '&#38;&#38;&#38; ';
    $this->assertEquals($expect, \QueryPath\Entities::replaceAllEntities($test));

    $test = "&eacute;\n";
    $expect = "&#233;\n";
    $this->assertEquals($expect, \QueryPath\Entities::replaceAllEntities($test));
  }

  public function testReplaceHexEntities() {
    $test = '&#xA9;';
    $expect = '&#xA9;';
    $this->assertEquals($expect, \QueryPath\Entities::replaceAllEntities($test));
  }

  public function testQPEntityReplacement() {
    $test = '<?xml version="1.0"?><root>&amp;&copy;&#38;& nothing.</root>';
    /*$expect = '<?xml version="1.0"?><root>&#38;&#169;&#38;&#38; nothing.</root>';*/
    // We get this because the DOM serializer re-converts entities.
    $expect = '<?xml version="1.0"?>
<root>&amp;&#xA9;&amp;&amp; nothing.</root>';

    $qp = qp($test, NULL, array('replace_entities' => TRUE));
    // Interestingly, the XML serializer converts decimal to hex and ampersands
    // to &amp;.
    $this->assertEquals($expect, trim($qp->xml()));
  }
}

<?php
/**
 * Tests for the QueryPath library.
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

namespace QueryPath\Tests;

require_once 'PHPUnit/Autoload.php';
require_once 'src/QueryPath/Extension/QPXSL.php';
require_once __DIR__ . '/../TestCase.php';
/**
 * @ingroup querypath_tests
 * @extension
 */
class QPXSLTests extends TestCase {

  protected $file = './test/advanced.xml';

  public static function setUpBeforeClass() {
    \QueryPath::enable('\QueryPath\Extension\QPXSL');
  }
  public function testXSLT() {
    // XML and XSLT taken from http://us.php.net/manual/en/xsl.examples-collection.php
    // and then modified to be *actually welformed* XML.
    $orig = '<?xml version="1.0"?><collection>
     <cd>
      <title>Fight for your mind</title>
      <artist>Ben Harper</artist>
      <year>1995</year>
     </cd>
     <cd>
      <title>Electric Ladyland</title>
      <artist>Jimi Hendrix</artist>
      <year>1997</year>
     </cd>
    </collection>';

    $template = '<?xml version="1.0"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
     <xsl:param name="owner" select="\'Nicolas Eliaszewicz\'"/>
     <xsl:output method="html" encoding="iso-8859-1" indent="no"/>
     <xsl:template match="collection">
      <div>
      Hey! Welcome to <xsl:value-of select="$owner"/>\'s sweet CD collection!
      <xsl:apply-templates/>
      </div>
     </xsl:template>
     <xsl:template match="cd">
      <h1><xsl:value-of select="title"/></h1>
      <h2>by <xsl:value-of select="artist"/> - <xsl:value-of select="year"/></h2>
      <hr />
     </xsl:template>
    </xsl:stylesheet>
    ';

    $qp = qp($orig)->xslt($template);
    $this->assertEquals(2, $qp->top('h1')->size(), 'Make sure that data was formatted');
  }
}

<?php
/**
 * @file
 * Test the Tree Builder's special-case rules.
 */
namespace Masterminds\HTML5\Tests\Parser;

use Masterminds\HTML5\Parser\TreeBuildingRules;
use Masterminds\HTML5\Parser\Tokenizer;
use Masterminds\HTML5\Parser\Scanner;
use Masterminds\HTML5\Parser\StringInputStream;
use Masterminds\HTML5\Parser\DOMTreeBuilder;

/**
 * These tests are functional, not necessarily unit tests.
 */
class TreeBuildingRulesTest extends \Masterminds\HTML5\Tests\TestCase
{

    const HTML_STUB = '<!DOCTYPE html><html><head><title>test</title></head><body>%s</body></html>';

    /**
     * Convenience function for parsing.
     */
    protected function parse($string)
    {
        $treeBuilder = new DOMTreeBuilder();
        $input = new StringInputStream($string);
        $scanner = new Scanner($input);
        $parser = new Tokenizer($scanner, $treeBuilder);

        $parser->parse();

        return $treeBuilder->document();
    }

    public function testHasRules()
    {
        $doc = new \DOMDocument('1.0');
        $engine = new TreeBuildingRules($doc);

        $this->assertTrue($engine->hasRules('li'));
        $this->assertFalse($engine->hasRules('imaginary'));
    }

    public function testHandleLI()
    {
        $html = sprintf(self::HTML_STUB, '<ul id="a"><li>test<li>test2</ul><a></a>');
        $doc = $this->parse($html);

        $list = $doc->getElementById('a');

        $this->assertEquals(2, $list->childNodes->length);
        foreach ($list->childNodes as $ele) {
            $this->assertEquals('li', $ele->tagName);
        }
    }

    public function testHandleDT()
    {
        $html = sprintf(self::HTML_STUB, '<dl id="a"><dt>Hello<dd>Hi</dl><a></a>');
        $doc = $this->parse($html);

        $list = $doc->getElementById('a');

        $this->assertEquals(2, $list->childNodes->length);
        $this->assertEquals('dt', $list->firstChild->tagName);
        $this->assertEquals('dd', $list->lastChild->tagName);
    }

    public function testTable()
    {
        $html = sprintf(self::HTML_STUB, '<table><thead id="a"><th>foo<td>bar<td>baz');
        $doc = $this->parse($html);

        $list = $doc->getElementById('a');

        $this->assertEquals(3, $list->childNodes->length);
        $this->assertEquals('th', $list->firstChild->tagName);
        $this->assertEquals('td', $list->lastChild->tagName);
    }
}

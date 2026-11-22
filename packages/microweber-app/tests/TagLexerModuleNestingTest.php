<?php

declare(strict_types=1);

namespace MicroweberPackages\App\StandaloneTests;

use MicroweberPackages\App\Utils\ParserHelpers\TagLexer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * The module parser left dangling </module> tags when a NON-self-closing
 * <module ...>...</module> pair reached the lexer — e.g. saved edit-field
 * content injected AFTER the ></module>->/> normalization had already run. The
 * lexer must capture the WHOLE <module>...</module> element (depth-aware for
 * nested "modules in modules") so the matching </module> is replaced along with
 * the opening, never left behind.
 */
final class TagLexerModuleNestingTest extends TestCase
{
    private TagLexer $lexer;

    protected function setUp(): void
    {
        $this->lexer = new TagLexer();
    }

    #[Test]
    public function self_closing_tag_is_captured_whole(): void
    {
        $tags = $this->lexer->findModuleTags('<module type="btn" />');
        $this->assertCount(1, $tags);
        $this->assertSame('<module type="btn" />', $tags[0]['tag']);
        $this->assertTrue($tags[0]['self_closing']);
    }

    #[Test]
    public function adjacent_empty_pair_captures_the_closing_tag(): void
    {
        $tags = $this->lexer->findModuleTags('<module type="btn" id="a"></module>');
        $this->assertCount(1, $tags);
        $this->assertSame('<module type="btn" id="a"></module>', $tags[0]['tag'],
            'The </module> must be part of the matched tag, not left dangling.');
    }

    #[Test]
    public function non_adjacent_pair_spans_its_inner_content_and_close(): void
    {
        $tags = $this->lexer->findModuleTags('<module type="btn" id="a">INNER</module>');
        $this->assertCount(1, $tags);
        $this->assertSame('<module type="btn" id="a">INNER</module>', $tags[0]['tag']);
    }

    #[Test]
    public function nested_modules_match_at_the_right_depth(): void
    {
        $html = '<module type="layouts" id="o"><module type="btn" id="i"></module></module>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags, 'The outer module owns the whole span (one top-level element).');
        $this->assertSame($html, $tags[0]['tag'], 'The OUTER </module> must close the outer module, not the inner one.');
    }

    #[Test]
    public function two_siblings_are_separate_tags(): void
    {
        $html = '<module type="btn" id="a"></module><module type="btn" id="b"></module>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(2, $tags);
        $this->assertSame('<module type="btn" id="a"></module>', $tags[0]['tag']);
        $this->assertSame('<module type="btn" id="b"></module>', $tags[1]['tag']);
    }

    #[Test]
    public function gt_inside_a_quoted_attribute_does_not_end_the_tag(): void
    {
        $tags = $this->lexer->findModuleTags('<module type="btn" title="a > b" id="a"></module>');
        $this->assertCount(1, $tags);
        $this->assertSame('<module type="btn" title="a > b" id="a"></module>', $tags[0]['tag']);
    }

    #[Test]
    public function an_opening_without_a_close_falls_back_to_the_opening_tag(): void
    {
        $tags = $this->lexer->findModuleTags('prefix <module type="btn" id="a"> trailing');
        $this->assertCount(1, $tags);
        $this->assertSame('<module type="btn" id="a">', $tags[0]['tag']);
    }
}

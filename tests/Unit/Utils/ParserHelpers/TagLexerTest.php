<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\TagLexer;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the TagLexer — the quote-aware tokenizer that replaces brittle regexes.
 *
 * Also includes regression tests documenting bugs in the OLD regex approach,
 * verified to now pass with TagLexer.
 */
class TagLexerTest extends TestCase
{
    private TagLexer $lexer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lexer = new TagLexer();
    }

    /**
     * Old regex approach — kept for regression comparison.
     */
    private function findModuleTagsOldRegex(string $html): array
    {
        preg_match_all('/<module[^>]*>/Uis', $html, $matches);
        return $matches[0] ?? [];
    }

    // ── Basic tag finding ──────────────────────────────────────

    public function test_find_single_self_closing_module(): void
    {
        $html = '<div><module type="layouts" template="skin-1"/></div>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertStringContainsString('type="layouts"', $tags[0]['tag']);
        $this->assertTrue($tags[0]['self_closing']);
    }

    public function test_find_multiple_module_tags(): void
    {
        $html = '<module type="btn"/><p>text</p><module type="layouts"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(2, $tags);
    }

    public function test_find_module_with_nested_markup(): void
    {
        $html = '<div class="container"><module type="layouts"/><span>text</span></div>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
    }

    public function test_no_modules_in_plain_html(): void
    {
        $html = '<div><p>No modules here</p></div>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(0, $tags);
    }

    public function test_empty_input(): void
    {
        $this->assertCount(0, $this->lexer->findModuleTags(''));
    }

    public function test_has_module_tags_true(): void
    {
        $this->assertTrue($this->lexer->hasModuleTags('<module type="btn"/>'));
    }

    public function test_has_module_tags_false(): void
    {
        $this->assertFalse($this->lexer->hasModuleTags('<div>no modules</div>'));
    }

    // ── FIXED BUG: > inside quoted attribute value ──

    public function test_greater_than_in_quoted_value_does_not_truncate(): void
    {
        $html = '<module type="layouts" title="a > b" template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);

        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag'],
            'TagLexer correctly handles > inside quoted attribute values');

        // Confirm old regex would have broken
        $oldTags = $this->findModuleTagsOldRegex($html);
        $this->assertStringNotContainsString('template=', $oldTags[0],
            'Old regex confirmed to truncate at > inside quotes');
    }

    public function test_less_than_in_quoted_value(): void
    {
        $html = '<module type="layouts" data-tpl="a<b" template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);

        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag']);
    }

    // ── FIXED BUG: Embedded escaped quotes ──

    public function test_embedded_escaped_quotes(): void
    {
        $html = '<module type="layouts" title="say \\"hi\\"" template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);

        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag'],
            'TagLexer handles escaped quotes inside attribute values');
    }

    // ── Tags spanning multiple lines / odd whitespace ──

    public function test_tag_spanning_multiple_lines(): void
    {
        $html = "<module\n  type=\"layouts\"\n  template=\"skin-1\"\n/>";
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
    }

    public function test_tag_with_tabs_and_odd_whitespace(): void
    {
        $html = "<module\t\ttype=\"layouts\" \t template=\"skin-1\" />";
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
    }

    // ── FIXED BUG: Greedy regex over-matching ──

    public function test_multiple_tags_found_separately(): void
    {
        $html = '<module type="btn"/><p>text</p><module type="layouts"/>';
        $tags = $this->lexer->findModuleTags($html);

        $this->assertCount(2, $tags, 'TagLexer finds each tag separately');
        $this->assertStringContainsString('type="btn"', $tags[0]['tag']);
        $this->assertStringContainsString('type="layouts"', $tags[1]['tag']);
    }

    // ── Malformed / edge cases ──

    public function test_malformed_unclosed_module_tag(): void
    {
        $html = '<module type="btn"';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(0, $tags, 'Unclosed tag gracefully ignored');
    }

    public function test_module_text_outside_tag(): void
    {
        $html = '<p>The word module appears in text</p>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(0, $tags);
    }

    public function test_modulefoo_not_matched(): void
    {
        // <moduleFoo should not be matched — only <module followed by space/>/
        $html = '<moduleFoo type="btn"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(0, $tags, 'Tags like <moduleFoo are not matched');
    }

    public function test_non_self_closing_module(): void
    {
        $html = '<module type="btn">content</module>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertFalse($tags[0]['self_closing']);
    }

    public function test_self_closing_detected(): void
    {
        $html = '<module type="btn" />';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertTrue($tags[0]['self_closing']);
    }

    public function test_offset_is_correct(): void
    {
        $html = '12345<module type="btn"/>67890';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertSame(5, $tags[0]['offset']);
    }

    public function test_extract_tag_strings(): void
    {
        $html = '<module type="a"/><div></div><module type="b"/>';
        $strs = $this->lexer->extractTagStrings($html);
        $this->assertCount(2, $strs);
        $this->assertStringContainsString('type="a"', $strs[0]);
        $this->assertStringContainsString('type="b"', $strs[1]);
    }

    public function test_single_quoted_values_with_gt(): void
    {
        $html = "<module type='layouts' title='a > b' template='skin-1'/>";
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertStringContainsString("template='skin-1'", $tags[0]['tag']);
    }

    public function test_mixed_quotes_with_gt(): void
    {
        $html = '<module type="layouts" title=\'a > b\' template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag']);
    }

    public function test_case_insensitive_matching(): void
    {
        $html = '<MODULE type="btn"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
    }

    public function test_nested_lt_inside_quoted_value_stops_tag(): void
    {
        // < outside quotes means a new tag starts — the current tag is malformed
        $html = '<module type="btn" <span>extra</span>';
        $tags = $this->lexer->findModuleTags($html);
        // The < before span breaks the module tag
        $this->assertCount(0, $tags);
    }

    // ── More edge cases ──

    public function test_self_closing_slash_inside_quoted_value(): void
    {
        // A literal "/>" inside a quoted value must NOT end the tag early.
        $html = '<module type="btn" data-x="a/>b" template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag']);
    }

    public function test_consecutive_modules_no_separator(): void
    {
        $html = '<module type="a"/><module type="b"/><module type="c"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(3, $tags);
        $this->assertStringContainsString('type="a"', $tags[0]['tag']);
        $this->assertStringContainsString('type="c"', $tags[2]['tag']);
    }

    public function test_module_tag_at_very_start_and_end(): void
    {
        $html = '<module type="only"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertSame(0, $tags[0]['offset']);
    }

    public function test_module_with_no_space_before_self_close(): void
    {
        $html = '<module type="btn"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertTrue($tags[0]['self_closing']);
    }

    public function test_word_module_in_attribute_value_not_matched(): void
    {
        // "module" appearing inside another tag's attribute is not a module tag.
        $html = '<div data-note="see the module docs">x</div>';
        $this->assertCount(0, $this->lexer->findModuleTags($html));
    }

    public function test_quotes_inside_opposite_quotes_preserved(): void
    {
        $html = '<module type="btn" title="it\'s here" template="skin-1"/>';
        $tags = $this->lexer->findModuleTags($html);
        $this->assertCount(1, $tags);
        $this->assertStringContainsString('template="skin-1"', $tags[0]['tag']);
    }

    public function test_extract_tag_strings_empty_when_none(): void
    {
        $this->assertSame([], $this->lexer->extractTagStrings('<p>plain</p>'));
    }
}

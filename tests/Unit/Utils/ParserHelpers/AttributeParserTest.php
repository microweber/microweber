<?php

namespace Tests\Unit\Utils\ParserHelpers;

use PHPUnit\Framework\TestCase;
use MicroweberPackages\App\Utils\ParserUtils;
use MicroweberPackages\App\Utils\ParserHelpers\AttributeParser;

/**
 * Tests for attribute parsing.
 *
 * Section 1: Tests against the OLD ParserUtils::parseAttributes (documenting bugs)
 * Section 2: Tests against the NEW AttributeParser (showing fixes)
 */
class AttributeParserTest extends TestCase
{
    private ParserUtils $oldParser;
    private AttributeParser $newParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->oldParser = new ParserUtils();
        $this->newParser = new AttributeParser();
    }

    // ════════════════════════════════════════════════════════════
    // Section 1: OLD parser — documenting existing bugs
    // ════════════════════════════════════════════════════════════

    public function test_old_double_quoted_attribute(): void
    {
        $attrs = $this->oldParser->parseAttributes('<module type="layouts" template="skin-1"/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_old_single_quoted_attribute(): void
    {
        $attrs = $this->oldParser->parseAttributes("<module type='layouts' template='skin-1'/>");
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_old_parser_unquoted_value_now_fixed(): void
    {
        $attrs = $this->oldParser->parseAttributes('<module type=layouts />');
        $this->assertSame('layouts', $attrs['type'],
            'FIXED: unquoted value no longer has trailing space');
    }

    public function test_old_parser_digit_in_attr_name_now_fixed(): void
    {
        $attrs = $this->oldParser->parseAttributes('<module type="layouts" data-col-2="value"/>');
        $this->assertArrayHasKey('data-col-2', $attrs,
            'FIXED: data-col-2 is now correctly parsed');
        $this->assertSame('value', $attrs['data-col-2']);
    }

    public function test_old_parser_unquoted_value_before_closing_now_fixed(): void
    {
        $attrs = $this->oldParser->parseAttributes('<module type=layouts/>');
        $this->assertSame('layouts', $attrs['type'],
            'FIXED: unquoted value before /> no longer captures /');
    }

    // ════════════════════════════════════════════════════════════
    // Section 2: NEW AttributeParser — all bugs fixed
    // ════════════════════════════════════════════════════════════

    public function test_new_double_quoted_attribute(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" template="skin-1"/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_single_quoted_attribute(): void
    {
        $attrs = $this->newParser->parse("<module type='layouts' template='skin-1'/>");
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_mixed_quote_attributes(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" template=\'skin-1\'/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_unquoted_value_trimmed_correctly(): void
    {
        $attrs = $this->newParser->parse('<module type=layouts />');
        $this->assertSame('layouts', $attrs['type'],
            'FIX: unquoted value no longer has trailing space');
    }

    public function test_new_unquoted_value_before_self_closing(): void
    {
        $attrs = $this->newParser->parse('<module type=layouts/>');
        $this->assertSame('layouts', $attrs['type'],
            'FIX: unquoted value before /> no longer captures /');
    }

    public function test_new_empty_quoted_value(): void
    {
        $attrs = $this->newParser->parse('<module type="" />');
        $this->assertSame('', $attrs['type']);
    }

    public function test_new_attribute_with_hyphens(): void
    {
        $attrs = $this->newParser->parse('<module data-type="layouts" parent-module="btn"/>');
        $this->assertSame('layouts', $attrs['data-type']);
        $this->assertSame('btn', $attrs['parent-module']);
    }

    public function test_new_digit_in_attr_name_preserved(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" data-col-2="value"/>');
        $this->assertArrayHasKey('data-col-2', $attrs,
            'FIX: data-col-2 is now correctly parsed');
        $this->assertSame('value', $attrs['data-col-2']);
    }

    public function test_new_embedded_escaped_quotes(): void
    {
        $tag = '<module type="layouts" title="say \\"hi\\"" template="skin-1"/>';
        $attrs = $this->newParser->parse($tag);
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('say "hi"', $attrs['title'],
            'FIX: escaped quotes are unescaped in the value');
        $this->assertSame('skin-1', $attrs['template'],
            'FIX: escaped quotes do not corrupt following attributes');
    }

    public function test_new_space_around_equals(): void
    {
        $attrs = $this->newParser->parse('<module type = "layouts" />');
        $this->assertSame('layouts', $attrs['type'],
            'FIX: space around = is handled correctly');
    }

    public function test_new_weird_whitespace(): void
    {
        $attrs = $this->newParser->parse('<module   type="layouts"     template="skin-1"  />');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_duplicate_attributes_first_wins(): void
    {
        $attrs = $this->newParser->parse('<module type="first" type="second"/>');
        $this->assertSame('first', $attrs['type'],
            'HTML spec: first attribute wins for duplicates');
    }

    public function test_new_value_with_less_than(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" data-tpl="a<b"/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('a<b', $attrs['data-tpl']);
    }

    public function test_new_value_with_greater_than(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" title="a > b" template="skin-1"/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('a > b', $attrs['title']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_value_with_equals(): void
    {
        $attrs = $this->newParser->parse('<module type="layouts" data-expr="a=b"/>');
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('a=b', $attrs['data-expr']);
    }

    public function test_new_multiline_attributes(): void
    {
        $tag = "<module\n  type=\"layouts\"\n  template=\"skin-1\"\n/>";
        $attrs = $this->newParser->parse($tag);
        $this->assertSame('layouts', $attrs['type']);
        $this->assertSame('skin-1', $attrs['template']);
    }

    public function test_new_get_module_type(): void
    {
        $attrs = $this->newParser->parse('<module type="btn"/>');
        $this->assertSame('btn', $this->newParser->getModuleType($attrs));

        $attrs = $this->newParser->parse('<module data-type="layouts"/>');
        $this->assertSame('layouts', $this->newParser->getModuleType($attrs));
    }

    public function test_new_get_edit_field_attributes(): void
    {
        $attrs = $this->newParser->parse('<div class="edit" rel="content" field="content" rel-id="5">');
        $ef = $this->newParser->getEditFieldAttributes($attrs);
        $this->assertSame('content', $ef['field']);
        $this->assertSame('content', $ef['rel']);
        $this->assertSame('5', $ef['rel_id']);
    }

    public function test_new_no_attributes(): void
    {
        $attrs = $this->newParser->parse('<module/>');
        $this->assertEmpty($attrs);
    }

    public function test_new_boolean_attribute(): void
    {
        // Boolean attrs like "disabled" have no value
        // In the parser context this is rare but should not crash
        $attrs = $this->newParser->parse('<module type="btn" disabled />');
        $this->assertSame('btn', $attrs['type']);
    }

    public function test_new_underscore_in_name(): void
    {
        $attrs = $this->newParser->parse('<module data_type="btn"/>');
        $this->assertArrayHasKey('data_type', $attrs);
        $this->assertSame('btn', $attrs['data_type']);
    }

    // ════════════════════════════════════════════════════════════
    // Unquoted slash values (FIX): keep internal '/', drop the
    // tag's self-closing '/'. Covers shop/products and friends.
    // ════════════════════════════════════════════════════════════

    public function test_new_unquoted_slash_value_preserved_with_space(): void
    {
        $attrs = $this->newParser->parse('<module type=shop/products />');
        $this->assertSame('shop/products', $attrs['type'],
            'FIX: unquoted slash value keeps its internal slash');
    }

    public function test_new_unquoted_slash_value_preserved_before_self_close(): void
    {
        $attrs = $this->newParser->parse('<module type=shop/products/>');
        $this->assertSame('shop/products', $attrs['type'],
            'FIX: internal slash kept, only the self-closing slash dropped');
    }

    public function test_new_unquoted_slash_value_before_gt(): void
    {
        $attrs = $this->newParser->parse('<module type=shop/products>');
        $this->assertSame('shop/products', $attrs['type']);
    }

    public function test_new_unquoted_slash_value_at_eof(): void
    {
        // No closing > at all (truncated) — internal slash still kept.
        $attrs = $this->newParser->parse('<module type=shop/products');
        $this->assertSame('shop/products', $attrs['type']);
    }

    public function test_new_unquoted_multi_segment_slash_value(): void
    {
        $attrs = $this->newParser->parse('<module type=shop/products template=titles/skin-1 />');
        $this->assertSame('shop/products', $attrs['type']);
        $this->assertSame('titles/skin-1', $attrs['template'],
            'FIX: every unquoted slash value on the tag is preserved');
    }

    public function test_new_unquoted_self_closing_slash_dropped(): void
    {
        $attrs = $this->newParser->parse('<module type=layouts/>');
        $this->assertSame('layouts', $attrs['type'],
            'self-closing slash is not part of the value');
    }

    public function test_new_unquoted_bare_trailing_slash_dropped(): void
    {
        // type=layouts/  (slash then end-of-string) — treated as self-close.
        $attrs = $this->newParser->parse('<module type=layouts/');
        $this->assertSame('layouts', $attrs['type']);
    }

    public function test_new_quoted_slash_value_unaffected(): void
    {
        $attrs = $this->newParser->parse('<module type="shop/products" template="titles/skin-1"/>');
        $this->assertSame('shop/products', $attrs['type']);
        $this->assertSame('titles/skin-1', $attrs['template']);
    }

    // ── More edge cases ──

    public function test_new_empty_input_returns_empty_array(): void
    {
        $this->assertSame([], $this->newParser->parse(''));
        $this->assertSame([], $this->newParser->parse('   '));
    }

    public function test_new_tabs_and_newlines_between_attributes(): void
    {
        $attrs = $this->newParser->parse("<module\ttype=\"btn\"\n\tdata-x=\"1\"\r\n/>");
        $this->assertSame('btn', $attrs['type']);
        $this->assertSame('1', $attrs['data-x']);
    }

    public function test_new_single_quotes_with_double_inside(): void
    {
        $attrs = $this->newParser->parse('<module title=\'say "hi"\' type="btn"/>');
        $this->assertSame('say "hi"', $attrs['title']);
        $this->assertSame('btn', $attrs['type']);
    }

    public function test_new_value_with_colon_and_at(): void
    {
        $attrs = $this->newParser->parse('<module data-bind="x:y" data-mail="a@b.com"/>');
        $this->assertSame('x:y', $attrs['data-bind']);
        $this->assertSame('a@b.com', $attrs['data-mail']);
    }

    public function test_new_trailing_whitespace_before_self_close(): void
    {
        $attrs = $this->newParser->parse('<module type="btn"    />');
        $this->assertSame('btn', $attrs['type']);
        $this->assertArrayNotHasKey('/', $attrs);
    }

    public function test_new_multiple_digit_names(): void
    {
        $attrs = $this->newParser->parse('<module data-col-2="a" data-col-12="b" col3="c"/>');
        $this->assertSame('a', $attrs['data-col-2']);
        $this->assertSame('b', $attrs['data-col-12']);
        $this->assertSame('c', $attrs['col3']);
    }

    public function test_new_get_edit_field_attributes_data_variants(): void
    {
        $attrs = $this->newParser->parse('<div class="edit" data-rel="global" data-field="header" data-id="9">');
        $ef = $this->newParser->getEditFieldAttributes($attrs);
        $this->assertSame('header', $ef['field']);
        $this->assertSame('global', $ef['rel']);
        $this->assertSame('9', $ef['rel_id']);
    }

    public function test_new_get_module_type_priority_and_absence(): void
    {
        $this->assertNull($this->newParser->getModuleType($this->newParser->parse('<module template="x"/>')));
        // 'type' wins over 'data-type' when both present
        $attrs = $this->newParser->parse('<module data-type="b" type="a"/>');
        $this->assertSame('a', $this->newParser->getModuleType($attrs));
    }
}

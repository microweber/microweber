<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\LayoutProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Tests for LayoutProcessor — the top-level orchestrator.
 */
class LayoutProcessorTest extends TestCase
{
    private LayoutProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = new LayoutProcessor();
    }

    // ── Empty input ──

    public function test_empty_input_returns_empty(): void
    {
        $this->assertSame('', $this->processor->process(''));
    }

    // ── Input with no modules ──

    public function test_no_modules_passthrough(): void
    {
        $html = '<div><p>No modules here</p></div>';
        $result = $this->processor->process($html);
        $this->assertSame($html, $result);
    }

    // ── Basic module processing ──

    public function test_single_module_processed(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '<button>OK</button>');

        $this->assertStringContainsString('module-btn-3', $result);
        $this->assertStringContainsString('<button>OK</button>', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Module in script is not processed ──

    public function test_module_in_script_not_processed(): void
    {
        $html = '<script>var x = "<module type=\"btn\"/>";</script>';
        $result = $this->processor->process($html);

        // The module inside script should be preserved as-is
        // (the outer script quotes may be escaped differently but module tag must remain)
        $this->assertStringContainsString('<module', $result);
        $this->assertStringContainsString('</script>', $result);
    }

    // ── Module in HTML comment not processed ──

    public function test_module_in_html_comment_not_processed(): void
    {
        $html = '<!-- <module type="btn"/> -->';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('<!-- <module type="btn"/> -->', $result);
    }

    // ── Module in Blade comment not processed ──

    public function test_module_in_blade_comment_not_processed(): void
    {
        $html = '{{-- <module type="btn"/> --}}';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('{{-- <module type="btn"/> --}}', $result);
    }

    // ── Empty module produces nothing ──

    public function test_empty_module_produces_nothing(): void
    {
        $html = '<div><module/></div>';
        $result = $this->processor->process($html);

        $this->assertStringNotContainsString('mw-unprocessed-module-tag', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Global scope modules ──

    public function test_global_scope_no_content_id(): void
    {
        $html = '<div class="edit" rel="global" field="header">'
            . '<module type="layouts"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => 'content');

        $this->assertStringContainsString('module-layouts', $result);
        $this->assertStringNotContainsString('module-layouts-3', $result);
    }

    // ── Content scope modules ──

    public function test_content_scope_with_content_id(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="layouts"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => 'content');

        $this->assertStringContainsString('module-layouts-3', $result);
    }

    // ── Duplicate modules get unique IDs ──

    public function test_duplicate_modules_unique_ids(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'
            . '<module type="btn"/>'
            . '<module type="btn"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        $this->assertStringContainsString('module-btn-3', $result);
        $this->assertStringContainsString('module-btn-3--1', $result);
        $this->assertStringContainsString('module-btn-3--2', $result);
    }

    // ── Custom ID preserved ──

    public function test_custom_id_preserved(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn" id="my-custom-id"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        $this->assertStringContainsString('id="my-custom-id"', $result);
    }

    // ── Module with > in attribute value ──

    public function test_module_with_gt_in_attribute_value(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn" title="a > b"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        // The module should be processed, and the title should be preserved
        $this->assertStringContainsString('module-btn-3', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Module with digit-in-attribute name ──

    public function test_module_with_digit_attribute_name(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn" data-col-2="value"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        $this->assertStringContainsString('data-col-2="value"', $result);
    }

    // ── Tag aliases ──

    public function test_mw_tag_alias(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<mw type="btn"/>'
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        // <mw should be converted to <module
        $this->assertStringNotContainsString('<mw ', $result);
    }

    // ── Module in textarea not processed ──

    public function test_module_in_textarea_not_processed(): void
    {
        $html = '<textarea><module type="btn"/></textarea>';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('<module type="btn"/>', $result);
    }

    // ── Module in code not processed ──

    public function test_module_in_code_not_processed(): void
    {
        $html = '<code><module type="btn"/></code>';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('<module type="btn"/>', $result);
    }

    // ── Module in select not processed ──

    public function test_module_in_select_not_processed(): void
    {
        $html = '<select><option><module type="btn"/></option></select>';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('<module type="btn"/>', $result);
    }

    // ── Module in pre not processed ──

    public function test_module_in_pre_not_processed(): void
    {
        $html = '<pre><module type="btn"/></pre>';
        $result = $this->processor->process($html);

        $this->assertStringContainsString('<module type="btn"/>', $result);
    }

    // ── Full layout with several modules ──

    public function test_full_layout_multiple_modules(): void
    {
        $html = '<div class="edit" rel="global" field="header">'
            . '<module type="menu"/>'
            . '</div>'
            . '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'
            . '<module type="layouts" template="skin-1"/>'
            . '</div>'
            . '<div class="edit" rel="global" field="footer">'
            . '<module type="menu"/>'
            . '</div>';

        $result = $this->processor->process($html, 5, fn($type, $attrs) => "[$type]");

        // All modules should be processed
        $this->assertStringNotContainsString('<module', $result);
        // Content should appear
        $this->assertStringContainsString('[menu]', $result);
        $this->assertStringContainsString('[btn]', $result);
        $this->assertStringContainsString('[layouts]', $result);
    }

    // ── Cross-edit-field scope stays correct after offset shifts ──
    // Regression: scope must be derived from the lexer's original-layout
    // offset, not strpos() on the layout being mutated in-place. A long
    // first-module render shifts later positions; identical tag strings in
    // different scopes must still resolve to their own edit field.

    public function test_cross_edit_field_scope_survives_offset_shift(): void
    {
        $html = '<div class="edit" rel="content" field="content"><module type="btn"/></div>'
            . '<div class="edit" rel="global" field="footer"><module type="btn"/></div>';

        // First module renders a long string to shift later byte offsets.
        $result = $this->processor->process($html, 5, function ($type, $attrs) {
            return str_repeat('X', 500);
        });

        // Content-scoped btn keeps the content id; global-scoped btn does not.
        $this->assertStringContainsString('id="module-btn-5"', $result);
        $this->assertStringContainsString('id="module-btn"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Module after a CLOSED edit field is NOT scoped to that field ──
    // Regression: scope is range-based (open < pos < close), so a sibling
    // module placed after </div> falls back to global, not content scope.

    public function test_module_after_closed_edit_field_is_global(): void
    {
        $html = '<div class="edit" rel="content" field="content"><module type="btn"/></div>'
            . '<module type="btn"/>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        // First btn is content-scoped; the second (outside the field) is global.
        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringContainsString('id="module-btn"', $result);
        // The outside module must NOT inherit the content counter.
        $this->assertStringNotContainsString('id="module-btn-3--1"', $result);
    }

    public function test_nested_edit_field_module_uses_inner_scope(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'                       // outer → module-btn-3
            . '<div class="edit" rel="content" field="banner" rel-id="1">'
            . '<module type="btn"/>'                       // inner → module-btn-1
            . '</div>'
            . '<module type="btn"/>'                       // back to outer → module-btn-3--1
            . '</div>';

        $result = $this->processor->process($html, 3, fn($type, $attrs) => '');

        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringContainsString('id="module-btn-1"', $result);
        $this->assertStringContainsString('id="module-btn-3--1"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Edit-field content loading (DB bridge) ──

    public function test_edit_field_content_loaded_and_replaces_default(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="layouts" template="clean"/>'  // inline default
            . '</div>';

        $loader = function ($field, $rel, $relId, $cid) {
            return ($field === 'content' && $rel === 'content')
                ? '<h2>Saved</h2><module type="btn"/>'
                : null;
        };

        $result = $this->processor->process($html, 3, fn($t, $a) => '', $loader);

        // The default layouts module is replaced by the saved content.
        $this->assertStringContainsString('<h2>Saved</h2>', $result);
        $this->assertStringNotContainsString('module-layouts', $result);
        // The btn from the saved content renders, content-scoped to id 3.
        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_edit_field_loader_null_keeps_inline_default(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'
            . '</div>';

        // Loader returns null → inline default is kept and processed normally.
        $result = $this->processor->process($html, 3, fn($t, $a) => '', fn() => null);

        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_edit_field_nested_loaded_content_resolved(): void
    {
        // Outer field's saved content itself contains another .edit field,
        // which must also be resolved from the loader.
        $html = '<div class="edit" rel="content" field="content"><module type="x"/></div>';

        $loader = function ($field, $rel, $relId, $cid) {
            if ($field === 'content') {
                return 'OUTER<div class="edit" rel="global" field="footer"><module type="y"/></div>';
            }
            if ($field === 'footer') {
                return 'FOOTER<module type="btn"/>';
            }
            return null;
        };

        $result = $this->processor->process($html, 3, fn($t, $a) => '', $loader);

        $this->assertStringContainsString('OUTER', $result);
        $this->assertStringContainsString('FOOTER', $result);
        // btn came from the nested global field → global scope (no content id).
        $this->assertStringContainsString('id="module-btn"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_edit_field_loaded_content_comments_are_protected(): void
    {
        // A <module> inside a comment in the LOADED content must NOT be rendered
        // (loaded content arrives after the initial protect() pass).
        $html = '<div class="edit" rel="content" field="content"><module type="x"/></div>';

        $loader = fn($field) => $field === 'content'
            ? '<module type="btn"/><!-- <module type="btn"/> --><pre><module type="btn"/></pre>'
            : null;

        $result = $this->processor->process($html, 3, fn($t, $a) => '', $loader);

        // One real btn rendered; the comment + pre modules stay verbatim.
        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringContainsString('<!-- <module type="btn"/> -->', $result);
        $this->assertStringContainsString('<pre><module type="btn"/></pre>', $result);
        // Exactly the two protected raw module tags remain (comment + pre).
        $this->assertSame(2, substr_count($result, '<module'));
    }

    public function test_simulated_nested_edit_field_editing_with_inner_modules(): void
    {
        // Mirrors the live "edit the nested fields" simulation: a page with
        // nested .edit defaults, where the saved (edited) content of one field
        // itself contains a further .edit field that is also edited — and every
        // edited region carries inner modules that must render.
        $html = '<h2>Page</h2>'
            . '<div class="edit" rel="content" field="block_a">DEFAULT-A</div>'
            . '<div class="edit" rel="global" field="block_g">DEFAULT-G</div>'
            . '<div class="edit" rel="content" field="block_nested">DEFAULT-NESTED</div>';

        $store = [
            'block_a'      => '<h3>EDITED-A</h3><module type="btn"/>',
            'block_g'      => '<h3>EDITED-G</h3><module type="layouts"/>',
            'block_nested' => '<h3>EDITED-NESTED</h3><module type="btn" id="kept-custom"/>'
                            . '<div class="edit" rel="global" field="block_inner">INNER-DEFAULT</div>',
            'block_inner'  => '<p>EDITED-INNER</p><module type="btn"/>',
        ];
        $loader = fn($field, $rel, $relId, $cid) => $store[$field] ?? null;
        $modLoader = fn($type, $attrs) => '[' . $type . ']';

        $result = $this->processor->process($html, 7, $modLoader, $loader);

        // Every edited value renders exactly once; no default survives.
        foreach (['EDITED-A', 'EDITED-G', 'EDITED-NESTED', 'EDITED-INNER'] as $t) {
            $this->assertSame(1, substr_count($result, $t), "$t must render exactly once");
        }
        foreach (['DEFAULT-A', 'DEFAULT-G', 'DEFAULT-NESTED', 'INNER-DEFAULT'] as $t) {
            $this->assertStringNotContainsString($t, $result, "$t must be replaced by edited content");
        }
        // Inner modules from the edited fields render; custom id is preserved;
        // content-scoped btn gets the content id; no raw tags leak.
        $this->assertStringContainsString('[btn]', $result);
        $this->assertStringContainsString('[layouts]', $result);
        $this->assertStringContainsString('id="kept-custom"', $result);
        $this->assertStringContainsString('id="module-btn-7"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_edit_field_loader_receives_resolved_content_id(): void
    {
        $seen = [];
        $loader = function ($field, $rel, $relId, $cid) use (&$seen) {
            $seen[] = [$field, $rel, $relId, $cid];
            return null;
        };

        $html = '<div class="edit" rel="content" field="content"><module type="btn"/></div>'
            . '<div class="edit" rel="global" field="header"><module type="btn"/></div>';

        $this->processor->process($html, 7, fn() => '', $loader);

        $this->assertContains(['content', 'content', null, 7], $seen);
        $this->assertContains(['header', 'global', null, null], $seen);
    }

    // ── Regression: module loader returning a non-string (Stringable) ──
    // THE bug that blanked all 406 layouts: the real module renderer returns a
    // Stringable / HtmlString, and a strict is_string() check dropped it. The
    // loader result must be coerced to string.

    public function test_module_loader_stringable_object_is_rendered(): void
    {
        $stringable = new class {
            public function __toString(): string { return '<b>STRINGABLE-OUTPUT</b>'; }
        };

        $result = $this->processor->process('<module type="btn"/>', null, fn() => $stringable);

        $this->assertStringContainsString('STRINGABLE-OUTPUT', $result,
            'FIX: a Stringable loader result is coerced to string, not dropped');
        $this->assertStringContainsString('class="module module-btn"', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_module_loader_html_string_is_rendered(): void
    {
        $result = $this->processor->process(
            '<module type="btn"/>',
            null,
            fn() => new \Illuminate\Support\HtmlString('<span>HTML-STRING</span>')
        );

        $this->assertStringContainsString('HTML-STRING', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_module_loader_null_or_false_yields_empty_content(): void
    {
        $nullOut = $this->processor->process('<module type="btn"/>', null, fn() => null);
        $this->assertStringContainsString('class="module module-btn"', $nullOut);
        $this->assertStringNotContainsString('<module', $nullOut);

        $falseOut = $this->processor->process('<module type="btn"/>', null, fn() => false);
        $this->assertStringContainsString('class="module module-btn"', $falseOut);
    }

    // ── Regression: edit-field loader returning FALSE keeps the inline default ──
    // edit_field() returns false for an empty field; that must NOT blank the
    // region (the bug that emptied layout sections).

    public function test_edit_field_loader_false_keeps_inline_default(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<module type="btn"/>'
            . '</div>';

        // Loader returns false (the real edit_field() "empty field" value).
        $result = $this->processor->process($html, 3, fn($t, $a) => '', fn() => false);

        $this->assertStringContainsString('id="module-btn-3"', $result,
            'FIX: a false edit-field loader result keeps the inline default');
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Regression: many sibling modules in one scope get DISTINCT ids ──
    // (duplicate ids collided in the real load() cache → every module rendered
    // the first one's content). The allocator must hand out unique ids.

    public function test_many_sibling_layouts_get_distinct_ids(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . str_repeat('<module type="layouts"/>', 8)
            . '</div>';

        // Loader echoes the id so we can confirm each module is distinct.
        $result = $this->processor->process($html, 1, fn($t, $a) => '[' . $a['id'] . ']');

        $ids = ['module-layouts-1', 'module-layouts-1--1', 'module-layouts-1--2',
            'module-layouts-1--3', 'module-layouts-1--4', 'module-layouts-1--5',
            'module-layouts-1--6', 'module-layouts-1--7'];
        foreach ($ids as $id) {
            $this->assertSame(1, substr_count($result, '[' . $id . ']'),
                "id $id must appear exactly once (no duplicate / collision)");
        }
        $this->assertStringNotContainsString('<module', $result);
    }

    // ── Recursive module output processing ──

    public function test_module_output_is_recursively_processed(): void
    {
        // A "layouts" module renders content that itself contains a <module>;
        // the nested module must also be resolved (not left raw).
        $loader = function ($type, $attrs) {
            if ($type === 'layouts') {
                return 'WRAP[<module type="btn"/>]';
            }
            if ($type === 'btn') {
                return 'BUTTON';
            }
            return '';
        };

        $html = '<module type="layouts" template="x"/>';
        $result = $this->processor->process($html, null, $loader);

        $this->assertStringContainsString('WRAP[', $result);
        $this->assertStringContainsString('BUTTON', $result);
        $this->assertStringContainsString('module-btn', $result);
        $this->assertStringNotContainsString('<module', $result);
    }

    public function test_recursion_is_depth_bounded_no_infinite_loop(): void
    {
        // A pathological module that always re-emits itself must terminate
        // (depth guard) rather than recurse forever.
        $loader = fn($type, $attrs) => '<module type="loop"/>';

        $html = '<module type="loop"/>';
        $result = $this->processor->process($html, null, $loader);

        // Terminates and returns a string (no infinite loop / no fatal).
        $this->assertIsString($result);
    }

    // ── Helper access ──

    public function test_helper_access(): void
    {
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\TagLexer::class, $this->processor->getLexer());
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\AttributeParser::class, $this->processor->getAttributeParser());
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\ContentProtector::class, $this->processor->getContentProtector());
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\ModuleIdAllocator::class, $this->processor->getModuleIdAllocator());
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\ModuleRenderer::class, $this->processor->getModuleRenderer());
        $this->assertInstanceOf(\MicroweberPackages\App\Utils\ParserHelpers\EditFieldExtractor::class, $this->processor->getEditFieldExtractor());
    }
}

<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\LayoutProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Exhaustive nested-edit-field + edge-case coverage for the LayoutProcessor
 * pipeline (now the default parser). Uses fake module/edit-field loaders so the
 * tests are fast and DB-free.
 */
class LayoutProcessorNestedTest extends TestCase
{
    private LayoutProcessor $p;

    protected function setUp(): void
    {
        parent::setUp();
        $this->p = new LayoutProcessor();
    }

    /** Echo the module type+id so we can assert what rendered where. */
    private function modLoader(): callable
    {
        return fn($type, $attrs) => '[' . $type . ':' . ($attrs['id'] ?? '?') . ']';
    }

    /** Field store loader. */
    private function fieldLoader(array $store): callable
    {
        return fn($field, $rel, $relId, $cid) => $store[$field] ?? null;
    }

    // ─────────────────────────────────────────────────────────────
    // Deep nesting
    // ─────────────────────────────────────────────────────────────

    public function test_three_level_nested_edit_fields_all_resolve(): void
    {
        $html = '<div class="edit" rel="content" field="lvl1">D1</div>';
        $store = [
            'lvl1' => 'L1<div class="edit" rel="content" field="lvl2">D2</div>',
            'lvl2' => 'L2<div class="edit" rel="content" field="lvl3">D3</div>',
            'lvl3' => 'L3<module type="btn"/>',
        ];
        $out = $this->p->process($html, 3, $this->modLoader(), $this->fieldLoader($store));

        foreach (['L1', 'L2', 'L3'] as $m) {
            $this->assertSame(1, substr_count($out, $m), "$m once");
        }
        $this->assertStringContainsString('[btn:module-btn-3]', $out);
        $this->assertStringNotContainsString('<module', $out);
        $this->assertStringNotContainsString('D1', $out);
        $this->assertStringNotContainsString('D3', $out);
    }

    public function test_mixed_rel_scopes_in_nesting(): void
    {
        // content field whose loaded content has a GLOBAL field (no content id)
        // and a deeper CONTENT field.
        $html = '<div class="edit" rel="content" field="root">D</div>';
        $store = [
            'root'   => '<module type="btn"/>'
                      . '<div class="edit" rel="global" field="g">DG</div>',
            'g'      => '<module type="btn"/>'
                      . '<div class="edit" rel="content" field="c2">DC</div>',
            'c2'     => '<module type="btn"/>',
        ];
        $out = $this->p->process($html, 5, $this->modLoader(), $this->fieldLoader($store));

        // root btn → content scope 5; g btn → global; c2 btn → content scope 5.
        $this->assertStringContainsString('[btn:module-btn-5]', $out);
        $this->assertStringContainsString('[btn:module-btn]', $out);       // global
        $this->assertStringContainsString('[btn:module-btn-5--1]', $out);  // 2nd content-scoped
        $this->assertStringNotContainsString('<module', $out);
    }

    public function test_same_module_type_at_many_levels_gets_unique_ids(): void
    {
        $html = '<div class="edit" rel="content" field="a">D</div>';
        $store = [
            'a' => '<module type="btn"/><div class="edit" rel="content" field="b">D</div>',
            'b' => '<module type="btn"/><div class="edit" rel="content" field="c">D</div>',
            'c' => '<module type="btn"/>',
        ];
        $out = $this->p->process($html, 7, $this->modLoader(), $this->fieldLoader($store));

        // All content-scoped to 7 → distinct counters, no collision.
        $this->assertStringContainsString('[btn:module-btn-7]', $out);
        $this->assertStringContainsString('[btn:module-btn-7--1]', $out);
        $this->assertStringContainsString('[btn:module-btn-7--2]', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // Circular / depth safety
    // ─────────────────────────────────────────────────────────────

    public function test_circular_edit_field_does_not_loop_forever(): void
    {
        // field "loop" loads content that contains "loop" again.
        $html = '<div class="edit" rel="content" field="loop">D</div>';
        $store = ['loop' => 'X<div class="edit" rel="content" field="loop">D</div>'];

        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));
        // Terminates (resolved-set guard) and is a string.
        $this->assertIsString($out);
        $this->assertStringContainsString('X', $out);
    }

    public function test_module_self_referential_output_depth_bounded(): void
    {
        $loader = fn($type, $attrs) => '<module type="loop"/>'; // always re-emits
        $out = $this->p->process('<module type="loop"/>', null, $loader);
        $this->assertIsString($out); // no infinite recursion
    }

    // ─────────────────────────────────────────────────────────────
    // Loaded-content edge cases
    // ─────────────────────────────────────────────────────────────

    public function test_loaded_content_only_a_comment(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => '<!-- <module type="btn"/> -->'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));

        $this->assertStringContainsString('<!-- <module type="btn"/> -->', $out);
        $this->assertStringNotContainsString('[btn', $out); // not rendered
    }

    public function test_loaded_content_with_module_in_input_attribute(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => '<input value="<module type=\'btn\'/>"/><module type="btn"/>'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));

        // The input-attribute module is preserved verbatim (NOT rendered); only
        // the real module renders → exactly one [btn] marker.
        $this->assertStringContainsString('<input value="<module', $out);
        $this->assertStringContainsString('[btn:module-btn-1]', $out);
        $this->assertSame(1, substr_count($out, '[btn'));
    }

    public function test_loaded_content_with_nested_protected_regions(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => '<textarea><script>x</script></textarea><module type="btn"/>'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));

        $this->assertStringContainsString('<textarea><script>x</script></textarea>', $out);
        $this->assertStringContainsString('[btn:module-btn-1]', $out);
        $this->assertStringNotContainsString('mw-protected', $out);
    }

    public function test_empty_string_loaded_keeps_default(): void
    {
        $html = '<div class="edit" rel="content" field="c"><module type="btn"/></div>';
        $out = $this->p->process($html, 1, $this->modLoader(), fn() => '');
        $this->assertStringContainsString('[btn:module-btn-1]', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // Duplicate field names + custom ids at depth
    // ─────────────────────────────────────────────────────────────

    public function test_duplicate_field_name_resolved_once(): void
    {
        $html = '<div class="edit" rel="content" field="dup">D</div>'
              . '<div class="edit" rel="content" field="dup">D</div>';
        $store = ['dup' => 'CONTENT-<module type="btn"/>'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));
        // Same key resolved once; both regions show the loaded content.
        $this->assertGreaterThanOrEqual(1, substr_count($out, 'CONTENT-'));
        $this->assertStringNotContainsString('<module', $out);
    }

    public function test_custom_id_preserved_deep_in_nesting(): void
    {
        $html = '<div class="edit" rel="content" field="a">D</div>';
        $store = [
            'a' => '<div class="edit" rel="content" field="b">D</div>',
            'b' => '<module type="btn" id="deep-custom"/>',
        ];
        $out = $this->p->process($html, 9, $this->modLoader(), $this->fieldLoader($store));
        $this->assertStringContainsString('[btn:deep-custom]', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // Unicode / entities / malformed in nested content
    // ─────────────────────────────────────────────────────────────

    public function test_unicode_and_entities_in_loaded_content(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => '<h3>café 日本語 😀 &amp; &lt;x&gt;</h3><module type="btn"/>'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));
        $this->assertStringContainsString('café 日本語 😀 &amp; &lt;x&gt;', $out);
        $this->assertStringContainsString('[btn:module-btn-1]', $out);
    }

    public function test_malformed_unclosed_module_in_loaded_content_no_fatal(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => 'before <module type="btn" oops'];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));
        $this->assertIsString($out);
        $this->assertStringContainsString('before', $out);
    }

    public function test_deeply_nested_divs_inside_edit_field(): void
    {
        $html = '<div class="edit" rel="content" field="c">D</div>';
        $store = ['c' => str_repeat('<div>', 15) . '<module type="btn"/>' . str_repeat('</div>', 15)];
        $out = $this->p->process($html, 1, $this->modLoader(), $this->fieldLoader($store));
        $this->assertStringContainsString('[btn:module-btn-1]', $out);
        $this->assertStringNotContainsString('<module', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // rel="inherit" — fields inherited from a master content
    // ─────────────────────────────────────────────────────────────

    public function test_inherit_field_loads_from_master_and_scopes_to_master(): void
    {
        $html = '<div class="edit" rel="inherit" field="footer">DEFAULT</div>';

        $seen = [];
        $loader = function ($field, $rel, $relId, $cid) use (&$seen) {
            $seen[] = [$field, $rel, $cid];
            return $cid === 99 ? '<h3>MASTER-FOOTER</h3><module type="btn"/>' : null;
        };
        // Current content 10 inherits from master 99.
        $resolver = fn($id) => $id === 10 ? 99 : null;

        $out = $this->p->process($html, 10, $this->modLoader(), $loader, $resolver);

        // The loader was called with the MASTER id (99), not the child (10).
        $this->assertContains(['footer', 'inherit', 99], $seen);
        $this->assertStringContainsString('MASTER-FOOTER', $out);
        // The module in the inherited region is scoped to the master id.
        $this->assertStringContainsString('[btn:module-btn-99]', $out);
        $this->assertStringNotContainsString('DEFAULT', $out);
    }

    public function test_inherit_without_resolver_falls_back_to_current_id(): void
    {
        // No resolver → inherit behaves like content scoped to the current id.
        $html = '<div class="edit" rel="inherit" field="footer">D</div>';
        $store = ['footer' => '<module type="btn"/>'];
        $out = $this->p->process($html, 4, $this->modLoader(), $this->fieldLoader($store));
        $this->assertStringContainsString('[btn:module-btn-4]', $out);
    }

    public function test_deep_inherit_then_nested_content_fields(): void
    {
        // Inherited region (from master) itself contains a nested content field.
        $html = '<div class="edit" rel="inherit" field="inh">D</div>';
        $store = [
            'inh' => 'INH<module type="btn"/>'
                   . '<div class="edit" rel="content" field="inner">D2</div>',
            'inner' => 'INNER<module type="btn"/>',
        ];
        $resolver = fn($id) => $id === 2 ? 50 : null; // current 2 → master 50
        $out = $this->p->process($html, 2, $this->modLoader(), $this->fieldLoader($store), $resolver);

        $this->assertStringContainsString('INH', $out);
        $this->assertStringContainsString('INNER', $out);
        // inherited region module → master scope 50; nested content field module
        // → current content scope 2.
        $this->assertStringContainsString('[btn:module-btn-50]', $out);
        $this->assertStringContainsString('[btn:module-btn-2]', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // Modules that have edit fields IN them (rel="module" regions)
    // ─────────────────────────────────────────────────────────────

    public function test_module_with_internal_edit_field_default_renders(): void
    {
        // A layout module renders a section containing a rel=module edit field
        // whose inline default holds another module.
        $loader = function ($type, $attrs) {
            if ($type === 'layouts') {
                return '<section class="edit" rel="module" field="lf-' . ($attrs['id'] ?? '') . '">'
                    . 'INLINE<module type="btn"/></section>';
            }
            return '[' . $type . ':' . ($attrs['id'] ?? '?') . ']';
        };
        // No saved content for the module field → keep inline default.
        $out = $this->p->process('<module type="layouts"/>', 5, $loader, fn() => null);

        $this->assertStringContainsString('INLINE', $out);
        $this->assertStringContainsString('[btn:', $out);     // inner module rendered
        $this->assertStringContainsString('module-layouts', $out);
        $this->assertStringNotContainsString('<module', $out);
    }

    public function test_module_internal_edit_field_edited_content_replaces_default(): void
    {
        $loader = function ($type, $attrs) {
            if ($type === 'layouts') {
                return '<section class="edit" rel="module" field="lf">'
                    . 'DEFAULT-IN-MODULE<module type="btn"/></section>';
            }
            return '[' . $type . ']';
        };
        $store = ['lf' => 'EDITED-IN-MODULE<module type="btn"/><module type="btn"/>'];
        $out = $this->p->process('<module type="layouts"/>', 5, $loader, $this->fieldLoader($store));

        $this->assertStringContainsString('EDITED-IN-MODULE', $out);
        $this->assertStringNotContainsString('DEFAULT-IN-MODULE', $out);
        $this->assertSame(2, substr_count($out, '[btn]')); // both edited modules render
        $this->assertStringNotContainsString('<module', $out);
    }

    // ─────────────────────────────────────────────────────────────
    // Comments (HTML + Blade) inside form tags
    // ─────────────────────────────────────────────────────────────

    public function test_html_and_blade_comments_in_form_keep_modules_unrendered(): void
    {
        $html = '<form action="/x" method="post">'
            . '<!-- <module type="btn"/> -->'                                  // html comment
            . '{{-- <module type="btn"/> --}}'                                 // blade comment
            . '<label><!-- <module type="btn"/> -->Name</label>'
            . '<input name="n"/>'
            . '<select><!-- <module type="btn"/> --><option>A</option></select>'
            . '<button><!-- <module type="btn"/> -->Go</button>'
            . '<div class="edit" rel="content" field="c"><module type="btn"/></div>' // REAL module
            . '</form>';

        $out = $this->p->process($html, 3, $this->modLoader(), fn() => null);

        // The form scaffolding is intact.
        $this->assertStringContainsString('<form action="/x"', $out);
        $this->assertStringContainsString('<input name="n"/>', $out);
        $this->assertStringContainsString('<select>', $out);
        $this->assertStringContainsString('<option>A</option>', $out);
        // Exactly ONE module rendered (the real one); the 5 comment-wrapped
        // modules stay verbatim and are NOT rendered.
        $this->assertSame(1, substr_count($out, '[btn:'), 'only the real module renders');
        $this->assertStringContainsString('[btn:module-btn-3]', $out);
        $this->assertSame(5, substr_count($out, '<module'), 'all 5 comment modules kept verbatim');
        $this->assertStringNotContainsString('mw-protected', $out);
    }

    public function test_module_inside_module_internal_edit_field_renders(): void
    {
        // layouts → section(rel=module) → its content has ANOTHER layouts module
        // → which also has a rel=module section → with a btn. Two levels deep.
        $loader = function ($type, $attrs) {
            static $depth = 0;
            if ($type === 'layouts') {
                $depth++;
                if ($depth === 1) {
                    return '<section class="edit" rel="module" field="a">'
                        . '<module type="layouts"/></section>';
                }
                return '<section class="edit" rel="module" field="b">'
                    . '<module type="btn"/></section>';
            }
            return '[btn]';
        };
        $out = $this->p->process('<module type="layouts"/>', 1, $loader, fn() => null);

        $this->assertStringContainsString('[btn]', $out);
        $this->assertStringNotContainsString('<module', $out);
    }
}

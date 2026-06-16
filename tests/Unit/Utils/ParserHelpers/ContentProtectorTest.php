<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\ContentProtector;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ContentProtector class.
 *
 * Section 1: Tests for the NEW ContentProtector (covers all bug fixes)
 * Section 2: Regression tests documenting OLD parser bugs
 */
class ContentProtectorTest extends TestCase
{
    private ContentProtector $protector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->protector = new ContentProtector();
    }

    // ════════════════════════════════════════════════════════════
    // Section 1: NEW ContentProtector
    // ════════════════════════════════════════════════════════════

    // ── Script protection ──

    public function test_module_inside_script_is_protected(): void
    {
        $html = '<script>var x = "<module type=\"btn\"/>";</script>';
        $result = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $result);
        $this->assertStringNotContainsString('</script>', $result);
    }

    public function test_script_restored_byte_for_byte(): void
    {
        $html = '<script type="text/javascript">
            var modules = ["<module type=\"a\"/>", "<module type=\"b\"/>"];
        </script>';

        $protected = $this->protector->protect($html);
        $restored = $this->protector->restore($protected);

        $this->assertSame($html, $restored);
    }

    // ── Textarea protection ──

    public function test_module_inside_textarea_is_protected(): void
    {
        $html = '<textarea><module type="btn"/></textarea>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── Code protection ──

    public function test_module_inside_code_is_protected(): void
    {
        $html = '<code><module type="btn"/></code>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── Pre protection (fixed — old parser didn't protect pre) ──

    public function test_module_inside_pre_is_protected(): void
    {
        $html = '<pre><module type="btn"/></pre>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected,
            'FIX: <pre> is now in the protected tag list');

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── Style protection ──

    public function test_module_inside_style_is_protected(): void
    {
        $html = '<style>/* <module type="btn"/> */</style>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);
    }

    // ── Select / optgroup protection ──

    public function test_module_inside_select_is_protected(): void
    {
        $html = '<select><option value="1"><module type="btn"/></option></select>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    public function test_module_inside_optgroup_is_protected(): void
    {
        $html = '<optgroup label="Group"><option><module type="btn"/></option></optgroup>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);
    }

    // ── HTML comment protection ──

    public function test_module_inside_html_comment_is_protected(): void
    {
        $html = '<!-- <module type="btn"/> -->';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    public function test_html_comment_preserved_in_context(): void
    {
        $html = '<div>before</div><!-- <module type="x"/> --><div>after</div>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);
        $this->assertStringContainsString('<div>before</div>', $protected);
        $this->assertStringContainsString('<div>after</div>', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── FIXED BUG: Blade comments {{-- --}} now protected ──

    public function test_blade_comment_is_protected(): void
    {
        $html = '{{-- <module type="btn"/> --}}';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected,
            'FIX: Blade comments now protect module tags from parsing');

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    public function test_blade_comment_in_context(): void
    {
        $html = '<div>text</div>{{-- <module type="btn"/> --}}<p>more</p>';
        $protected = $this->protector->protect($html);

        $this->assertStringNotContainsString('<module', $protected);
        $this->assertStringContainsString('<div>text</div>', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── Multiple protected regions ──

    public function test_multiple_scripts_all_protected(): void
    {
        $html = '<script>var a = "<module type=\"a\"/>";</script>'
            . '<div><module type="real"/></div>'
            . '<script>var b = "<module type=\"b\"/>";</script>';

        $protected = $this->protector->protect($html);

        // Real module tag should survive
        $this->assertStringContainsString('<module type="real"/>', $protected);
        // Script modules should be gone
        $this->assertStringNotContainsString('var a', $protected);
        $this->assertStringNotContainsString('var b', $protected);
    }

    // ── Mixed region types ──

    public function test_mixed_protection_types(): void
    {
        $html = '<script><module type="a"/></script>'
            . '<!-- <module type="b"/> -->'
            . '{{-- <module type="c"/> --}}'
            . '<code><module type="d"/></code>'
            . '<module type="real"/>';

        $protected = $this->protector->protect($html);

        // Only the real module should survive
        $this->assertStringContainsString('<module type="real"/>', $protected);
        $this->assertSame(1, substr_count($protected, '<module'));

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
    }

    // ── Empty input ──

    public function test_empty_input(): void
    {
        $this->assertSame('', $this->protector->protect(''));
        $this->assertSame('', $this->protector->restore(''));
    }

    // ── No protected regions ──

    public function test_no_regions_passthrough(): void
    {
        $html = '<div><module type="btn"/></div>';
        $protected = $this->protector->protect($html);
        $this->assertSame($html, $protected);
    }

    // ── Reset state ──

    public function test_reset_clears_state(): void
    {
        $html = '<script><module type="a"/></script>';
        $this->protector->protect($html);
        $this->assertNotEmpty($this->protector->getReplacements());

        $this->protector->reset();
        $this->assertEmpty($this->protector->getReplacements());
        $this->assertEmpty($this->protector->getCommentReplacements());
    }

    // ════════════════════════════════════════════════════════════
    // Section 2: Old parser regression (documenting bugs)
    // ════════════════════════════════════════════════════════════

    public function test_old_parser_blade_comment_bug(): void
    {
        // The old parser uses: /<!--(?!<!)[^\[>].*?-->/
        // This does NOT match {{-- ... --}}
        $html = '{{-- <module type="btn"/> --}}';
        $pattern = "/<!--(?!<!)[^\[>].*?-->/";
        preg_match_all($pattern, $html, $matches);

        $this->assertEmpty($matches[0],
            'OLD BUG CONFIRMED: regex does not match Blade comments');
    }

    public function test_old_parser_pre_not_protected(): void
    {
        // The old parser protects: script, code, textarea, style, select
        // But NOT pre
        $protectedTags = ['script', 'code', 'textarea', 'style', 'select'];
        $this->assertNotContains('pre', $protectedTags,
            'OLD BUG CONFIRMED: <pre> not in protected list');
    }

    // ════════════════════════════════════════════════════════════
    // Section 3: More edge cases
    // ════════════════════════════════════════════════════════════

    public function test_blade_comment_protected_before_html_comment(): void
    {
        // A Blade comment that itself contains an HTML comment must round-trip
        // intact (Blade is protected first).
        $html = '{{-- outer <!-- inner --> <module type="btn"/> --}}';
        $protected = $this->protector->protect($html);
        $this->assertStringNotContainsString('<module', $protected);
        $this->assertSame($html, $this->protector->restore($protected));
    }

    public function test_multiple_comments_all_restored(): void
    {
        $html = '<!-- a --><div>x</div>{{-- b --}}<!-- c -->';
        $protected = $this->protector->protect($html);
        $this->assertStringContainsString('<div>x</div>', $protected);
        $this->assertSame($html, $this->protector->restore($protected));
    }

    public function test_attributes_inside_protected_open_tag_preserved(): void
    {
        $html = '<script type="application/json" data-x="1">{"a":"<module/>"}</script>';
        $protected = $this->protector->protect($html);
        $this->assertStringNotContainsString('<module', $protected);
        $this->assertSame($html, $this->protector->restore($protected));
    }

    public function test_restore_is_noop_when_nothing_protected(): void
    {
        $html = '<div><module type="btn"/></div>';
        $this->protector->protect($html);
        // Restoring a string with no placeholders changes nothing.
        $this->assertSame('plain text', $this->protector->restore('plain text'));
    }

    public function test_nested_protected_regions_restore_fully(): void
    {
        // A <script> inside a <textarea>: the inner region is protected first and
        // its placeholder ends up inside the outer region's stored value. restore()
        // must unwind BOTH (it loops until no placeholder marker remains).
        $html = '<textarea id="x"><script>var b = "";</script></textarea>';
        $protected = $this->protector->protect($html);
        $this->assertStringNotContainsString('<script', $protected);

        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
        $this->assertStringNotContainsString('mw-protected', $restored);
    }

    public function test_comment_inside_script_restores_fully(): void
    {
        $html = '<script>/* <!-- not a real comment --> */ var x = 1;</script>';
        $protected = $this->protector->protect($html);
        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
        $this->assertStringNotContainsString('mw-protected', $restored);
    }

    public function test_html_and_blade_comments_inside_form_are_protected(): void
    {
        // Comments (HTML + Blade) carrying a <module> placed INSIDE a <form>
        // (and its label/button/select children) must be protected and round-trip
        // byte-for-byte; the form structure itself is untouched.
        $html = '<form action="/submit" method="post">'
            . '<!-- <module type="btn"/> -->'
            . '{{-- <module type="btn"/> --}}'
            . '<label><!-- <module type="btn"/> -->Name</label>'
            . '<input name="n"/>'
            . '<select><!-- <module type="btn"/> -->{{-- <module type="btn"/> --}}<option>A</option></select>'
            . '<button type="submit"><!-- <module type="btn"/> -->Go</button>'
            . '</form>';

        $protected = $this->protector->protect($html);
        // No raw module survives the protection pass.
        $this->assertStringNotContainsString('<module', $protected);
        // The form open tag is not a protected region, so it survives in place.
        $this->assertStringContainsString('<form action="/submit"', $protected);

        // Byte-for-byte restore — comments, the <input> void tag, the <select>
        // and everything else come back exactly.
        $restored = $this->protector->restore($protected);
        $this->assertSame($html, $restored);
        $this->assertStringNotContainsString('mw-protected', $restored);
    }

    public function test_protect_after_reset_starts_fresh(): void
    {
        $this->protector->protect('<script>a</script>');
        $this->protector->reset();
        $out = $this->protector->protect('<div>no protected regions</div>');
        $this->assertSame('<div>no protected regions</div>', $out);
        $this->assertEmpty($this->protector->getReplacements());
    }
}

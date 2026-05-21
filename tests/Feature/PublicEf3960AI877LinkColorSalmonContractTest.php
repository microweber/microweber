<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-ef3960 / AI-877 P3
 *
 * Bootstrap template _variables.scss sets $link-color: #f4a261 (demo salmon),
 * which compiles into `a { color: ... }` in app.css and trickles to every bare
 * text link on the public frontend (footer mailto, "Create a website" credit
 * link, /search "Return home" link, any unclassed body-text <a>).
 *
 * Fix: global scope override in public-touch.css (no @media guard):
 *   a:not(.btn):not(.navbar-brand):not([class*="btn-"]) { color: var(--color-primary, #0d6efd) }
 *   a:not(.btn):not(.navbar-brand):not([class*="btn-"]):hover { color: var(--color-primary-hover, #0b5ed7) }
 *
 * 6th instance of the Stage-2 salmon-cascade family.
 */
class PublicEf3960AI877LinkColorSalmonContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $served;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
        );
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->served = (string) file_get_contents(
            base_path('public/templates/bootstrap/css/public-touch.css')
        );
    }

    // ─── Source-level: new rule present ──────────────────────────────────────

    #[Test]
    public function bare_link_color_override_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~a:not\(\.btn\):not\(\.navbar-brand\)[^{]*\{[^}]*color:\s*var\(--color-primary~s',
            $this->src,
            'public-touch.css must contain a:not(.btn):not(.navbar-brand) { color: var(--color-primary, #0d6efd) } to override the salmon link color.'
        );
    }

    #[Test]
    public function bare_link_hover_color_override_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~a:not\(\.btn\):not\(\.navbar-brand\)[^{]*:hover\s*\{[^}]*color:\s*var\(--color-primary-hover~s',
            $this->src,
            'public-touch.css must contain a:not(.btn):not(.navbar-brand):hover { color: var(--color-primary-hover, #0b5ed7) }.'
        );
    }

    #[Test]
    public function rule_appears_after_all_at_media_blocks(): void
    {
        // Verify the bare-link rule is at global scope (outside @media) by
        // confirming its position in the comment-stripped source comes AFTER
        // the last @media occurrence. Any @media block appearing after the rule
        // would indicate the rule is nested inside that block.
        $rulePos = strrpos($this->srcStripped, 'a:not(.btn):not(.navbar-brand)');
        $this->assertNotFalse($rulePos, 'a:not(.btn):not(.navbar-brand) selector must exist in comment-stripped source.');

        $lastMediaPos = strrpos($this->srcStripped, '@media');
        $this->assertNotFalse($lastMediaPos, '@media blocks must exist in the file.');

        $this->assertGreaterThan(
            $lastMediaPos,
            $rulePos,
            'The bare-link override selector must appear AFTER the last @media block, confirming it is at global scope.'
        );
    }

    #[Test]
    public function btn_class_links_are_excluded(): void
    {
        // The :not(.btn) guard must be present to prevent the rule from
        // overriding button link colors.
        $this->assertStringContainsString(
            ':not(.btn)',
            $this->srcStripped,
            'The bare-link color override must exclude .btn elements via :not(.btn).'
        );
    }

    #[Test]
    public function navbar_brand_links_are_excluded(): void
    {
        $this->assertStringContainsString(
            ':not(.navbar-brand)',
            $this->srcStripped,
            'The bare-link color override must exclude .navbar-brand via :not(.navbar-brand) so the Bootstrap nav chrome keeps its own color.'
        );
    }

    // ─── Source + served mirror parity ───────────────────────────────────────

    #[Test]
    public function task_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-ef3960',
            $this->src,
            'public-touch.css must carry the task-2026-05-22-ef3960 marker.'
        );
    }

    #[Test]
    public function source_and_served_mirror_are_byte_identical(): void
    {
        $this->assertSame(
            $this->src,
            $this->served,
            'Templates/Bootstrap/resources/assets/css/public-touch.css and ' .
            'public/templates/bootstrap/css/public-touch.css must be byte-identical.'
        );
    }

    // ─── Regression guards ───────────────────────────────────────────────────

    #[Test]
    public function salmon_hex_not_a_link_color_in_source(): void
    {
        // The salmon value #f4a261 must not appear as an `a { color: ... }` value
        // in this override file (it can appear in comments/docblocks).
        $this->assertDoesNotMatchRegularExpression(
            '~\ba\b[^{]*\{\s*color:\s*#f4a261~s',
            $this->srcStripped,
            'No bare <a> color rule in public-touch.css may use the salmon value #f4a261.'
        );
    }

    #[Test]
    public function btn_primary_rule_still_present(): void
    {
        // Regression guard: AI-868 .btn-primary color rule must still exist.
        $this->assertStringContainsString(
            '.btn-primary',
            $this->srcStripped,
            'AI-868 .btn-primary rule must still be present after AI-877 appended its rule.'
        );
    }
}

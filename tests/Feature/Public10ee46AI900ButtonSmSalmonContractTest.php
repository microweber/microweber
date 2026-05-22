<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-10ee46 / AI-900 P3 — 7th salmon-cascade instance.
 *
 * CONTACT US topbar button (class="btn btn-sm") rendered salmon
 * rgb(244, 162, 97) because Bootstrap template $primary: #f4a261
 * cascades into .btn-sm elements without .btn-primary.
 *
 * AI-868 targeted .btn-primary (missed bare .btn-sm).
 * AI-877 targeted bare <a> text link color (missed button bg).
 * This fix directly targets a.btn.btn-sm / button.btn.btn-sm with
 * :not() exclusions for outline/danger variants.
 *
 * Path B discipline (AI-866 v2 lineage): literal hex #0d6efd,
 * not var() — confirmed-working pattern for this cascade family.
 */
class Public10ee46AI900ButtonSmSalmonContractTest extends TestCase
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
    public function button_btn_sm_gets_brand_blue_background(): void
    {
        $this->assertStringContainsString(
            'button.btn.btn-sm:not(.btn-outline-primary):not(.btn-danger):not(.btn-outline-danger)',
            $this->src,
            'button.btn.btn-sm with :not() exclusions must be present — CONTACT US fix.'
        );
    }

    #[Test]
    public function anchor_btn_sm_gets_brand_blue_background(): void
    {
        $this->assertStringContainsString(
            'a.btn.btn-sm:not(.btn-outline-primary):not(.btn-danger):not(.btn-outline-danger)',
            $this->src,
            'a.btn.btn-sm with :not() exclusions must be present — anchor variant of CONTACT US fix.'
        );
    }

    #[Test]
    public function rule_uses_brand_blue_hex_not_salmon(): void
    {
        // The task marker is inside a CSS comment so it's absent from srcStripped.
        // Use the selector itself as the slice anchor in stripped source — the rule
        // body (background-color, border-color, color) immediately follows.
        $selectorPos = strrpos($this->srcStripped, 'button.btn.btn-sm:not(.btn-outline-primary)');
        $this->assertNotFalse($selectorPos, 'button.btn.btn-sm selector must exist in stripped source.');

        $slice = substr($this->srcStripped, $selectorPos, 500);
        $this->assertStringContainsString('#0d6efd', $slice,
            'The AI-900 rule body must set background-color to brand-blue #0d6efd.');
        $this->assertStringNotContainsString('#f4a261', $slice,
            'Salmon #f4a261 must NOT appear in the AI-900 rule body.');
        $this->assertStringNotContainsString('244, 162, 97', $slice,
            'Salmon rgb(244,162,97) must NOT appear in the AI-900 rule body.');
    }

    #[Test]
    public function rule_uses_important_to_defeat_bootstrap_cascade(): void
    {
        $selectorPos = strrpos($this->srcStripped, 'button.btn.btn-sm:not(.btn-outline-primary)');
        $this->assertNotFalse($selectorPos, 'button.btn.btn-sm selector must exist in stripped source.');

        $slice = substr($this->srcStripped, $selectorPos, 500);
        $this->assertStringContainsString('!important', $slice,
            'The rule must use !important to defeat Bootstrap cascade specificity.');
    }

    // ─── Global-scope assertion via position comparison ───────────────────────

    #[Test]
    public function rule_appears_after_all_at_media_blocks(): void
    {
        $rulePos = strrpos($this->srcStripped, 'button.btn.btn-sm:not(.btn-outline-primary)');
        $this->assertNotFalse($rulePos, 'button.btn.btn-sm selector must exist in comment-stripped source.');

        $lastMediaPos = strrpos($this->srcStripped, '@media');
        $this->assertNotFalse($lastMediaPos, '@media blocks must exist in the file.');

        $this->assertGreaterThan(
            $lastMediaPos,
            $rulePos,
            'The .btn-sm override must appear AFTER the last @media block (global scope, all viewports).'
        );
    }

    // ─── Exclusion guards ─────────────────────────────────────────────────────

    #[Test]
    public function outline_primary_excluded(): void
    {
        $this->assertStringContainsString(
            ':not(.btn-outline-primary)',
            $this->srcStripped,
            'Shopping cart btn-outline-primary btn-sm must be excluded from the fix.'
        );
    }

    #[Test]
    public function danger_variants_excluded(): void
    {
        $this->assertStringContainsString(':not(.btn-danger)', $this->srcStripped,
            '.btn-danger must be excluded so red buttons stay red.');
        $this->assertStringContainsString(':not(.btn-outline-danger)', $this->srcStripped,
            '.btn-outline-danger must be excluded so outline-danger buttons stay as-is.');
    }

    // ─── Regression guards ────────────────────────────────────────────────────

    #[Test]
    public function ai877_bare_link_color_rule_still_present(): void
    {
        $this->assertStringContainsString(
            'a:not(.btn):not(.navbar-brand):not([class*="btn-"])',
            $this->srcStripped,
            'AI-877 bare-link color override must still be present after AI-900 appended its rule.'
        );
    }

    #[Test]
    public function ai868_btn_primary_rule_still_present(): void
    {
        $this->assertStringContainsString(
            '.btn-primary',
            $this->srcStripped,
            'AI-868 .btn-primary rule must still be present.'
        );
    }

    #[Test]
    public function ai866_shopping_cart_rule_still_present(): void
    {
        $this->assertStringContainsString(
            'js-shopping-cart-quantity',
            $this->srcStripped,
            'AI-866 shopping cart badge brand-blue rule must still be present.'
        );
    }

    // ─── Source + served mirror parity ───────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-10ee46',
            $this->src,
            'public-touch.css must carry the task-2026-05-22-10ee46 marker.'
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
}

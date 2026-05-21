<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-21-bef102 / AI-868 — Salmon .btn-primary on desktop
 * public pages (media-query-lift fix). P2.
 * Jira: https://microweber.atlassian.net/browse/AI-868
 *
 * Pre-fix ALL .btn-primary buttons rendered salmon `#f4a261` on desktop
 * (>768px, non-touch). The brand-blue fix existed in public-touch.css
 * but was trapped inside `@media (max-width: 768px), (pointer: coarse)`.
 * Bootstrap template's `$primary: #f4a261` (demo colour) compiled to
 * salmon and won at desktop with no competition.
 *
 * Fix: moved the `.btn-primary` color + hover block OUT of the `@media`
 * block to global scope. Also added `:root { --mw-btn-background-color:
 * var(--color-primary); }` to ensure nav buttons routing through
 * `var(--mw-btn-background-color)` in `app.css` also receive brand-blue.
 *
 * 5th instance of the Stage-2 salmon-cascade family (AI-794a + AI-855
 * + AI-819 CHANGE + AI-866 + AI-868). This instance is the canonical
 * Stage-2 sub-variant 2 "media-query-override-flip": the fix was
 * present but gated inside a mobile-only `@media` block so desktop
 * never saw it.
 *
 * Tier-3 verified at 1440 desktop (non-touch Playwright):
 *   /login Sign In:       rgb(13, 110, 253) ✓
 *   /login Create Account: rgb(13, 110, 253) ✓
 *   homepage hero CTA:    rgb(13, 110, 253) ✓
 *   --mw-btn-background-color token: #0d6efd ✓
 *
 * Test groups:
 *   A = global-scope brand-blue rule present + old trapped rule absent
 *   B = --mw-btn-background-color :root token present
 *   C = served-mirror byte-identity
 *   D = task marker
 */
class PublicBef102AI868BtnPrimaryDesktopBrandBlueContractTest extends TestCase
{
    private string $srcPath;
    private string $mirrorPath;
    private string $source;
    private string $stripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->srcPath    = base_path('Templates/Bootstrap/resources/assets/css/public-touch.css');
        $this->mirrorPath = base_path('public/templates/bootstrap/css/public-touch.css');
        $this->source     = (string) file_get_contents($this->srcPath);
        // Strip CSS /* */ comments before negative assertions (selector-self-match guard).
        $this->stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->source) ?? $this->source;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — global-scope lift
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function btn_primary_color_rule_at_global_scope(): void
    {
        // The rule must appear BEFORE the @media (max-width: 768px)
        // block so it fires at ALL viewports.
        $mediaPos = strpos($this->stripped, '@media (max-width: 768px)');
        $this->assertNotFalse($mediaPos, '@media (max-width: 768px) block must still exist.');

        $globalRulePos = strpos($this->stripped, '.btn-primary,');
        $this->assertNotFalse($globalRulePos, '.btn-primary selector must exist at global scope.');

        $this->assertLessThan(
            $mediaPos,
            $globalRulePos,
            '.btn-primary global-scope rule must appear BEFORE the @media (max-width: 768px) block so desktop receives brand-blue.'
        );
    }

    #[Test]
    public function btn_primary_global_rule_uses_color_primary_token(): void
    {
        // Global rule must apply brand-blue via the token with literal fallback.
        $this->assertMatchesRegularExpression(
            '/\.btn-primary[\s\S]*?background-color:\s*var\(--color-primary,\s*#0d6efd\)\s*!important/s',
            $this->stripped,
            '.btn-primary global rule must set background-color: var(--color-primary, #0d6efd) !important.'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn-primary[\s\S]*?border-color:\s*var\(--color-primary,\s*#0d6efd\)\s*!important/s',
            $this->stripped,
            '.btn-primary global rule must set border-color: var(--color-primary, #0d6efd) !important.'
        );
    }

    #[Test]
    public function btn_primary_hover_rule_at_global_scope(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.btn-primary:hover[\s\S]*?background-color:\s*var\(--color-primary-hover,\s*#0b5ed7\)\s*!important/s',
            $this->stripped,
            '.btn-primary:hover global rule must use var(--color-primary-hover, #0b5ed7) !important.'
        );
    }

    #[Test]
    public function btn_primary_color_not_inside_media_query_block(): void
    {
        // The inner media-query block must NOT contain the .btn-primary
        // color override any more — it was lifted to global scope.
        $mediaStart = strpos($this->stripped, '@media (max-width: 768px)');
        $this->assertNotFalse($mediaStart);

        // Slice from @media open to the next top-level rule (a rough
        // but sufficient boundary — the @media block spans ~400 lines
        // so 20000 chars is a safe window).
        $mediaSlice = substr($this->stripped, $mediaStart, 20000);

        // Find the closing `}` that ends the @media block. We walk
        // the brace depth to find the real closing brace.
        $depth = 0;
        $closePos = false;
        for ($i = 0; $i < strlen($mediaSlice); $i++) {
            if ($mediaSlice[$i] === '{') { $depth++; }
            elseif ($mediaSlice[$i] === '}') {
                $depth--;
                if ($depth === 0) { $closePos = $i; break; }
            }
        }
        $innerBlock = $closePos !== false ? substr($mediaSlice, 0, $closePos) : $mediaSlice;

        // "background-color: var(--color-primary)" must NOT appear inside.
        $this->assertStringNotContainsString(
            'background-color: var(--color-primary)',
            $innerBlock,
            'background-color: var(--color-primary) must NOT be inside the @media (max-width: 768px) block — it was lifted to global scope for AI-868.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — --mw-btn-background-color :root token
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function root_mw_btn_background_color_token_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/:root\s*\{[^}]*--mw-btn-background-color:\s*var\(--color-primary,\s*#0d6efd\)/s',
            $this->stripped,
            ':root must declare --mw-btn-background-color: var(--color-primary, #0d6efd) so nav buttons routing through that token receive brand-blue.'
        );
    }

    #[Test]
    public function root_token_at_global_scope_before_media_query(): void
    {
        $mediaPos  = strpos($this->stripped, '@media (max-width: 768px)');
        $tokenPos  = strpos($this->stripped, '--mw-btn-background-color:');
        $this->assertNotFalse($tokenPos, '--mw-btn-background-color token declaration must exist.');
        $this->assertLessThan($mediaPos, $tokenPos, '--mw-btn-background-color must be declared before the @media block.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — served-mirror byte-identity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical(): void
    {
        if (! file_exists($this->mirrorPath)) {
            $this->markTestSkipped('Served mirror absent.');
        }
        $this->assertSame(
            md5_file($this->srcPath),
            md5_file($this->mirrorPath),
            'public/templates/bootstrap/css/public-touch.css must be byte-identical to the source.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task marker
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-21-bef102',
            $this->source,
            'public-touch.css must carry the task-bef102 marker.'
        );
        $this->assertStringContainsString(
            'AI-868',
            $this->source,
            'public-touch.css must carry the AI-868 ticket reference.'
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-2747f0 / AI-866 — header cart-counter badge brand-blue
 * cascade. 4th instance of the Stage-2 salmon-cascade family on public
 * frontend.
 * Jira: https://microweber.atlassian.net/browse/AI-866
 *
 * Pre-fix the header cart-counter badge (`<span class="btn btn-outline-primary
 * btn-sm mx-2 js-shopping-cart-quantity">` rendered from
 * `Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php`)
 * rendered salmon `rgb(244, 162, 97)` on `/shop`, `/cart`, `/premium-coffee-beans`
 * because Bootstrap's `btn-outline-primary` consumes the active template's
 * `$primary` SCSS variable which resolves to salmon (NOT brand-blue
 * `rgb(13, 110, 253)` per the AI-209 commerce-color contract).
 *
 * 4th instance of the Stage-2 salmon-cascade family on public frontend:
 *   - AI-794a auth-card CTA (`btn-primary`)
 *   - AI-855 admin Media Library upload button (cascade sibling)
 *   - AI-819 CHANGE primary-btn `:not()` selector chain
 *   - AI-866 header cart-counter badge (THIS)
 * = 4-instance threshold reached; LESSONS canonicalization landed in
 * `LESSONS.md` of the same date.
 *
 * Fix shape (single CSS rule + hover variant in Templates/Bootstrap/.../public-touch.css):
 *   .js-shopping-cart-quantity,
 *   .btn-shopping-cart .badge,
 *   .btn-shopping-cart > span.btn {
 *       background-color: var(--color-primary, #0d6efd) !important;
 *       color: #fff !important;
 *       border-color: var(--color-primary, #0d6efd) !important;
 *   }
 *
 * Token choice — `var(--color-primary, #0d6efd)` consumes the existing
 * `:root --color-primary: #0d6efd` declared at public-touch.css:8.
 * NOT `var(--primary-500)` — that token lives in `microweber-theme-v3.scss`
 * (admin Filament theme), NOT in the public-frontend pipeline. Token-name
 * choice by stylesheet pipeline is the LESSONS-formalised two-modality
 * split between admin + public salmon-cascade fixes.
 *
 * NOT inside the touch-viewport @media block — defect manifests at BOTH
 * 1440 desktop AND 390 mobile; fix must be viewport-agnostic.
 *
 * Acceptance gates (verified at HEAD):
 *   - Tier-1 source-pin: AI-866 rule + hover variant in public-touch.css
 *   - Tier-2 served-page: curl /templates/bootstrap/css/public-touch.css
 *     returns the new rule body
 *   - Tier-2 mirror: src + public/ mirror byte-identical
 *   - Tier-3 runtime (Playwright): getComputedStyle().backgroundColor =
 *     'rgb(13, 110, 253)' on cart-counter badges across /shop /cart
 *     /premium-coffee-beans (deferred to designer's verify-before-accept)
 *
 * 4-group structure: A = source-presence (AI-866 docblock + rule body + hover);
 * B = served-mirror byte-identity + public-touch.css carries the rule;
 * C = scope discipline (rule OUTSIDE touch-viewport @media; viewport-agnostic);
 * D = back-compat regression sentinels (existing AI-516 cart-badge tap-target
 * rule preserved; AI-518/519/520 sibling-rules preserved).
 */
class Shop2747f0AI866CartCounterBadgeBrandBlueContractTest extends TestCase
{
    private function srcPath(): string
    {
        return base_path('Templates/Bootstrap/resources/assets/css/public-touch.css');
    }

    private function servedMirrorPath(): string
    {
        return base_path('public/templates/bootstrap/css/public-touch.css');
    }

    private function srcContents(): string
    {
        return (string) file_get_contents($this->srcPath());
    }

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*.*?\*/~s', '', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-866 source-presence (rule + hover variant + markers)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai866_rule_body_carries_brand_blue_cascade_override(): void
    {
        $source = $this->srcContents();
        // Find the AI-866 rule selector block. The 3-selector list:
        //   .js-shopping-cart-quantity,
        //   .btn-shopping-cart .badge,
        //   .btn-shopping-cart > span.btn {
        $this->assertStringContainsString('.js-shopping-cart-quantity,', $source, 'AI-866 must include .js-shopping-cart-quantity selector.');
        $this->assertStringContainsString('.btn-shopping-cart .badge,', $source, 'AI-866 must include .btn-shopping-cart .badge selector (defensive — future badge-class).');
        $this->assertStringContainsString('.btn-shopping-cart > span.btn {', $source, 'AI-866 must include .btn-shopping-cart > span.btn selector (current rendered DOM shape).');
        // Properties — brand-blue via the public-frontend token convention.
        $this->assertMatchesRegularExpression(
            '/background-color:\s*var\(--color-primary,\s*#0d6efd\)\s*!important;/',
            $source,
            'AI-866 rule must carry background-color: var(--color-primary, #0d6efd) !important.'
        );
        $this->assertMatchesRegularExpression(
            '/color:\s*#fff\s*!important;/',
            $source,
            'AI-866 rule must carry color: #fff !important for WCAG contrast against brand-blue bg.'
        );
        $this->assertMatchesRegularExpression(
            '/border-color:\s*var\(--color-primary,\s*#0d6efd\)\s*!important;/',
            $source,
            'AI-866 rule must carry border-color: var(--color-primary, #0d6efd) !important — defeats Bootstrap btn-outline-primary salmon border.'
        );
    }

    #[Test]
    public function ai866_hover_variant_present(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('.js-shopping-cart-quantity:hover,', $source, 'AI-866 must include hover variant for the brand-blue cascade.');
        $this->assertMatchesRegularExpression(
            '/background-color:\s*#0b5ed7\s*!important;/',
            $source,
            'Hover variant must use #0b5ed7 (Bootstrap-darken brand-blue at 7.5%) for visual depth on tap/hover.'
        );
    }

    #[Test]
    public function ai866_carries_task_id_markers(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('task-2026-05-18-2747f0', $source, 'AI-866 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-866', $source);
    }

    #[Test]
    public function ai866_carries_lessons_pointer(): void
    {
        $source = $this->srcContents();
        // The rule's docblock must point at LESSONS.md so future agents
        // reading the source find the 4-instance family canonicalization.
        $this->assertStringContainsString('LESSONS', $source, 'AI-866 docblock must point at the LESSONS canonicalization entry (4-instance salmon-cascade family).');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — served-mirror byte-identity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical_to_src(): void
    {
        $src = $this->srcContents();
        $served = (string) file_get_contents($this->servedMirrorPath());
        $this->assertSame(
            $src,
            $served,
            'Templates/Bootstrap/.../public-touch.css MUST be byte-identical to public/templates/bootstrap/css/public-touch.css (served-mirror convention from AI-516 onwards).'
        );
    }

    #[Test]
    public function served_mirror_carries_ai866_rule(): void
    {
        $served = (string) file_get_contents($this->servedMirrorPath());
        $this->assertStringContainsString('task-2026-05-18-2747f0', $served);
        $this->assertStringContainsString('.js-shopping-cart-quantity,', $served);
        $this->assertStringContainsString('background-color: var(--color-primary, #0d6efd) !important;', $served);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — scope discipline (rule OUTSIDE touch-viewport @media)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai866_rule_lives_outside_touch_viewport_media_block(): void
    {
        $source = $this->srcContents();
        // The AI-866 rule must NOT be nested inside the
        // `@media (max-width: 1023.98px), (hover: none) and (pointer: coarse) { ... }`
        // touch-viewport block — defect manifests at desktop too.
        // Find AI-866 rule position + the closing brace of the
        // touch-viewport @media block + verify the rule sits AFTER the
        // close brace.
        $touchMediaOpen = strpos($source, '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse) {');
        $this->assertNotFalse($touchMediaOpen, 'Touch-viewport @media block must exist for scope sentinel.');
        $ai866Pos = strpos($source, 'task-2026-05-18-2747f0');
        $this->assertNotFalse($ai866Pos);
        // The rule must sit AFTER the touch-viewport @media open. To verify
        // it's outside the @media block we check the rule sits AFTER all
        // `}` close-braces at column 0 between the @media open and the
        // rule position. Simplest: count `}` braces at column 0 between
        // touchMediaOpen and ai866Pos — must be at least 1 (the @media
        // close).
        $between = substr($source, $touchMediaOpen, $ai866Pos - $touchMediaOpen);
        $closingBraceCount = preg_match_all('/^\}/m', $between);
        $this->assertGreaterThanOrEqual(
            1,
            $closingBraceCount,
            'AI-866 rule must sit AFTER the touch-viewport @media block close (at least 1 column-0 `}` between the @media open and AI-866 pos).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai516_cart_badge_tap_target_rule_preserved(): void
    {
        $source = $this->srcContents();
        // AI-516 rule MUST stay intact — AI-866 only adds new selectors,
        // does NOT touch the existing min-width/min-height tap-target rule.
        $this->assertStringContainsString('.js-shopping-cart-quantity:not([hidden])', $source);
        $this->assertMatchesRegularExpression(
            '/\.js-shopping-cart-quantity:not\(\[hidden\]\)\s*\{[^}]*min-width:\s*44px[^}]*min-height:\s*44px[^}]*\}/s',
            $source,
            'AI-516 cart-badge tap-target rule (44x44 min) MUST stay intact.'
        );
    }

    #[Test]
    public function root_color_primary_token_preserved(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('--color-primary: #0d6efd;', $source, ':root --color-primary token must stay intact — AI-866 consumes it via var(--color-primary, #0d6efd).');
    }

    #[Test]
    public function sibling_ai_marker_rules_preserved(): void
    {
        $source = $this->srcContents();
        // AI-866 only appended at end of file — prior AI ship rules
        // must all stay intact. Sentinel-pin a sample of the public-touch.css
        // existing AI-NNN markers (AI-519/520 live in mobile-touch.css —
        // admin/checkout panel scope; NOT in this public-frontend file).
        $this->assertStringContainsString('AI-516', $source, 'AI-516 cart-icon tap-target rule must stay intact.');
        $this->assertStringContainsString('AI-518', $source, 'AI-518 header-CTA tap-target rule must stay intact.');
        $this->assertStringContainsString('AI-522', $source, 'AI-522 ContactForm tap-target rule must stay intact.');
        $this->assertStringContainsString('AI-528', $source, 'AI-528 Category nav-list tap-target rule must stay intact.');
        $this->assertStringContainsString('AI-534', $source, 'AI-534 Logo link tap-target rule must stay intact.');
        $this->assertStringContainsString('AI-535', $source, 'AI-535 Breadcrumb tap-target rule must stay intact.');
    }

    #[Test]
    public function lessons_md_carries_ai866_canonicalization_entry(): void
    {
        // The 4-instance threshold required formal LESSONS canonicalization
        // per designer's instruction. Verify the dated entry landed.
        $lessons = (string) file_get_contents(base_path('LESSONS.md'));
        $this->assertStringContainsString('Stage-2 salmon-cascade family on public frontend', $lessons);
        $this->assertStringContainsString('AI-866', $lessons);
        $this->assertStringContainsString('var(--color-primary, #0d6efd)', $lessons, 'LESSONS entry must document the public-frontend token convention (NOT --primary-500 admin convention).');
        $this->assertStringContainsString('Two-modality split', $lessons, 'LESSONS entry must document the admin/public token-name split.');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-2747f0 / AI-866 v2 — header cart-counter badge brand-blue
 * cascade. CHANGE absorbed per designer dispatch task-2026-05-18-6a307b.
 * 4th instance of the Stage-2 salmon-cascade family on public frontend.
 * Jira: https://microweber.atlassian.net/browse/AI-866
 *
 * Pre-fix the header cart-counter badge (`<span class="btn btn-outline-primary
 * btn-sm mx-2 js-shopping-cart-quantity">` rendered from
 * `Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php`)
 * rendered salmon `rgb(244, 162, 97)` because Bootstrap's `btn-outline-primary`
 * consumes the active template's `$primary` SCSS variable which resolves to
 * salmon (NOT brand-blue `rgb(13, 110, 253)` per the AI-209 commerce-color
 * contract).
 *
 * 4th instance of the Stage-2 salmon-cascade family on public frontend
 * (AI-794a + AI-855 + AI-819 CHANGE + AI-866 = 4-instance threshold);
 * LESSONS canonicalization landed in `LESSONS.md` of the same date.
 *
 * v1 (a56fcd04c0) shipped 3-selector form with `var(--color-primary,
 * #0d6efd) !important` — Tier-1 source-pin + Tier-2 served-mirror +
 * Tier-2 served-bundle all green. Designer's Tier-3 runtime probe at
 * fresh /shop load measured cart badge STILL salmon despite served-bundle
 * green. Cascade diagnostic: v1 selectors landed at (0,2,0) specificity —
 * same tier as the competing app.css rule `.btn.btn-outline,
 * .btn.btn-outline-primary, .btn.btn-outline-secondary { ... !important
 * = transparent }`; source-order tiebreak DID NOT favour public-touch.css
 * as expected (root cause unconfirmed; AI-866d separate root-cause
 * investigation candidate exploring the `--mw-primary-color: #f4a261`
 * token at app.css :root).
 *
 * v2 fix shape (designer's Path B — surgical specificity bump):
 *   .js-shopping-cart-quantity.btn.btn-outline-primary,
 *   .btn-shopping-cart > span.btn.btn-outline-primary {
 *       background-color: #0d6efd !important;
 *       color: #fff !important;
 *       border-color: #0d6efd !important;
 *   }
 *
 * Changes from v1: (a) added `.btn.btn-outline-primary` to BOTH selectors
 * to raise specificity to (0,3,0) — beats all current (0,2,0) competitors
 * regardless of source-order shenanigans; (b) dropped `.btn-shopping-cart
 * .badge` selector — no `.badge` child exists in the rendered DOM per the
 * cascade probe; (c) swapped `var(--color-primary, #0d6efd)` to literal
 * `#0d6efd` so token-shadow is ruled out independently. v1's discipline
 * calls (public-modality token convention + class-chain combinator) STAY;
 * only specificity + literal-value adjustments change.
 *
 * NOT inside the touch-viewport @media block — defect manifests at BOTH
 * 1440 desktop AND 390 mobile; fix must be viewport-agnostic.
 *
 * Pin-evolution discipline: this test file pin-evolves in place per the
 * LESSONS rule (v1 pins replaced with v2 pins; NO parallel test). v1 task-id
 * `task-2026-05-18-2747f0` retained — same task carries CHANGE absorption.
 *
 * Acceptance gates:
 *   - Tier-1 source-pin: v2 rule + hover variant in public-touch.css
 *   - Tier-2 served-mirror: src + public/ byte-identical
 *   - Tier-3 runtime (designer): getComputedStyle().backgroundColor =
 *     'rgb(13, 110, 253)' on cart-counter badge at fresh /shop load
 *     BEFORE marking Done — MANDATORY for salmon-cascade family ships
 *     post-AI-866 v1's missed-runtime-probe lesson.
 *
 * 4-group structure: A = v2 source-presence (specificity-bumped selectors +
 * literal hex + hover variant + markers); B = served-mirror byte-identity;
 * C = scope discipline (rule OUTSIDE touch-viewport @media; viewport-agnostic);
 * D = back-compat regression sentinels (existing AI-516 cart-badge tap-target
 * rule preserved; sibling AI markers preserved) + v1-shape-absent negative
 * regression guards.
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
    public function ai866_v2_rule_body_carries_specificity_bumped_selectors_and_literal_hex(): void
    {
        $source = $this->srcContents();
        // v2 selectors (specificity bumped to (0,3,0) — beats all (0,2,0)
        // competitors regardless of source-order):
        //   .js-shopping-cart-quantity.btn.btn-outline-primary,
        //   .btn-shopping-cart > span.btn.btn-outline-primary {
        //       background-color: #0d6efd !important;
        //       color: #fff !important;
        //       border-color: #0d6efd !important;
        //   }
        $this->assertStringContainsString(
            '.js-shopping-cart-quantity.btn.btn-outline-primary,',
            $source,
            'AI-866 v2 must include .js-shopping-cart-quantity.btn.btn-outline-primary selector — specificity bump to (0,3,0).'
        );
        $this->assertStringContainsString(
            '.btn-shopping-cart > span.btn.btn-outline-primary {',
            $source,
            'AI-866 v2 must include .btn-shopping-cart > span.btn.btn-outline-primary selector — specificity bump to (0,3,0).'
        );
        // Properties — literal #0d6efd (NOT var() — token-shadow ruled out
        // independently of any future AI-866d diagnostic).
        $this->assertMatchesRegularExpression(
            '/background-color:\s*#0d6efd\s*!important;/',
            $source,
            'AI-866 v2 must carry background-color: #0d6efd !important (literal hex, NOT var() — token-shadow ruled out).'
        );
        $this->assertMatchesRegularExpression(
            '/color:\s*#fff\s*!important;/',
            $source,
            'AI-866 v2 must carry color: #fff !important for WCAG contrast against brand-blue bg.'
        );
        $this->assertMatchesRegularExpression(
            '/border-color:\s*#0d6efd\s*!important;/',
            $source,
            'AI-866 v2 must carry border-color: #0d6efd !important — defeats Bootstrap btn-outline-primary salmon border.'
        );
    }

    #[Test]
    public function ai866_v2_hover_variant_specificity_bumped(): void
    {
        $source = $this->srcContents();
        // v2 hover selectors carry same (0,3,0) specificity bump.
        $this->assertStringContainsString(
            '.js-shopping-cart-quantity.btn.btn-outline-primary:hover,',
            $source,
            'AI-866 v2 hover must include the specificity-bumped selector chain.'
        );
        $this->assertStringContainsString(
            '.btn-shopping-cart > span.btn.btn-outline-primary:hover {',
            $source,
            'AI-866 v2 hover must include the .btn-shopping-cart specificity-bumped selector.'
        );
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
    public function served_mirror_carries_ai866_v2_rule(): void
    {
        $served = (string) file_get_contents($this->servedMirrorPath());
        $this->assertStringContainsString('task-2026-05-18-2747f0', $served);
        $this->assertStringContainsString('.js-shopping-cart-quantity.btn.btn-outline-primary,', $served);
        $this->assertStringContainsString('.btn-shopping-cart > span.btn.btn-outline-primary {', $served);
        $this->assertStringContainsString('background-color: #0d6efd !important;', $served);
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

    // ─────────────────────────────────────────────────────────────────────
    // v1-shape-absent negative regression guards (added at v2 absorption)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function v1_low_specificity_three_selector_block_is_absent(): void
    {
        // Comment-stripped slice (selector-self-match guard, 22+ recurrences).
        $stripped = $this->stripCssComments($this->srcContents());
        // v1 carried a 3-selector list at (0,2,0):
        //   .js-shopping-cart-quantity,
        //   .btn-shopping-cart .badge,
        //   .btn-shopping-cart > span.btn { ... }
        // The `.btn-shopping-cart .badge` selector specifically distinguished v1
        // from v2 — DOM has no .badge child per the cascade probe + Path B
        // explicitly drops it. If this selector reappears, v1 has crept back.
        $this->assertStringNotContainsString(
            '.btn-shopping-cart .badge',
            $stripped,
            'AI-866 v1 .btn-shopping-cart .badge selector must NOT reappear — Path B dropped it (no .badge child in rendered DOM).'
        );
    }

    #[Test]
    public function v1_var_color_primary_token_consumer_is_absent_in_ai866_block(): void
    {
        // Comment-stripped slice — docblock prose mentions `var(--color-primary,
        // #0d6efd)` as the v1 token convention, which is legitimate; only the
        // RULE BODY must NOT carry it (Path B specifies literal #0d6efd).
        $stripped = $this->stripCssComments($this->srcContents());
        // Find the v2 selector list opening + slice to the matching `}` close.
        $ai866Pos = strpos($stripped, '.js-shopping-cart-quantity.btn.btn-outline-primary,');
        $this->assertNotFalse($ai866Pos, 'v2 selector chain must exist before this negative regression guard can run.');
        $closeBracePos = strpos($stripped, '}', $ai866Pos);
        $this->assertNotFalse($closeBracePos);
        $ruleBody = substr($stripped, $ai866Pos, $closeBracePos - $ai866Pos);
        $this->assertStringNotContainsString(
            'var(--color-primary',
            $ruleBody,
            'AI-866 v2 rule body must use literal #0d6efd (NOT var(--color-primary, ...)) — Path B rules out token-shadow.'
        );
    }
}

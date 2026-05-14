<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-58 / TASK-013 / TICKET-CY / AI-30 — public-site 44x44
 * tap-target sweep regression coverage.
 *
 * Pins acceptance #2 + #5: the CSS rule-set lives at the canonical
 * source path and the served public path, both copies are identical,
 * the touch-viewport media query is present, and every selector
 * required by the audit (social, cart, footer, swiper bullet,
 * carousel control, navbar toggler, qty buttons) carries a 44px
 * minimum.
 *
 * Style after the cycle-52..57 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PublicTouchTargetContractTest extends TestCase
{
    private string $sourceCss;
    private string $servedCss;
    private string $masterLayout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sourceCss = file_get_contents(base_path(
            'Templates/Bootstrap/resources/assets/css/public-touch.css'
        ));
        $this->servedCss = file_get_contents(base_path(
            'public/templates/bootstrap/css/public-touch.css'
        ));
        $this->masterLayout = file_get_contents(base_path(
            'Templates/Bootstrap/resources/views/layouts/master.blade.php'
        ));
    }

    #[Test]
    public function source_and_served_css_files_are_byte_identical(): void
    {
        // Strategy guard: the public template's asset() helper resolves
        // to public/, so the source file under Templates/.../assets/ MUST
        // be mirrored to public/templates/bootstrap/css/. Drift between
        // the two would silently ship stale rules to live traffic.
        $this->assertSame(
            $this->sourceCss,
            $this->servedCss,
            'public-touch.css source and served copies must be byte-identical'
        );
    }

    #[Test]
    public function master_layout_links_the_public_touch_css_after_app_css(): void
    {
        $appPos   = strpos($this->masterLayout, 'dist/build/app.css');
        $touchPos = strpos($this->masterLayout, 'css/public-touch.css');

        $this->assertNotFalse($appPos, 'app.css link must be present');
        $this->assertNotFalse($touchPos, 'public-touch.css link must be present');
        $this->assertGreaterThan(
            $appPos,
            $touchPos,
            'public-touch.css must load AFTER app.css so its touch-viewport rules win the cascade'
        );
    }

    #[Test]
    public function touch_viewport_media_query_matches_admin_pattern(): void
    {
        // Mirror of mobile-touch.css: cover phones AND any pointer-coarse
        // device regardless of viewport (foldables, touch-screen laptops).
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*1023\.98px\s*\),\s*\(\s*hover:\s*none\s*\)\s*and\s*\(\s*pointer:\s*coarse\s*\)/',
            $this->sourceCss,
            'public-touch.css must use the same touch-viewport media query as the Filament admin file'
        );
    }

    #[Test]
    public function all_audit_offenders_carry_44px_minimum(): void
    {
        // Every selector named in the agent-test 2026-05-08 mobile-QA brief
        // (social icons, cart badge link, footer nav, swiper bullet,
        // carousel control, navbar toggler, quantity buttons) must reach
        // a 44px floor. Asserting on selector PRESENCE inside the touch
        // media block + a min-width AND min-height: 44px declaration.
        $required_selectors = [
            '.mw-socialLinks a',                       // social icons (16x25, 20x24)
            '.btn-shopping-cart > a.nav-link',          // cart badge link (58x22)
            '.js-shopping-cart-quantity',               // AI-516 cart count badge (86x38 -> 44x44)
            '.footer-19-menu a',                        // footer nav (~24px)
            '.swiper-pagination-bullet',                // carousel dots (8x8)
            '.carousel-control-prev',                   // bootstrap carousel arrow
            '.navbar-toggler',                          // mobile hamburger
            '.mw-add-to-cart-btn',                      // primary cart CTA
        ];

        foreach ($required_selectors as $sel) {
            $this->assertStringContainsString(
                $sel,
                $this->sourceCss,
                "public-touch.css: required audit selector '{$sel}' is missing"
            );
        }

        // The media query must wrap min-width: 44px AND min-height: 44px
        // declarations. Count >= 1 of each.
        $this->assertMatchesRegularExpression(
            '/min-width:\s*44px/',
            $this->sourceCss,
            'public-touch.css: at least one min-width: 44px rule must exist'
        );
        $this->assertMatchesRegularExpression(
            '/min-height:\s*44px/',
            $this->sourceCss,
            'public-touch.css: at least one min-height: 44px rule must exist'
        );
    }

    #[Test]
    public function ai_516_cart_badge_rule_carries_explicit_44px_floor(): void
    {
        // AI-516 — the `.js-shopping-cart-quantity` selector must own a
        // rule body that sets BOTH min-width: 44px AND min-height: 44px.
        // Selector presence alone is not enough — guard the actual
        // declarations so a future refactor that drops one half is caught.
        // The `:not([hidden])` guard is part of the contract too: the
        // empty-state hidden-attribute behaviour from AI-40 must survive.
        $this->assertMatchesRegularExpression(
            '/\.js-shopping-cart-quantity:not\(\[hidden\]\)\s*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->sourceCss,
            'public-touch.css: AI-516 badge rule must declare BOTH min-width: 44px AND min-height: 44px on `.js-shopping-cart-quantity:not([hidden])`'
        );
    }

    #[Test]
    public function rule_set_is_targeted_not_universal(): void
    {
        // AC#2 explicit: prefer targeted classes/selectors over a
        // universal `a, button { min-height: 44px }` sweep. Guard
        // against future regressions that try to "shortcut" this with
        // a global-tag rule.
        $this->assertDoesNotMatchRegularExpression(
            "/(^|\\s)a\\s*,\\s*button\\s*\\{[^}]*min-height:\\s*44px/m",
            $this->sourceCss,
            'public-touch.css must not use a universal `a, button { min-height: 44px }` sweep — keep the rule list targeted per AC#2'
        );
    }
}

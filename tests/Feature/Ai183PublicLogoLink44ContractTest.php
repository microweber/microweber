<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-156 / AI-183 — Public-page Site Logo link below WCAG 2.5.5 /
 * iOS HIG 44×44 floor on mobile.
 *
 * UX-audit P2 finding (agent-test public-page systematic scan at
 * 390×844): logo link rendered at 63×24 across all public pages
 * (home, blog, contact, shop). The logo link is the primary
 * site-home navigation control on mobile but the tappable area
 * was below the floor.
 *
 * Cycle-156 fix (CSS-only) — appended to
 * `packages/frontend-assets/.../microweber/css/default.css`
 * (the cross-template public CSS loaded on every public page via
 * TemplateManager / FrontendController). Inside the existing
 * `@media (max-width: 768px)` AI-166/169 hamburger touch-target
 * block, add a min-width/min-height: 44px floor on the logo `<a>`
 * targeting:
 *   - `.module-logo a` — the live-edit module-type namespace,
 *     present on every template's logo wrapper (`module="logo"`)
 *   - `.mw-big-header-logo a` — Big2-specific wrapper as defensive
 *     duplicate so the rule binds even after a `module-logo`
 *     refactor
 *   - `a.text-2xl.font-semibold` — Tailwind-utility selector that
 *     matches the rendered link directly
 *
 * Use min-height (not height) so a logo with a real <img> still
 * sizes to its natural height — we just guarantee a minimum 44px
 * tappable area. inline-flex centering keeps the visible logo
 * text/image vertically centered inside the larger box.
 */
class Ai183PublicLogoLink44ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_183_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');
        $this->assertStringContainsString('AI-183', $src,
            'default.css MUST carry the AI-183 anchor inline so the '
            . 'cycle-156 logo-link floor is discoverable at refactor time.');
        $this->assertStringContainsString('cycle-156', $src,
            'default.css MUST carry the cycle-156 anchor inline.');
    }

    #[Test]
    public function source_pins_logo_link_44_min(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // The cross-template `.module-logo a` selector must hit
        // min-width/min-height: 44px on mobile.
        $this->assertMatchesRegularExpression(
            '/\.module-logo\s+a[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-width:44px !important on '
            . '.module-logo a so the cycle-156 floor wins against any '
            . 'template-specific logo-link sizing.'
        );
        $this->assertMatchesRegularExpression(
            '/\.module-logo\s+a[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-height:44px !important on '
            . '.module-logo a — that\'s the AI-183 ticket\'s suggested '
            . 'fix since the original 63x24 rendered link\'s height (24) '
            . 'was the more egregious dimension.'
        );
    }

    #[Test]
    public function source_includes_big2_namespace_and_tailwind_selector(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');
        // Defensive duplicate selectors so the rule binds across
        // refactors and across the Big2 template specifically.
        $this->assertMatchesRegularExpression(
            '/\.mw-big-header-logo\s+a/m',
            $src,
            'default.css MUST include the Big2-specific '
            . '.mw-big-header-logo a selector as defensive duplicate '
            . 'against any module-logo namespace refactor.'
        );
        $this->assertMatchesRegularExpression(
            '/a\.text-2xl\.font-semibold/m',
            $src,
            'default.css MUST include the Tailwind-utility '
            . 'a.text-2xl.font-semibold selector — that\'s the rendered '
            . 'link class agent-test reported.'
        );
    }

    #[Test]
    public function rule_is_inside_max_width_768_block(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // Find the AI-183 anchor and check the closest preceding
        // @media block declares `(max-width: 768px)` so desktop
        // density is preserved.
        $anchorPos = strpos($src, 'AI-183');
        $this->assertNotFalse($anchorPos, 'AI-183 anchor must be present.');

        $rulePos = strpos($src, '.module-logo a', $anchorPos);
        $this->assertNotFalse($rulePos, 'AI-183 rule must follow the anchor.');

        $beforeRule = substr($src, 0, $rulePos);
        $lastMediaPos = strrpos($beforeRule, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-183 rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $lastMediaPos, 60);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-183 rule MUST be inside `@media (max-width: 768px)` so '
            . 'desktop density is preserved (logo at desktop size keeps '
            . 'its natural inline layout).');
    }

    #[Test]
    public function built_bundle_carries_logo_44_floor(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/default.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built default.css missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson.
        $this->assertStringContainsString('.module-logo a', $built,
            'Built default.css MUST contain the .module-logo a selector. '
            . 'If missing, the bundle was not rebuilt after the source edit.');
        $this->assertMatchesRegularExpression(
            '/\.module-logo\s+a[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $built,
            'Built default.css MUST contain min-width:44px !important on '
            . '.module-logo a.'
        );
    }
}

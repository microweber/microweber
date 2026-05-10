<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-154 / AI-180 — Secondary toolbar actions (Insert layout /
 * Template settings / Design / Quick AI edit / Advanced) 0×0 on
 * mobile (P2).
 *
 * UX-audit P2 finding (agent-test mobile-ux-audit-report.md):
 * "Secondary toolbar actions have 0×0px buttons" at 390×844.
 *
 * Reproduction at 390×844: the 5 right-rail buttons live inside
 * `.mw-live-edit-right-sidebar-wrapper`. The cycle-N
 * `packages/frontend-assets/.../ui/css/index.css` `@media (max-
 * width: 767px)` block contained `.mw-live-edit-right-sidebar-
 * wrapper { display: none !important }` — completely hiding the
 * rail (and its 5 buttons) on mobile. Buttons were technically
 * `display: flex` but their parent was `display: none`, so they
 * collapsed to 0×0 and were unreachable on mobile.
 *
 * Cycle-154 fix: comment out the `display: none !important` rule.
 * Rationale:
 *   - The rail is `position: absolute` (overlays the canvas, doesn't
 *     displace content), so showing it on mobile is layout-safe.
 *   - After the cycle-153 / AI-179 fix, every button inside the rail
 *     is 44×44 — meets WCAG 2.5.5 / iOS HIG.
 *   - On a 390px viewport, the 50px-wide rail at the right edge
 *     covers ~13% of the canvas — acceptable trade-off for
 *     restoring access to 5 useful tools (Insert layout / Template
 *     settings / Design / Quick AI edit / Advanced).
 */
class Ai180RightRailVisibleMobileContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_180_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/css/index.css');
        $this->assertStringContainsString('AI-180', $src,
            'index.css MUST carry the AI-180 anchor inline so the '
            . 'cycle-154 rail-visibility fix is discoverable at '
            . 'refactor time.');
        $this->assertStringContainsString('cycle-154', $src,
            'index.css MUST carry the cycle-154 anchor inline.');
    }

    #[Test]
    public function source_does_not_actively_hide_right_rail_on_mobile(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/css/index.css');

        // The active hide rule must NOT be present. We allow the
        // commented-out version inside a /* ... */ block (preserved as
        // history), but the LIVE rule is forbidden.
        // Strip block comments before checking.
        $stripped = preg_replace('#/\*[\s\S]*?\*/#', '', $src);
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\s*\{\s*display:\s*none/m',
            $stripped,
            'index.css MUST NOT contain an active '
            . '`.mw-live-edit-right-sidebar-wrapper { display: none }` '
            . 'rule — that hide rule was the AI-180 P2 root cause and '
            . 'cycle-154 commented it out.'
        );
    }

    #[Test]
    public function commented_out_rule_preserved_with_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/css/index.css');

        // The commented-out block (preserving history near the AI-180
        // anchor) MUST still be present so future readers see WHY the
        // rule went away, not just that it's missing.
        $this->assertMatchesRegularExpression(
            '/AI-180[\s\S]{0,1500}\/\*[\s\S]{0,400}\.mw-live-edit-right-sidebar-wrapper\s*\{\s*display:\s*none[\s\S]{0,400}\*\//m',
            $src,
            'index.css MUST keep the commented-out hide rule near the '
            . 'AI-180 anchor so future readers see the original rule + '
            . 'why it was retired.'
        );
    }

    #[Test]
    public function built_bundle_does_not_carry_active_hide_rule(): void
    {
        $bundles = [
            'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.css',
            'public/vendor/microweber-packages/frontend-assets/build/liveedit.css',
        ];
        $checked = 0;
        foreach ($bundles as $rel) {
            $path = base_path($rel);
            if (!file_exists($path)) continue;
            $checked++;
            $built = file_get_contents($path);
            $this->assertDoesNotMatchRegularExpression(
                '/\.mw-live-edit-right-sidebar-wrapper\{display:none/',
                $built,
                "Built bundle {$rel} MUST NOT contain the minified "
                . '`.mw-live-edit-right-sidebar-wrapper{display:none` '
                . 'rule. If present, the bundle was not rebuilt after the '
                . 'source edit (cycle-142 lesson).'
            );
        }
        $this->assertGreaterThan(0, $checked,
            'At least one frontend-assets CSS bundle should exist; if '
            . 'none do, the build pipeline may be misconfigured.');
    }
}

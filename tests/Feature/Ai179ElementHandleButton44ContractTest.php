<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-153 / AI-179 — Canvas element-handle buttons 32×32 < WCAG 44×44.
 *
 * UX-audit P1 finding (agent-test mobile-ux-audit-report.md):
 * "Element handle buttons 32×32px and clipped (some at negative Y
 *  coordinates)" at 390×844.
 *
 * The handle floats over the canvas iframe whenever a user
 * selects/hovers an editable element. The .mw-le-handle-menu-button
 * tiles are pinned to 32x32 by handles.scss:134 and :297 — fine for
 * desktop pointer precision, hostile to touch.
 *
 * Cycle-153 fix: bump every visible handle-menu button to >=44x44 on
 * touch-capable contexts (`@media (pointer: coarse)`) AND on small
 * viewports (`(max-width: 800px)`) so the rule fires whether the
 * environment is a true touch device, a narrow desktop window, or a
 * dev-tools viewport-emulation. Margin between buttons increased from
 * 2px to 4px so the larger tap targets don't merge into one fat
 * strip.
 *
 * The (negative-Y / clipped) part of the audit is a separate
 * positioning concern (JS-side, in element-handle.js). The cycle-N
 * `@media (max-width: 800px)` rule already side-steps the clipping
 * for the layout-root menu by converting it to `position: sticky;
 * bottom: 0;`. The remaining clip case (regular element-root menu
 * near top of canvas) is documented as phase-2 follow-up — needs
 * JS-side clamping, not pure CSS.
 */
class Ai179ElementHandleButton44ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_179_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/scss/handles.scss');
        $this->assertStringContainsString('AI-179', $src,
            'handles.scss MUST carry the AI-179 anchor inline.');
        $this->assertStringContainsString('cycle-153', $src,
            'handles.scss MUST carry the cycle-153 anchor inline.');
    }

    #[Test]
    public function source_promotes_handle_menu_button_to_44x44(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/scss/handles.scss');

        // The fix MUST set min-width/min-height: 44px on the handle-
        // menu button so the cycle-N 32x32 rule loses to specificity.
        $this->assertMatchesRegularExpression(
            '/\.mw-handle-item\s+\.mw-handle-item-menus-holder\s+\.mw-le-handle-menu-button[\s\S]{0,500}min-width:\s*44px/m',
            $src,
            'handles.scss MUST pin min-width:44px on the high-specificity '
            . '.mw-handle-item .mw-handle-item-menus-holder '
            . '.mw-le-handle-menu-button rule so it wins the cascade '
            . 'against the 32x32 rule on line 134.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-handle-item\s+\.mw-handle-item-menus-holder\s+\.mw-le-handle-menu-button[\s\S]{0,500}min-height:\s*44px/m',
            $src,
            'handles.scss MUST pin min-height:44px on the high-specificity rule.'
        );

        // Lower-specificity rule (line 297) — direct .mw-le-handle-
        // menu-buttons child — must also be promoted.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-handle-menu-buttons\s*>\s*\.mw-le-handle-menu-button[\s\S]{0,500}min-width:\s*44px/m',
            $src,
            'handles.scss MUST also pin min-width:44px on the '
            . '.mw-le-handle-menu-buttons > .mw-le-handle-menu-button '
            . 'rule so the fix is robust to ancestor refactors.'
        );
    }

    #[Test]
    public function rule_fires_on_mobile_or_touch(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/scss/handles.scss');

        // Find the AI-179 anchor and check the closest preceding @media
        // block declares both `(max-width: 800px)` AND `(pointer: coarse)`.
        $anchorPos = strpos($src, 'AI-179');
        $this->assertNotFalse($anchorPos, 'AI-179 anchor must be present.');

        // Find the first @media after the anchor (the rule lives there)
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'AI-179 rule must sit inside an @media block.');

        // Read the media-query line
        $mediaLine = substr($src, $mediaPos, 80);
        $this->assertStringContainsString('max-width: 800px', $mediaLine,
            'AI-179 @media MUST include `(max-width: 800px)` so the '
            . '44x44 floor applies on narrow viewports.');
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'AI-179 @media MUST include `(pointer: coarse)` so the '
            . '44x44 floor applies on real touch devices regardless of '
            . 'viewport width.');
    }

    #[Test]
    public function menus_holder_grows_to_fit_44_buttons(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/scss/handles.scss');
        // The menus-holder shipped at $handleholderHeight px tall (line 125,
        // 38px). With 44x44 buttons it must grow vertically — pin
        // min-height: 56px so the buttons aren't clipped.
        $this->assertMatchesRegularExpression(
            '/\.mw-handle-item\s+\.mw-handle-item-menus-holder[\s\S]{0,500}min-height:\s*56px/m',
            $src,
            'handles.scss MUST grow .mw-handle-item-menus-holder to '
            . 'min-height:56px on mobile so the new 44x44 buttons fit '
            . 'with chrome (44 + 12px padding).'
        );
    }

    #[Test]
    public function built_bundle_carries_44_promotion(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/liveedit.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built liveedit.css missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson: load-bearing pieces MUST
        // appear in the built bundle.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-handle-menu-buttons\s*>\s*\.mw-le-handle-menu-button[\s\S]{0,500}min-width:\s*44px/m',
            $built,
            'Built liveedit.css MUST contain the 44x44 promotion. If '
            . 'missing, the bundle was not rebuilt after the source edit.'
        );
        // Minifier may strip whitespace inside the media query, so allow
        // either `pointer: coarse` or `pointer:coarse`.
        $this->assertMatchesRegularExpression(
            '/pointer:\s*coarse/',
            $built,
            'Built liveedit.css MUST contain the (pointer: coarse) '
            . 'media query so touch devices get the 44x44 floor.'
        );
    }
}

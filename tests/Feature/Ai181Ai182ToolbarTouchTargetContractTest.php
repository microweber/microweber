<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-155 / AI-181 + AI-182 — Two toolbar buttons below WCAG 2.5.5
 * / iOS HIG 44×44 touch-target floor.
 *
 * UX-audit P2 findings (agent-test systematic mobile scan at 390×844):
 *
 * AI-181 — VIEW button `#mw-page-set-preview-mode`
 *          (`.live-edit-toolbar-buttons-view`) measured 34×38. Bootstrap
 *          `.btn` defaults pad to ~38px tall — below the floor.
 *
 * AI-182 — Toolbar user-menu hamburger `#toolbar-user-menu-button`
 *          (`.mw-le-hamburger`) measured 24×17 — the cycle-N
 *          `gui.css:118` rule pins width: 24px / height: 17px (the
 *          visual bar size). Three inner `<span>` bars are absolute-
 *          positioned, so the parent's box can grow without
 *          disturbing the bar layout.
 *
 * Cycle-155 fix (CSS-only, scoped under .mw-admin-live-edit-page +
 * .mw-live-edit-page so non-live-edit Filament admin chrome is
 * untouched). Both buttons bumped to min-width/min-height: 44px on
 * `(max-width: 768px), (pointer: coarse)` so the floor applies on
 * mobile viewports AND any touch device regardless of width.
 */
class Ai181Ai182ToolbarTouchTargetContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_181_and_ai_182_anchors(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');
        $this->assertStringContainsString('AI-181', $src,
            'live-edit-mobile.css MUST carry the AI-181 anchor inline.');
        $this->assertStringContainsString('AI-182', $src,
            'live-edit-mobile.css MUST carry the AI-182 anchor inline.');
        $this->assertStringContainsString('cycle-155', $src,
            'live-edit-mobile.css MUST carry the cycle-155 anchor inline.');
    }

    #[Test]
    public function source_promotes_view_button_to_44_min(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The VIEW button (#mw-page-set-preview-mode) must hit min-
        // width/min-height: 44px on mobile/touch.
        $this->assertMatchesRegularExpression(
            '/#mw-page-set-preview-mode[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-width:44px !important on '
            . '#mw-page-set-preview-mode so the cycle-155 floor wins '
            . 'against Bootstrap `.btn` defaults.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-page-set-preview-mode[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-height:44px !important on '
            . '#mw-page-set-preview-mode.'
        );
    }

    #[Test]
    public function source_promotes_user_menu_hamburger_to_44_min(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // #toolbar-user-menu-button (.mw-le-hamburger) must hit
        // min-width/min-height: 44px so the 24x17 visual-bar parent
        // box grows without disturbing the inner absolute-positioned
        // span bars.
        $this->assertMatchesRegularExpression(
            '/#toolbar-user-menu-button[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-width:44px !important on '
            . '#toolbar-user-menu-button so the parent box grows past '
            . 'the cycle-N gui.css 24x17 visual-bar size.'
        );
        $this->assertMatchesRegularExpression(
            '/#toolbar-user-menu-button[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-height:44px !important on '
            . '#toolbar-user-menu-button.'
        );
    }

    #[Test]
    public function rule_fires_on_mobile_or_touch(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Find the AI-181/AI-182 cycle-155 anchor and the first
        // @media block AFTER it (the comment above the rule mentions
        // the selector too, so we can't search backwards from the
        // selector — we look forward from the anchor instead).
        $anchorPos = strpos($src, 'cycle-155');
        $this->assertNotFalse($anchorPos, 'cycle-155 anchor must be present.');

        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'AI-181 rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $mediaPos, 80);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-181/AI-182 @media MUST include `(max-width: 768px)` so '
            . 'the floor applies on narrow viewports.');
        $this->assertStringContainsString('pointer: coarse', $mediaQueryLine,
            'AI-181/AI-182 @media MUST include `(pointer: coarse)` so '
            . 'the floor applies on real touch devices regardless of '
            . 'viewport width.');
    }

    #[Test]
    public function rules_use_admin_live_edit_page_scope(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Both rules MUST use .mw-admin-live-edit-page scope (matches
        // the existing toolbar scope rules) so non-live-edit Filament
        // admin pages keep their normal touch-target sizing.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+#mw-page-set-preview-mode/m',
            $src,
            'live-edit-mobile.css MUST scope the AI-181 VIEW button '
            . 'rule under .mw-admin-live-edit-page.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+#toolbar-user-menu-button/m',
            $src,
            'live-edit-mobile.css MUST scope the AI-182 user-menu '
            . 'rule under .mw-admin-live-edit-page.'
        );
    }

    #[Test]
    public function built_bundle_carries_44_promotion(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson.
        $this->assertStringContainsString('#mw-page-set-preview-mode', $built,
            'Built bundle MUST contain the AI-181 VIEW-button rule. '
            . 'If missing, the bundle was not rebuilt after the source edit.');
        $this->assertStringContainsString('#toolbar-user-menu-button', $built,
            'Built bundle MUST contain the AI-182 user-menu rule.');
        $this->assertMatchesRegularExpression(
            '/#mw-page-set-preview-mode[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $built,
            'Built bundle MUST contain min-width:44px !important on '
            . '#mw-page-set-preview-mode.'
        );
        $this->assertMatchesRegularExpression(
            '/#toolbar-user-menu-button[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $built,
            'Built bundle MUST contain min-width:44px !important on '
            . '#toolbar-user-menu-button.'
        );
    }
}

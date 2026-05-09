<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-143 / AI-150 — corrected live-edit-mobile.css selectors.
 *
 * Tester re-test of cycle-142 import fix exposed two latent bugs in
 * the cycle-126 rules that had been DARK in production for 16 cycles
 * and only became visible once the import was added:
 *
 *   1. AI-141 toolbar text-labels: the original selector was
 *      `.live-edit-toolbar .fi-icon-btn` — `.fi-icon-btn` is a
 *      Filament class, but the live-edit toolbar buttons are Vue
 *      components that render with `.btn-icon.live-edit-toolbar-
 *      buttons.mw-toolbar-icon-btn` (see SettingsCustomize.vue
 *      line 346 onward). The selector NEVER matched, so the
 *      ::after content:attr(aria-label) tooltip never rendered.
 *      Cycle-143 fix: swap to the correct selector chain.
 *
 *   2. AI-140 sidebar collapse to 56px: the original rule fought
 *      with the inline-style drawer pattern in
 *      src/MicroweberPackages/Filament/resources/views/filament/
 *      components/layout/live-edit.blade.php (lines 21-35) which
 *      positions the sidebar `position:absolute;
 *      transform:translateX(-100%)` and slides it in via
 *      `.fi-sidebar.active`. The width:56px rule was redundant
 *      when the drawer was closed (already off-screen) and
 *      ignored when active (drawer fills viewport on mobile).
 *      Cycle-143 fix: remove the width:56px declaration; instead
 *      clamp `.fi-sidebar.active` width to
 *      `min(280px, calc(100vw - 60px))` so the drawer is usable
 *      on mobile, and force the back-to-admin toggle button
 *      visible so users have a tappable hamburger affordance.
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai* — source-grep
 * + functional pin against the BUILT bundle.
 */
class Ai150LiveEditMobileSelectorCorrectionContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function toolbar_text_label_rule_uses_correct_vue_component_selector(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Corrected selector MUST target the Vue-component classes
        // that the live-edit toolbar actually renders with.
        $this->assertStringContainsString(
            '.mw-live-edit-page .live-edit-toolbar-buttons.mw-toolbar-icon-btn',
            $src,
            'live-edit-mobile.css MUST target .live-edit-toolbar-buttons'
            . '.mw-toolbar-icon-btn (the Vue-component class chain). The '
            . 'cycle-126 selector .live-edit-toolbar .fi-icon-btn never '
            . 'matched anything because the live-edit toolbar is built '
            . 'in Vue, not Filament.'
        );

        $this->assertStringContainsString(
            '.mw-live-edit-page .btn-icon.live-edit-toolbar-buttons',
            $src,
            'live-edit-mobile.css MUST also target the .btn-icon variant '
            . '(some toolbar buttons render with both class chains).'
        );
    }

    #[Test]
    public function toolbar_text_label_rule_emits_aria_label_via_pseudo(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The ::after pseudo MUST emit content: attr(aria-label) so
        // every button gets the auto-label without DOM changes.
        $this->assertMatchesRegularExpression(
            '/\.live-edit-toolbar-buttons\.mw-toolbar-icon-btn::after[\s\S]{0,200}content:\s*attr\(aria-label\)/m',
            $src,
            'live-edit-mobile.css MUST emit content: attr(aria-label) on '
            . 'the corrected toolbar selector so the cycle-126 AI-141 '
            . 'auto-label finally renders.'
        );
    }

    #[Test]
    public function sidebar_active_state_is_width_clamped_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The corrected AI-140 fix MUST clamp .fi-sidebar.active width
        // on mobile so the drawer doesn't take the full viewport.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.fi-sidebar\.active[\s\S]{0,200}width:\s*min\(280px,\s*calc\(100vw\s*-\s*60px\)\)\s*!important/m',
            $src,
            'live-edit-mobile.css MUST clamp .fi-sidebar.active width to '
            . 'min(280px, calc(100vw - 60px)) on mobile so the drawer '
            . 'leaves at least 60px of viewport visible for the close '
            . 'overlay.'
        );
    }

    #[Test]
    public function legacy_56px_width_rule_was_removed(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The cycle-126 redundant `.mw-live-edit-page .fi-sidebar
        // { width: 56px !important }` rule MUST be gone — it fought
        // with the slide-out drawer pattern. A regression that
        // reintroduces it would re-trigger the AI-150 symptom.
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-live-edit-page\s+\.fi-sidebar\s*\{[\s\S]{0,200}width:\s*56px/m',
            $src,
            'live-edit-mobile.css MUST NOT re-introduce the width:56px '
            . 'rule on bare .fi-sidebar — that rule conflicts with the '
            . 'live-edit slide-out drawer pattern. The corrected fix '
            . 'targets .fi-sidebar.active with a clamped width instead.'
        );
    }

    #[Test]
    public function back_to_admin_toggle_is_visible_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The mw-live-edit-toolbar-back-to-admin-link is the existing
        // hamburger toggle wired up in live-edit.blade.php; force it
        // visible on mobile so users have a tappable affordance to
        // open/close the drawer.
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link[\s\S]{0,200}display:\s*inline-flex\s*!important/m',
            $src,
            'live-edit-mobile.css MUST force #mw-live-edit-toolbar-back-'
            . 'to-admin-link visible on mobile so users have a tappable '
            . 'hamburger affordance for the sidebar drawer.'
        );

        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link[\s\S]{0,200}min-height:\s*44px/m',
            $src,
            'The visible toggle MUST also satisfy the WCAG 2.5.5 '
            . '44x44 touch-target floor.'
        );
    }

    #[Test]
    public function ai_150_anchor_documents_the_regression_inline(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The cycle-143 anchor + AI-150 reference MUST stay inline so
        // a future maintainer can trace the regression class.
        $this->assertStringContainsString(
            'AI-150',
            $src,
            'live-edit-mobile.css MUST carry the AI-150 anchor inline so '
            . 'the cycle-143 corrigendum is discoverable at refactor time.'
        );
        $this->assertStringContainsString(
            'cycle-143',
            $src,
            'live-edit-mobile.css MUST carry the cycle-143 anchor inline.'
        );
    }

    #[Test]
    public function built_bundle_now_carries_the_corrected_selectors(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping the production-CSS pin. "
                . 'Run `npm run build` in packages/microweber-filament-theme/ to refresh.'
            );
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '.live-edit-toolbar-buttons.mw-toolbar-icon-btn',
            $built,
            'Built CSS bundle MUST contain the corrected toolbar selector '
            . 'so AI-141 auto-labels actually render. If this fails, '
            . 'live-edit-mobile.css change did not propagate to the bundle.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.fi-sidebar\.active[^{]*\{[^}]*width:\s*min\(280px/m',
            $built,
            'Built CSS bundle MUST contain the .fi-sidebar.active width '
            . 'clamp so the drawer is usable on mobile.'
        );
    }
}

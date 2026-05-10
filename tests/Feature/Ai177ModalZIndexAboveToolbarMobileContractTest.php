<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-149 / AI-177 — live-edit toolbar leaks through Filament modals
 * on mobile.
 *
 * User report (task-2026-05-10-2f4c73): "there are bobile issses in
 * live edit on eitiyng them odule settgis update pic, etc, pls fix"
 * — mobile issues when editing module settings (image, etc.).
 *
 * Reproduction at 390x844 with the SocialLinks module-settings
 * slide-over open: live-edit `#toolbar` (z:999, position:relative)
 * + its inner `.mw-live-edit-search-dropdown` (z:1000) win the
 * stacking fight against `.fi-modal-window-ctn` (z:200, fixed) and
 * the toolbar buttons + tablet-overflow arrow controls visually leak
 * through the empty band between the modal heading and the form
 * iframe inside the slide-over. The close-overlay (also z:200) loses
 * too — toolbar covers the dark backdrop the user expects.
 *
 * Cycle-149 fix: scoped CSS-only z-index promotion at <=768px so
 * `.fi-modal-window-ctn` AND `.fi-modal-close-overlay` are bumped
 * above the toolbar's 1000 ceiling. Selector matches the existing
 * `.mw-admin-live-edit-page .fi-modal > .fi-modal-window-ctn`
 * specificity (0,3,0) in `live-edit-classes.css` so we win the
 * cascade tie via source order — `live-edit-mobile.css` is @import'd
 * AFTER `live-edit-classes.css` in `index.css`.
 *
 * Desktop is untouched: at >=769px the slide-over is a right-side
 * strip and the toolbar stays interactive to the LEFT of the panel.
 */
class Ai177ModalZIndexAboveToolbarMobileContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_177_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertStringContainsString('AI-177', $src,
            'live-edit-mobile.css MUST carry the AI-177 anchor inline so '
            . 'the cycle-149 z-index fix is discoverable at refactor time.');
        $this->assertStringContainsString('cycle-149', $src,
            'live-edit-mobile.css MUST carry the cycle-149 anchor inline.');
    }

    #[Test]
    public function source_promotes_modal_window_ctn_z_index(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Must use the .mw-admin-live-edit-page selector to match the
        // (0,3,0) specificity of the existing z=200 rule in
        // live-edit-classes.css. A weaker selector (e.g. just
        // .mw-live-edit-page .fi-modal-window-ctn at 0,2,0) loses the
        // specificity fight and the !important promotion has no effect.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.fi-modal\s*>\s*\.fi-modal-window-ctn[\s\S]{0,400}z-index:\s*1100\s*!important/m',
            $src,
            'live-edit-mobile.css MUST promote `.mw-admin-live-edit-page '
            . '.fi-modal > .fi-modal-window-ctn` z-index to 1100 !important '
            . 'on mobile so the modal-stacking-context wins over the '
            . 'live-edit toolbar (z:999) + its search dropdown (z:1000).'
        );
    }

    #[Test]
    public function source_promotes_close_overlay_z_index(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.fi-modal\s*>\s*\.fi-modal-close-overlay[\s\S]{0,400}z-index:\s*1100\s*!important/m',
            $src,
            'live-edit-mobile.css MUST promote `.mw-admin-live-edit-page '
            . '.fi-modal > .fi-modal-close-overlay` z-index to 1100 !important '
            . 'so the dark backdrop tap-to-dismiss target is reachable on '
            . 'mobile (toolbar at z:999 was previously covering it).'
        );
    }

    #[Test]
    public function rule_is_inside_max_width_768_block(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $anchorPos = strpos($src, 'AI-177');
        $this->assertNotFalse($anchorPos, 'AI-177 anchor must be present.');

        // Find the next .mw-admin-live-edit-page rule after the anchor
        $rulePos = strpos($src, '.mw-admin-live-edit-page', $anchorPos);
        $this->assertNotFalse($rulePos, 'AI-177 rule must follow the anchor.');

        $beforeRule = substr($src, 0, $rulePos);
        $lastMediaPos = strrpos($beforeRule, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-177 rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $lastMediaPos, 60);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-177 rule MUST be inside `@media (max-width: 768px)` so it '
            . 'does NOT fire on desktop where slide-over is a right-side '
            . 'strip and the toolbar must stay interactive to the LEFT.');
    }

    #[Test]
    public function built_bundle_carries_z_index_promotion(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping production-CSS pin."
            );
        }
        $built = file_get_contents($path);

        // Functional pin: the z-index promotion MUST appear in the BUILT
        // CSS bundle (not just the source). Per cycle-142 lesson: source
        // landings without bundle rebuild are a recurring regression.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.fi-modal\s*>\s*\.fi-modal-window-ctn[\s\S]{0,400}z-index:\s*1100\s*!important/m',
            $built,
            'Built bundle MUST contain the modal-window-ctn z-index '
            . 'promotion. If missing, the bundle was not rebuilt after '
            . 'the source edit.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.fi-modal\s*>\s*\.fi-modal-close-overlay[\s\S]{0,400}z-index:\s*1100\s*!important/m',
            $built,
            'Built bundle MUST contain the close-overlay z-index promotion.'
        );
    }

    #[Test]
    public function existing_z_index_200_rule_still_present_for_desktop(): void
    {
        // Sanity check: the cycle-149 fix must NOT remove the existing
        // desktop z-index:200 rule in live-edit-classes.css. We just
        // override it on mobile via a media-query rule with equal
        // specificity but later cascade order.
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css');

        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.fi-modal\s*>\s*\.fi-modal-window-ctn\s*\{\s*z-index:\s*200\s*!important/m',
            $src,
            'live-edit-classes.css MUST still carry the desktop '
            . 'z-index:200 rule for `.fi-modal > .fi-modal-window-ctn` — '
            . 'cycle-149 only adds a mobile override; desktop z-stack is '
            . 'unchanged so the toolbar remains interactive to the left '
            . 'of the right-anchored slide-over.'
        );
    }
}

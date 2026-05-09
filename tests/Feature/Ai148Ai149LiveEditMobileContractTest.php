<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-142 / AI-148 + AI-149 — Live Edit mobile UX contract.
 *
 * Pins two related fixes that landed together:
 *
 *   - AI-148 (Picture/Image not discoverable in mobile module picker):
 *     AdminLiveEditPage::addContentAction() now lists a 5th entry
 *     "New Image" that opens the Media Library upload page. Novice
 *     mobile users searching for "picture" or "image" in the picker
 *     finally find a tappable affordance instead of dead-ending.
 *
 *   - AI-149 (sidebar permanently open on mobile, no toggle):
 *     ROOT CAUSE was an oversight in cycle-126 — `live-edit-mobile.css`
 *     was created with the AI-140 sidebar-collapse + AI-141 toolbar
 *     text-labels rules and extended in cycle-127 with the AI-143 /
 *     AI-144 modal viewport-fit rules, but the file was NEVER added
 *     to the import chain in `index.css`. As a result every one of
 *     those four fixes has been DARK in the built bundle since they
 *     were authored. Cycle-142 adds the import; the rebuilt bundle
 *     now carries the rules.
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai* — source-grep
 * assertions that catch regressions at refactor time.
 */
class Ai148Ai149LiveEditMobileContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    // -----------------------------------------------------------------
    // AI-148 — Picture / Image discoverable in mobile module picker
    // -----------------------------------------------------------------

    #[Test]
    public function add_content_picker_lists_a_new_image_entry(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertMatchesRegularExpression(
            '/[\'"]title[\'"]\s*=>\s*[\'"]New Image[\'"]/',
            $src,
            'AdminLiveEditPage::addContentAction MUST include a "New Image" '
            . 'entry so novice mobile users searching for "picture" / "image" '
            . 'in the Add-content picker find a tappable affordance.'
        );

        $this->assertMatchesRegularExpression(
            '/[\'"]action[\'"]\s*=>\s*[\'"]addImageAction[\'"]/',
            $src,
            '"New Image" picker entry MUST route to addImageAction so the '
            . 'tap actually opens the Media Library upload flow.'
        );
    }

    #[Test]
    public function add_image_action_method_exists_and_redirects_to_media_library(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertMatchesRegularExpression(
            '/public function addImageAction\(\)\s*:\s*Action/',
            $src,
            'AdminLiveEditPage MUST declare a public addImageAction(): Action '
            . 'method so the picker entry resolves at action-mount time.'
        );

        $this->assertStringContainsString(
            "Action::make('addImageAction')",
            $src,
            'addImageAction MUST construct a Filament Action with the matching '
            . 'name so the picker\'s replaceMountedAction call succeeds.'
        );

        $this->assertStringContainsString(
            "route('filament.admin.pages.media-library')",
            $src,
            'addImageAction MUST redirect to the canonical Media Library '
            . 'upload page route — `filament.admin.pages.media-library` is '
            . 'the route name registered by Modules/MediaLibrary/.../MediaLibrary.php.'
        );
    }

    #[Test]
    public function picker_entry_uses_a_recognisable_image_icon(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        // The icon MUST be a recognisable image / photo glyph so the
        // picker entry reads as an image affordance at a glance.
        $this->assertMatchesRegularExpression(
            '/[\'"]title[\'"]\s*=>\s*[\'"]New Image[\'"][\s\S]{0,400}[\'"]icon[\'"]\s*=>\s*[\'"]heroicon-o-photo[\'"]/',
            $src,
            'New Image picker entry MUST use heroicon-o-photo (or another '
            . 'recognisable photo glyph) as its icon so the affordance is '
            . 'visually distinct from the page/post/category/product icons.'
        );
    }

    // -----------------------------------------------------------------
    // AI-149 — sidebar collapse on mobile (re-enable cycle-126/127 fix)
    // -----------------------------------------------------------------

    #[Test]
    public function index_css_imports_live_edit_mobile_so_cycle_126_127_fixes_are_live(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/index.css');

        // The cycle-142 fix: the import line MUST be present in index.css.
        // Without it, every cycle-126 + cycle-127 mobile rule is dark in
        // the built bundle (which is what AI-149 was reporting — sidebar
        // permanently open on mobile because the AI-140 collapse rules
        // never reached the browser).
        $this->assertStringContainsString(
            "@import './microweber/live-edit-mobile.css';",
            $src,
            'index.css MUST import live-edit-mobile.css so the cycle-126 '
            . '(AI-140 sidebar collapse + AI-141 toolbar text-labels) and '
            . 'cycle-127 (AI-143 menu modal + AI-144 content modal) rules '
            . 'reach the built bundle.'
        );
    }

    #[Test]
    public function live_edit_mobile_css_carries_all_four_cycle_126_127_rule_anchors(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // All four AI-1xx anchors must remain inline so a future refactor
        // can trace which fix each rule belongs to.
        foreach (['AI-140', 'AI-141', 'AI-143', 'AI-144'] as $anchor) {
            $this->assertStringContainsString(
                $anchor,
                $src,
                "live-edit-mobile.css MUST keep the {$anchor} anchor inline "
                . 'so the cycle-126/127 rule provenance stays discoverable.'
            );
        }
    }

    #[Test]
    public function live_edit_mobile_css_clamps_active_drawer_width_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // AI-150 corrigendum (cycle-143): the original cycle-126 rule
        // (`width:56px` on bare .fi-sidebar) was removed because it
        // fought with the slide-out drawer pattern in
        // src/MicroweberPackages/Filament/.../live-edit.blade.php.
        // The corrected fix clamps .fi-sidebar.active to a sane mobile
        // width so the drawer is usable + doesn't fill the viewport.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.fi-sidebar\.active[\s\S]{0,200}width:\s*min\(280px,\s*calc\(100vw\s*-\s*60px\)\)\s*!important/m',
            $src,
            'live-edit-mobile.css MUST carry the AI-150 .fi-sidebar.active '
            . 'width clamp (min(280px, calc(100vw - 60px))) so the drawer '
            . 'leaves room for the close-overlay on mobile. The cycle-126 '
            . '56px-icon-strip rule was removed in cycle-143 because it '
            . 'fought with the slide-out drawer pattern.'
        );
    }

    #[Test]
    public function built_bundle_now_carries_the_active_drawer_width_clamp(): void
    {
        // Functional pin: the built CSS that the browser actually loads
        // MUST contain the AI-150 active-drawer width clamp. This is the
        // regression-detection mechanism that catches "rule in source
        // but not in bundle" issues like the cycle-126 import gap.
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping the production-CSS pin. "
                . 'Run `npm run build` in packages/microweber-filament-theme/ to refresh.'
            );
        }
        $built = file_get_contents($path);

        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.fi-sidebar\.active[^{]*\{[^}]*width:\s*min\(280px/m',
            $built,
            'Built CSS bundle MUST contain the .mw-live-edit-page '
            . '.fi-sidebar.active width:min(280px,...) rule. If this '
            . 'fails, live-edit-mobile.css is no longer reaching the '
            . 'bundle — that is the AI-149 regression class.'
        );
    }
}

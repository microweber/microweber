<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-17d95a / AI-832 HIGH — Tools dropdown mobile viewport safety.
 *
 * Root cause: .dropdown-content is position:absolute inside a horizontally-
 * scrollable #toolbar (overflow-x:auto, AI-74c5f5). position:absolute is
 * relative to the SCROLLED container, not the viewport — popup renders off-
 * screen when the toolbar is scrolled right on narrow viewports.
 *
 * Fix (live-edit-mobile.css, inside the @media (max-width: 768px),
 * (pointer: coarse) block):
 *   - position: fixed — always viewport-relative, even when toolbar is scrolled
 *   - top: calc(var(--toolbar-height, 48px) + 4px) — just below toolbar
 *   - right: 4px; left: auto — right-anchored, 4px from screen edge
 *   - max-height: 80vh; overflow-y: auto — all items reachable by scroll
 *   - z-index: 100000 — above toolbar and iframe canvas
 *   - max-width: calc(100vw - 8px) — never overflows screen width
 *   - flex-shrink: 0 on .custom-dropdown — trigger never crushed by flex
 */
class LiveEdit17d95aAI832ToolsDropdownMobileContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css')
        );
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;

        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $this->bundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ─── Mobile popup: position:fixed ─────────────────────────────────────────

    #[Test]
    public function dropdown_content_show_uses_position_fixed_on_mobile(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.custom-dropdown\s+\.dropdown-content\.show\s*\{[^}]*position:\s*fixed\s*!important~s',
            $this->srcStripped,
            '.custom-dropdown .dropdown-content.show must use position:fixed !important on mobile.'
        );
    }

    #[Test]
    public function popup_is_anchored_below_toolbar_height_token(): void
    {
        // top value uses the --toolbar-height token
        $pos = strrpos($this->srcStripped, '.custom-dropdown .dropdown-content.show');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 400);
        $this->assertStringContainsString('--toolbar-height', $slice,
            'top position must use var(--toolbar-height) so it adapts to the toolbar height token.'
        );
        $this->assertStringContainsString('right: 4px', $slice,
            'Popup must be right-anchored with 4px edge clearance.'
        );
    }

    #[Test]
    public function popup_has_max_height_and_overflow_scroll(): void
    {
        $pos = strrpos($this->srcStripped, '.custom-dropdown .dropdown-content.show');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 400);
        $this->assertStringContainsString('max-height: 80vh', $slice,
            'Popup must have max-height: 80vh so content is reachable by scroll on short screens.'
        );
        $this->assertStringContainsString('overflow-y: auto', $slice,
            'Popup must have overflow-y: auto for scrollable content.'
        );
    }

    #[Test]
    public function popup_has_high_z_index(): void
    {
        $pos = strrpos($this->srcStripped, '.custom-dropdown .dropdown-content.show');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 400);
        $this->assertStringContainsString('z-index: 100000', $slice,
            'z-index must be high enough to appear above the toolbar and iframe canvas.'
        );
    }

    #[Test]
    public function popup_has_max_width_viewport_constraint(): void
    {
        $pos = strrpos($this->srcStripped, '.custom-dropdown .dropdown-content.show');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 400);
        $this->assertStringContainsString('max-width: calc(100vw - 8px)', $slice,
            'Popup must be capped at calc(100vw - 8px) to prevent horizontal overflow.'
        );
    }

    // ─── Trigger button: flex-shrink protection ───────────────────────────────

    #[Test]
    public function trigger_wrapper_has_flex_shrink_0(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.custom-dropdown\s*\{[^}]*flex-shrink:\s*0~s',
            $this->srcStripped,
            '.custom-dropdown must have flex-shrink: 0 so the trigger is never crushed by toolbar flex.'
        );
    }

    // ─── Mobile @media scope ──────────────────────────────────────────────────

    #[Test]
    public function rule_is_inside_mobile_media_query(): void
    {
        // The rule must be inside @media (max-width: 768px)
        $pos = strrpos($this->srcStripped, '.custom-dropdown .dropdown-content.show');
        $this->assertNotFalse($pos);
        $before = substr($this->srcStripped, 0, (int) $pos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos);
        $mediaSlice = substr($this->srcStripped, (int) $mediaPos, 80);
        $this->assertStringContainsString('768px', $mediaSlice,
            'Tools dropdown mobile rule must be inside @media (max-width: 768px) block.'
        );
    }

    #[Test]
    public function live_edit_page_scope_present(): void
    {
        // Selector must be scoped to .mw-live-edit-page or .mw-admin-live-edit-page
        $this->assertMatchesRegularExpression(
            '~\.mw-(admin-)?live-edit-page\s+#toolbar\s+\.custom-dropdown\s+\.dropdown-content~s',
            $this->srcStripped,
            'Rule must be scoped to .mw-live-edit-page / .mw-admin-live-edit-page #toolbar.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-17d95a', $this->src);
    }

    // ─── Webpack bundle probe ─────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_fixed_positioning_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Built bundle not present in this environment.');
        }
        // Bundle preserves whitespace in property values; check for z-index:100000
        // which is unique to this fix. position:fixed is in the same rule block.
        $this->assertStringContainsString('z-index: 100000', $this->bundle,
            'Webpack bundle must contain z-index:100000 for the mobile dropdown fix.'
        );
        $this->assertStringContainsString('position: fixed', $this->bundle,
            'Webpack bundle must contain position:fixed for the mobile dropdown fix.'
        );
    }

    #[Test]
    public function bundle_mtime_is_newer_than_source(): void
    {
        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $srcPath = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Built bundle not present in this environment.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime($srcPath),
            filemtime($bundlePath),
            'Bundle mtime must be ≥ source mtime — confirms bundle was rebuilt after the fix.'
        );
    }

    // ─── AI-717 sticky-SAVE regression guard ─────────────────────────────────

    #[Test]
    public function ai717_sticky_save_still_present(): void
    {
        $this->assertStringContainsString(
            '#save-button',
            $this->srcStripped,
            'AI-717 sticky SAVE button rules must still be present in live-edit-mobile.css.'
        );
        // Supersession: the AI-717 sticky SAVE selector was made more specific
        // (`#toolbar #save-button`) and its offset bumped from `right: 0` to
        // `right: 4px !important` (AI-1163 mobile-overflow pass — a small gutter
        // so the box-shadow/edge clears the toolbar border). Still rightmost
        // sticky; pin the current right-offset shape.
        $this->assertMatchesRegularExpression(
            '~#save-button\s*\{[^}]*right:\s*4px~s',
            $this->srcStripped,
            'AI-717 SAVE must still be right-anchored (now right:4px, rightmost sticky).'
        );
    }
}

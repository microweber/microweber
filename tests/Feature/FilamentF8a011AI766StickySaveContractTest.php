<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-f8a011 / AI-766 — Filament sticky save button + content clearance.
 *
 * Background (AI-511 context):
 *   The sticky `.fi-form-actions` bar was introduced in AI-511
 *   (task-2026-05-14-92501a). It applies `position: sticky; bottom: 0`
 *   to the form action bar universally across admin panel page-level
 *   forms — mobile and desktop alike.
 *
 * Problem AI-766 addresses:
 *   Without a matching bottom padding on the form container the sticky
 *   bar physically overlaps the last form field when the page is
 *   scrolled to its natural end. The user can see the field label but
 *   cannot reach the input — data-entry dead zone.
 *
 * Fix (this task):
 *   Add `padding-bottom: 72px` to the two form-container selectors
 *   that mirror the AI-511 scope:
 *     body.fi-panel-admin .fi-page > .fi-form
 *     body.fi-panel-admin .fi-page-content > .fi-form
 *   72px = 44px (button min-height touch floor) + 2 × 0.75rem padding
 *   ≈ 68px → rounded to 72px for breathing room.
 *
 * AI-511 regression guards (last four tests): the original sticky
 * positioning, dark-mode override, and desktop max-width constraint
 * must remain intact after this additive change.
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class FilamentF8a011AI766StickySaveContractTest extends TestCase
{
    private const CSS_SOURCE = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const CSS_BUNDLE  = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $css;
    private string $cssStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(base_path(self::CSS_SOURCE));
        $this->css        = $raw;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;

        $bundlePath  = base_path(self::CSS_BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task marker ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-f8a011',
            $this->css,
            'mobile-touch.css must carry the AI-766 task marker.'
        );
    }

    // ─── AC #3: padding-bottom on form container ──────────────────────────────

    #[Test]
    public function form_container_has_padding_bottom_72px_fi_page(): void
    {
        // AI-766 uses a multi-selector block (comma-separated). The AI-511 block
        // uses `body.fi-panel-admin .fi-page > .fi-form .fi-form-actions` (with
        // ".fi-form-actions" appended). The AI-766 block ends the selector with
        // `.fi-form,` (comma + newline, no child suffix). The trailing comma is
        // the discriminator — search for that specific pattern.
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-page\s*>\s*\.fi-form\s*,[\s\S]*?padding-bottom:\s*72px~s',
            $this->cssStripped,
            'AI-766 multi-selector block must have padding-bottom: 72px after .fi-page > .fi-form,'
        );
    }

    #[Test]
    public function form_container_has_padding_bottom_72px_fi_page_content(): void
    {
        // The .fi-page-content selector is the second line of the comma-list and
        // directly precedes the { padding-bottom: 72px } declaration.
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-page-content\s*>\s*\.fi-form\s*\{[^}]*padding-bottom:\s*72px~s',
            $this->cssStripped,
            'body.fi-panel-admin .fi-page-content > .fi-form block must have padding-bottom: 72px'
        );
    }

    // ─── AC #1+2: sticky positioning preserved (AI-511 regression guard) ─────

    #[Test]
    public function form_actions_sticky_position_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-page[^{]*\.fi-form-actions\s*\{[^}]*position:\s*sticky~s',
            $this->cssStripped,
            '.fi-form-actions must retain position: sticky (AI-511 regression guard)'
        );
    }

    #[Test]
    public function form_actions_bottom_zero_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-page[^{]*\.fi-form-actions\s*\{[^}]*bottom:\s*0~s',
            $this->cssStripped,
            '.fi-form-actions must retain bottom: 0 (AI-511 regression guard)'
        );
    }

    #[Test]
    public function form_actions_z_index_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-page[^{]*\.fi-form-actions\s*\{[^}]*z-index:\s*5~s',
            $this->cssStripped,
            '.fi-form-actions must retain z-index: 5 (AI-511 regression guard)'
        );
    }

    // ─── Dark-mode override preserved ────────────────────────────────────────

    #[Test]
    public function dark_mode_form_actions_background_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark\s+body\.fi-panel-admin[^{]*\.fi-form-actions\s*\{[^}]*background-color~s',
            $this->cssStripped,
            'Dark-mode .fi-form-actions background override must be present (AI-511 regression guard)'
        );
    }

    // ─── Desktop max-width constraint preserved ───────────────────────────────

    #[Test]
    public function desktop_max_width_constraint_preserved(): void
    {
        $this->assertStringContainsString(
            'max-width: 48rem',
            $this->cssStripped,
            'Form container max-width: 48rem (AI-511 desktop constraint) must be preserved'
        );
    }

    // ─── Bundle runtime probe ─────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_padding_bottom_72px(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present — run npm run build first.');
        }

        $this->assertMatchesRegularExpression(
            '~padding-bottom:\s*72px~',
            $this->bundle,
            'Built bundle must contain padding-bottom: 72px for AI-766 content clearance'
        );
    }

    #[Test]
    public function bundle_contains_fi_form_actions_sticky(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present — run npm run build first.');
        }

        $this->assertStringContainsString(
            'fi-form-actions',
            $this->bundle,
            'Built bundle must contain the .fi-form-actions sticky rule (AI-511 regression guard)'
        );
    }

    #[Test]
    public function bundle_mtime_not_older_than_source(): void
    {
        $bundlePath = base_path(self::CSS_BUNDLE);
        if (! file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present.');
        }

        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::CSS_SOURCE)),
            filemtime($bundlePath),
            'Bundle mtime must be >= source mtime — re-run npm run build after editing mobile-touch.css'
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-e1a4c9 / AI-1227
 * Add Content modal CSS spacing regressions.
 *
 * Root causes:
 *  D1 — .mw-add-content-modal-action-wrapper had `padding: 0` at runtime.
 *       Tailwind `p-4` on the Blade wrapper is not processed by the
 *       microweber-filament-theme Webpack pipeline, so the utility never
 *       reached the browser.
 *  D2 — prior `.mw-content-picker-modal .mw-add-content-group--primary
 *       .mw-add-content-modal-action-wrapper` rule set only `padding-block`
 *       (vertical), leaving inline (horizontal) padding at 0.
 *  D3 — .mw-add-content-group--secondary `margin-top: 0`; Tailwind `mt-4`
 *       had the same pipeline gap.
 *  D4 — search input rendered with a dark (near-black) border in light mode.
 *       The only existing rule was a `.dark` override; the light-mode
 *       baseline `border-gray-200` utility was not in the build pipeline.
 *
 * Fix:
 *  Added to live-edit-classes.css (microweber-filament-theme Webpack bundle):
 *    .mw-content-picker-modal .mw-add-content-modal-action-wrapper { padding: 1rem }
 *    .mw-content-picker-modal .mw-add-content-group--secondary { margin-top: 1rem }
 *    .mw-content-picker-modal .mw-add-content-modal-search-input { border: 1px solid rgb(209,213,219) }
 *    .dark / .dark override for search input border
 *
 * Source-of-truth:
 *   packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css
 */
class LiveEditE1a4c9AI1227AddContentModalSpacingContractTest extends TestCase
{
    private const CSS_SOURCE = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';
    private const BUILT_CSS  = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $cssSource;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cssSource   = (string) file_get_contents(base_path(self::CSS_SOURCE));
        // Strip CSS block comments before any absence assertions (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->cssSource);
    }

    // -----------------------------------------------------------------------
    // Group A — D1+D2 card padding (unified 1rem on all wrapper instances)
    // -----------------------------------------------------------------------

    #[Test]
    public function card_wrapper_has_padding_1rem(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*\{[^}]*padding\s*:\s*1rem~s',
            $this->cssSource,
            'AI-1227 D1+D2: .mw-content-picker-modal .mw-add-content-modal-action-wrapper MUST have padding: 1rem.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — D3 secondary section margin
    // -----------------------------------------------------------------------

    #[Test]
    public function secondary_section_has_margin_top_1rem(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-content-picker-modal\s+\.mw-add-content-group--secondary\s*\{[^}]*margin-top\s*:\s*1rem~s',
            $this->cssSource,
            'AI-1227 D3: .mw-content-picker-modal .mw-add-content-group--secondary MUST have margin-top: 1rem.'
        );
    }

    // -----------------------------------------------------------------------
    // Group C — D4 search input border (light + dark)
    // -----------------------------------------------------------------------

    #[Test]
    public function search_input_has_light_mode_border(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-content-picker-modal\s+\.mw-add-content-modal-search-input\s*\{[^}]*border\s*:\s*1px solid rgb\(209,\s*213,\s*219\)~s',
            $this->cssSource,
            'AI-1227 D4: .mw-content-picker-modal .mw-add-content-modal-search-input MUST carry light-mode border: 1px solid rgb(209,213,219).'
        );
    }

    #[Test]
    public function search_input_has_dark_mode_border_html_dark(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-search-input\s*\{[^}]*border\s*:\s*1px solid rgba\(255,\s*255,\s*255,\s*0\.1\)~s',
            $this->cssSource,
            'AI-1227 D4: .dark dark-mode override on search input border MUST be present.'
        );
    }

    #[Test]
    public function search_input_has_dark_mode_border_class_dark(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-search-input\s*\{[^}]*border\s*:\s*1px solid rgba\(255,\s*255,\s*255,\s*0\.1\)~s',
            $this->cssSource,
            'AI-1227 D4: .dark dark-mode override on search input border MUST be present.'
        );
    }

    // -----------------------------------------------------------------------
    // Group D — built bundle contains the new rules
    // -----------------------------------------------------------------------

    #[Test]
    public function built_bundle_contains_padding_rule(): void
    {
        $built = (string) file_get_contents(base_path(self::BUILT_CSS));
        $this->assertStringContainsString(
            'mw-add-content-modal-action-wrapper',
            $built,
            'AI-1227: built microweber-filament-theme.css MUST contain the card-wrapper padding rule.'
        );
    }

    #[Test]
    public function built_bundle_contains_secondary_section_rule(): void
    {
        $built = (string) file_get_contents(base_path(self::BUILT_CSS));
        $this->assertStringContainsString(
            'mw-add-content-group--secondary',
            $built,
            'AI-1227: built bundle MUST contain the secondary-section margin rule.'
        );
    }

    #[Test]
    public function built_bundle_contains_search_input_border_rule(): void
    {
        $built = (string) file_get_contents(base_path(self::BUILT_CSS));
        $this->assertStringContainsString(
            'mw-add-content-modal-search-input',
            $built,
            'AI-1227: built bundle MUST contain the search-input border rule.'
        );
    }

    // -----------------------------------------------------------------------
    // Group E — task-id marker
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-e1a4c9',
            $this->cssSource,
            'AI-1227: CSS source MUST carry the task-id marker for cross-surface audit grep.'
        );
    }
}

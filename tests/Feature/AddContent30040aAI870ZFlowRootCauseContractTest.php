<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-21-30040a / AI-870 — Add Content modal v2 Z-flow
 * regression root cause fix. P1.
 * Jira: https://microweber.atlassian.net/browse/AI-870
 *
 * Deep DOM investigation by designer found the ACTUAL root cause:
 * an inline <style> block (NOT from live-edit-classes.css) applies:
 *
 *   @media (min-width: 640px) {
 *     .mw-content-picker-modal .fi-sc-component .mb-6 {
 *       display: grid;
 *       grid-template-columns: 1fr 1fr;
 *       gap: 0.75rem;
 *     }
 *   }
 *
 * `.mw-add-content-modal-root` carries the Tailwind class `mb-6`.
 * At desktop (≥640px) this rule makes the modal root a 2-column
 * grid, placing search in the left column and the primary card
 * in the right column — Z-flow break.
 *
 * AI-713 targeted `.fi-grid` but the inline CSS targets
 * `.fi-sc-component .mb-6` — two different elements. AI-713 never
 * overrode the actual culprit.
 *
 * Fixes applied:
 *   1. live-edit-classes.css: override display:flex !important on
 *      .mw-content-picker-modal .fi-sc-component .mw-add-content-modal-root
 *   2. add-content-modal.blade.php: secondary grid always grid-cols-3
 *      (no 2→3 reflow artefact on viewport resize; modal is ≥618px)
 *   3. add-content-modal.blade.php: primary card visual upgrade
 *      (min-h-[72px], text-base, description visible, chevron)
 *
 * Test groups:
 *   A = CSS override presence + bundle delivery
 *   B = secondary grid always 3 columns (no sm:grid-cols-3)
 *   C = primary card visual upgrade
 *   D = task markers
 */
class AddContent30040aAI870ZFlowRootCauseContractTest extends TestCase
{
    private string $css;
    private string $cssStripped;
    private string $blade;
    private string $bundle;

    private const CSS_SRC = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';
    private const MODAL_BLADE = 'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php';
    private const BUNDLE = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(self::CSS_SRC));
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->css) ?? $this->css;
        $this->blade = (string) file_get_contents(base_path(self::MODAL_BLADE));
        if (file_exists(base_path(self::BUNDLE))) {
            $this->bundle = (string) file_get_contents(base_path(self::BUNDLE));
        } else {
            $this->bundle = '';
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — CSS override
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai870_flex_override_present_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-sc-component\s+\.mw-add-content-modal-root\s*\{[^}]*display:\s*flex\s*!important/s',
            $this->cssStripped,
            'live-edit-classes.css must override .mw-add-content-modal-root to display:flex !important inside .fi-sc-component.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-sc-component\s+\.mw-add-content-modal-root\s*\{[^}]*flex-direction:\s*column\s*!important/s',
            $this->cssStripped,
            'live-edit-classes.css must set flex-direction:column !important on .mw-add-content-modal-root.'
        );
    }

    #[Test]
    public function ai870_root_cause_comment_documents_inline_style_grid(): void
    {
        // The docblock must explain the inline <style> block culprit so
        // future agents understand why AI-713 alone was insufficient.
        $this->assertStringContainsString(
            'AI-870',
            $this->css,
            'live-edit-classes.css must carry AI-870 ticket reference.'
        );
        $this->assertStringContainsString(
            'fi-sc-component',
            $this->css,
            'AI-870 comment must mention .fi-sc-component (the inline CSS target).'
        );
    }

    #[Test]
    public function ai870_override_present_in_served_bundle(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Bundle absent — run npm run build in microweber-filament-theme.');
        }
        $this->assertStringContainsString(
            '.mw-content-picker-modal .fi-sc-component .mw-add-content-modal-root',
            $this->bundle,
            'AI-870 override selector must be present in the served Webpack bundle.'
        );
    }

    #[Test]
    public function bundle_mtime_not_older_than_css_source(): void
    {
        if (! file_exists(base_path(self::BUNDLE))) {
            $this->markTestSkipped('Bundle absent.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::CSS_SRC)),
            filemtime(base_path(self::BUNDLE)),
            'Served bundle mtime must be >= source mtime — run npm run build.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — secondary grid fix
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function secondary_grid_always_3_columns(): void
    {
        // Blade comment-strip before negative assertion.
        $strippedBlade = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->blade);

        $this->assertStringContainsString(
            'grid grid-cols-3 gap-3',
            $strippedBlade,
            'Secondary items grid must use grid-cols-3 (always 3 columns).'
        );
        $this->assertStringNotContainsString(
            'grid-cols-2 sm:grid-cols-3',
            $strippedBlade,
            'Secondary items grid must NOT use grid-cols-2 sm:grid-cols-3 — causes Z-flow reflow artefact on viewport resize.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — primary card visual upgrade
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function primary_card_has_min_height_72px(): void
    {
        $this->assertStringContainsString(
            'min-h-[72px]',
            $this->blade,
            'Primary card must carry Tailwind min-h-[72px] (increased from ~63px).'
        );
    }

    #[Test]
    public function primary_card_title_uses_text_base(): void
    {
        // The primary card title must use text-base (not text-sm) for
        // hierarchy above the secondary cards.
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group--primary[\s\S]*?text-base/s',
            $this->blade,
            'Primary card title must use text-base (not text-sm) per AI-870 visual upgrade.'
        );
    }

    #[Test]
    public function primary_card_shows_description_text(): void
    {
        // Description must be visible as text (not just tooltip/aria-label).
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group--primary[\s\S]*?action\[.description.\]/s',
            $this->blade,
            'Primary card description must be rendered as visible text inside the primary section.'
        );
    }

    #[Test]
    public function primary_card_has_chevron_affordance(): void
    {
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group--primary[\s\S]*?heroicon-o-chevron-right/s',
            $this->blade,
            'Primary card must include a heroicon-o-chevron-right affordance indicator.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_in_both_files(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-21-30040a',
            $this->css,
            'live-edit-classes.css must carry task-30040a marker.'
        );
        $this->assertStringContainsString(
            'task-2026-05-21-30040a',
            $this->blade,
            'add-content-modal.blade.php must carry task-30040a marker.'
        );
    }
}

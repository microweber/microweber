<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-df485c / AI-769 — Image picker dialog dark-mode
 * skin (AI-740 incomplete). Jira:
 *   https://microweber.atlassian.net/browse/AI-769
 *
 * Designer dispatch 2026-05-17T05:48:11 (P1 visual — dark-mode
 * user got a 600×~200 px white flash on Insert Image).
 *
 * AI-740 (task-8d5baf) only raised the dialog backdrop alpha
 * 40→60 % in dark mode. The modal SURFACE itself stayed light:
 *   - white .mw-dialog-holder bg
 *   - light .mw-modal-title-holder + .mw-dialog-header
 *   - light-on-white .mw-filepicker-component-navigation-header tabs
 *   - light input.form-control fields
 *   - light .mw-dialog-bottom-buttons / .mw-dialog-footer
 *   - light .mw-filepicker-component-thumbnails grid cells
 *
 * Fix: add a `.dark .mw-dialog-skin-default { … }` block in
 * general-styles.css (right next to the AI-740 backdrop override)
 * covering all six surfaces. Scope = `.mw-dialog-skin-default`
 * (shared by filepicker, prompts, confirms — all benefit). Light
 * mode unchanged.
 *
 * AI-740 backdrop override is preserved untouched — AI-769 is
 * additive.
 */
class LiveEditDf485cAI769DialogDarkSkinContractTest extends TestCase
{
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $bundlePath = base_path(
            'packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — dialog surface dark-mode rules
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function dialog_holder_dark_bg_and_text(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-dialog-holder[^{]*\{[^}]*background-color:\s*#1f2937\s*!important/i',
            $this->css,
            '.mw-dialog-holder dark-mode bg must be #1f2937 (gray-800, Filament dark surface).'
        );
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-dialog-holder[^{]*\{[^}]*color:\s*#f3f4f6\s*!important/i',
            $this->css,
            '.mw-dialog-holder dark-mode text color must be #f3f4f6 (gray-100).'
        );
    }

    #[Test]
    public function title_bar_carries_dark_skin(): void
    {
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-modal-title-holder',
            $this->css,
            'Title bar (.mw-modal-title-holder) must have a .dark override.'
        );
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-dialog-header',
            $this->css,
            'Dialog header (.mw-dialog-header) must have a .dark override.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — tab nav anchors dark-mode
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function tab_anchors_dark_text_with_accent_active(): void
    {
        // Default tab text gray-200. The CSS selector list runs
        // across multi-line (.dark variant + .dark variant) so
        // we just assert the rule body has color:#e5e7eb !important
        // inside the picker-tab-anchor declaration block.
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-component-navigation-header\s+a\s*\{[^}]*color:\s*#e5e7eb\s*!important/is',
            $this->css,
            'Picker tab anchors must use #e5e7eb (gray-200) default color in dark mode.'
        );
        // Active tab → --ese-accent.
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-filepicker-component-navigation-header\s+a\.active[^{]*\{[^}]*color:\s*var\(--ese-accent/i',
            $this->css,
            'Active picker tab must use --ese-accent color in dark mode.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — form inputs + footer + thumbnails
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function form_inputs_dark_bg(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+input\.form-control[^{]*\{[^}]*background-color:\s*#111827\s*!important/i',
            $this->css,
            'Form input dark-mode bg must be #111827 (gray-900).'
        );
        // Placeholder colour gray-400.
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+input\.form-control::placeholder[^{]*\{[^}]*color:\s*#9ca3af/i',
            $this->css,
            'Form input placeholder must be #9ca3af (gray-400) in dark mode.'
        );
    }

    #[Test]
    public function footer_buttons_dark_skin(): void
    {
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-dialog-bottom-buttons',
            $this->css,
            '.mw-dialog-bottom-buttons must have a .dark override.'
        );
        // The footer secondary-button (Cancel) rule excludes the
        // primary/danger/success/warning variants so they keep
        // their own coloured fills.
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-dialog-bottom-buttons\s+\.btn:not\(\.btn-primary\):not\(\.btn-danger\)/i',
            $this->css,
            'Footer button rule must use :not(.btn-primary):not(.btn-danger) to scope to secondary buttons only.'
        );
    }

    #[Test]
    public function thumbnail_grid_cells_dark_bg(): void
    {
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-filepicker-component-thumbnails',
            $this->css,
            'Thumbnail grid (.mw-filepicker-component-thumbnails) must have a .dark override.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — additive (AI-740 backdrop preserved + light unchanged)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai740_backdrop_override_preserved(): void
    {
        // AI-769 must NOT disturb AI-740's `!bg-black/60` dark
        // backdrop override.
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-dialog-overlay[^{]*\{[^}]*bg-black\/60/i',
            $this->css,
            'AI-740 dark-backdrop override (!bg-black/60) must remain intact.'
        );
    }

    #[Test]
    public function light_mode_dialog_holder_baseline_unchanged(): void
    {
        // The original light-mode rule for .mw-dialog-holder must
        // still exist (defensive guard — AI-769 is additive only).
        $this->assertMatchesRegularExpression(
            '/\.mw-dialog-skin-default:not\(\.mw_modal_live_edit_link_editor_settings\)\s+\.mw-dialog-holder\s*\{/',
            $this->css,
            'Pre-AI-769 light-mode .mw-dialog-holder rule must still exist (additive override only).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — bundle runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_dark_dialog_holder_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present — run `cd packages/microweber-filament-theme && npm run build` to enable runtime probe.');
        }
        // Probe for the computed dark dialog holder rule —
        // .dark + .mw-dialog-holder + #1f2937 bg.
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-dialog-skin-default\s+\.mw-dialog-holder[^{]*\{[^}]*#1f2937/i',
            $this->bundle,
            'Webpack bundle must carry the dark-mode .mw-dialog-holder rule with #1f2937 bg.'
        );
    }

    #[Test]
    public function task_id_and_ai769_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-df485c', $this->css);
        $this->assertStringContainsString('AI-769', $this->css);
        // AI-740 reference for audit-chain continuity.
        $this->assertStringContainsString(
            'AI-740',
            $this->css,
            'Comment must cite AI-740 (the backdrop-only fix this slice completes).'
        );
    }
}

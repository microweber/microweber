<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-8149b5 — Live Edit toolbar labels readable on mobile.
 *
 * The user reported: "also font is not reable on mobile" with a
 * screenshot showing the right vertical sidebar icons rendering
 * truncated labels: "Insert layou", "Template s...", "Quick AI ed...".
 *
 * Root cause (live-edit-mobile.css `@media (max-width: 768px)`):
 *   - font-size: 0.625rem (10px) — below comfort floor.
 *   - max-width: 60px + white-space: nowrap + text-overflow: ellipsis
 *     on the ::after content from aria-label — labels like
 *     "Template settings" (17 chars) overflowed 60px at any font-size
 *     and got truncated.
 *   - The vertical sidebar itself was width:50px (set in
 *     SettingsCustomize.vue scoped styles), so even un-truncated
 *     labels had no room to render.
 *
 * Fix (one source file edited):
 *   - font-size: 0.6875rem (11px) — readable + still smaller than
 *     body so the label reads as secondary metadata to the icon.
 *   - white-space: normal + overflow-wrap: break-word — labels
 *     wrap to 2 lines instead of truncating.
 *   - max-width: 72px + line-height: 1.15 — two-line labels stay
 *     compact and readable.
 *   - On the same @media, widen the right sidebar to width: 72px
 *     and bump button width to 64px so the labels have room.
 *
 * Browser-verified at 560×600: all 5 sidebar buttons (Insert layout /
 * Template settings / Design / Quick AI edit / Advanced) now render
 * their full labels in 2 lines, no ellipsis.
 */
class LiveEdit8149b5MobileFontReadabilityContractTest extends TestCase
{
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));
    }

    #[Test]
    public function label_font_size_meets_mobile_comfort_floor(): void
    {
        // 0.6875rem = 11px. Anything 10px or below is the regression
        // we just fixed.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.live-edit-toolbar-buttons\.mw-toolbar-icon-btn[^}]*\{[^}]*font-size:\s*0\.6875rem/s',
            $this->css,
            'Toolbar icon-button label must use 0.6875rem (11px), not 0.625rem (10px).'
        );
    }

    #[Test]
    public function labels_no_longer_truncate(): void
    {
        // Regression guard — the prior `white-space: nowrap` +
        // `text-overflow: ellipsis` combo on the ::after block is gone.
        // The new rule uses `white-space: normal` + `overflow-wrap`.
        $this->assertMatchesRegularExpression(
            '/\.live-edit-toolbar\s+\.fi-icon-btn::after[^}]*\{[^}]*white-space:\s*normal/s',
            $this->css,
            'The ::after label rule must allow wrapping (white-space: normal).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.fi-icon-btn::after\s*\{[^}]*white-space:\s*nowrap[^}]*text-overflow:\s*ellipsis/s',
            $this->css,
            'The previous nowrap + ellipsis truncation combo must not regress.'
        );
    }

    #[Test]
    public function label_max_width_accommodates_two_line_wrap(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.live-edit-toolbar\s+\.fi-icon-btn::after[^}]*\{[^}]*max-width:\s*72px/s',
            $this->css,
            'Label max-width must be 72px (widened from 60px) for two-line wrap.'
        );
    }

    #[Test]
    public function right_sidebar_is_widened_on_mobile_to_fit_labels(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-live-edit-right-sidebar-template-sidebar\s*\{[^}]*width:\s*72px\s*!important/s',
            $this->css,
            'Right vertical sidebar must widen to 72px on mobile so labels fit.'
        );
    }

    #[Test]
    public function sidebar_buttons_grow_to_match_widened_sidebar(): void
    {
        // Button width grows from 39px to 64px so the two-line label
        // is visually centered under the icon.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-template-sidebar\s+\.btn-icon\.live-edit-toolbar-buttons[^}]*\{[^}]*width:\s*64px\s*!important/s',
            $this->css
        );
    }

    #[Test]
    public function task_id_is_pinned_in_the_source_comment_for_grepability(): void
    {
        // The fix touches three loci in the same @media block — each
        // marked with the task id for audit grepping.
        $count = substr_count($this->css, 'task-2026-05-16-8149b5');
        $this->assertGreaterThanOrEqual(3, $count,
            'task-2026-05-16-8149b5 must appear in each touched block (font-size, ::after wrap, sidebar widening).');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-02760c — Live Edit hamburger menu sidebar drawer
 * was sliding to x=-280 (off-screen) instead of x=0 even when the
 * `.active` class was correctly applied.
 *
 * Root cause: Filament v5 ships Tailwind v4. Tailwind v4 uses the
 * separate CSS `translate` property (not `transform`) for translation
 * utilities — Filament's sidebar carries `-translate-x-full` which
 * compiles to `translate: -100% !important`. The pre-existing inline
 * slide-in animation in `live-edit.blade.php` drove `transform`, but
 * `translate` and `transform` are independent CSS properties that
 * apply additively. So even when `.active` set `transform: translateX(0%)`,
 * the un-reset `translate: -100%` kept the sidebar off-screen by -280px.
 *
 * User-visible symptom: clicking the hamburger toolbar button did
 * nothing visible — the menu content was rendering but at viewport
 * x=-280 (entirely off-screen left).
 *
 * Fix (Blade view only — no JS, no bundle rebuild needed):
 *   - Added `translate: none !important;` to `.fi-sidebar` inline rule
 *     in `live-edit.blade.php` so the only translation source is
 *     `transform`. This restores the working slide-in/out animation.
 *
 * Pins the new rule + a regression guard that the inline animation
 * keeps its `transform`-based driver and that `translate` is
 * explicitly reset.
 */
class LiveEdit02760cSidebarTranslateResetContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php'
        ));
    }

    #[Test]
    public function fi_sidebar_rule_resets_translate_to_none_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-sidebar\s*\{[^}]*translate:\s*none\s*!important/s',
            $this->blade,
            '.fi-sidebar must reset `translate: none !important` so the Tailwind v4 '
            . '`-translate-x-full` utility cannot keep the drawer off-screen.'
        );
    }

    #[Test]
    public function fi_sidebar_keeps_transform_based_slide_in(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-sidebar\s*\{[^}]*transform:\s*translateX\(-100%\)\s*!important/s',
            $this->blade,
            '.fi-sidebar default state must still translate -100% via the transform property.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-sidebar\.active\s*\{[^}]*transform:\s*translateX\(0%\)\s*!important/s',
            $this->blade,
            '.fi-sidebar.active must reset transform to translateX(0%) for the slide-in.'
        );
    }

    #[Test]
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-16-02760c', $this->blade);
    }

    #[Test]
    public function transition_speed_token_unchanged(): void
    {
        // The slide animation must still use the project's shared
        // toolbar-height animation-speed CSS variable so the duration
        // stays consistent with the rest of the live edit chrome.
        $this->assertStringContainsString(
            'transition: var(--toolbar-height-animation-speed)',
            $this->blade
        );
    }

    #[Test]
    public function hamburger_toggle_handler_remains_wired(): void
    {
        // Defensive — the click handler that toggles the .active
        // class on the sidebar must remain attached. Without it,
        // the CSS fix alone cannot reveal the drawer.
        $this->assertStringContainsString(
            "document.getElementById('mw-live-edit-toolbar-back-to-admin-link')",
            $this->blade
        );
        $this->assertStringContainsString(
            'mw.app.liveEditWidgets.toggleAdminSidebar()',
            $this->blade
        );
    }
}

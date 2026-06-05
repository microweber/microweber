<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-mnumodal-slide — Live-edit module-settings create/edit
 * submodals slide to full screen.
 *
 * Inside the live-edit slide-over each Module Settings page is a nested iframe.
 * Its create/edit "submodal" (Add post / Custom field / Accordion item / New tab
 * / …) is teleported to be a direct child of <body> (task-2026-06-05-mnumodal-
 * teleport) so its inputs are clickable. A small centred dialog — or a narrow
 * right-docked native slide-over — inside the already-narrow settings panel reads
 * as a cramped stray popup, so EVERY hoisted submodal instead slides in from the
 * inline-end edge and fills the full panel viewport (verified in-browser across
 * Tabs, Accordion, Content/posts and Custom-fields: each create dialog measured
 * the full iframe viewport and its first control was hit-testable).
 *
 * The rule is scoped with the child combinator to `body > .fi-modal` so it ONLY
 * affects the teleported (in-iframe) copy — the standalone
 * /admin/<module>-module-settings page keeps its centred dialog. The earlier
 * centred-chrome rules (pinned by MenuMnumodalCreateModalChromeContractTest) are
 * preserved for that standalone surface.
 */
class LiveEditMnumodalSlideSubmodalFullscreenContractTest extends TestCase
{
    private string $layout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php'
        ));
    }

    #[Test]
    public function hoisted_submodal_window_fills_the_viewport(): void
    {
        // body > .fi-modal .fi-modal-window → fixed, inset 0, 100vw × 100vh.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s*>\s*\.fi-modal\s+\.fi-modal-window\s*\{[^}]*'
            . 'position:\s*fixed\s*!important[^}]*'
            . 'inset:\s*0\s*!important[^}]*'
            . 'width:\s*100vw\s*!important[^}]*'
            . 'height:\s*100vh\s*!important/s',
            $this->layout,
            'Every hoisted submodal window must be a fixed, full-viewport sheet.'
        );
    }

    #[Test]
    public function rule_uses_child_combinator_so_standalone_page_is_untouched(): void
    {
        // The `>` direct-child combinator means only the teleported (body-level)
        // copy matches; the standalone page's nested modal does not.
        $this->assertStringContainsString('body.fi-panel-admin > .fi-modal .fi-modal-window', $this->layout,
            'The full-screen rule must use the body > .fi-modal child combinator.');
    }

    #[Test]
    public function applies_to_slide_overs_too_no_not_exclusion(): void
    {
        // Native slide-over submodals (e.g. Accordion item) must ALSO go
        // full-screen, so the full-screen window rule must NOT carry a
        // :not(.fi-modal-slide-over) guard.
        $this->assertDoesNotMatchRegularExpression(
            '/body\.fi-panel-admin\s*>\s*\.fi-modal:not\(\.fi-modal-slide-over\)\s+\.fi-modal-window\s*\{[^}]*position:\s*fixed/s',
            $this->layout,
            'The full-screen window rule must apply to slide-overs too (no :not(.fi-modal-slide-over)).'
        );
    }

    #[Test]
    public function content_region_scrolls_within_the_full_height_sheet(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s*>\s*\.fi-modal\s+\.fi-modal-content\s*\{[^}]*overflow-y:\s*auto\s*!important/s',
            $this->layout,
            'The submodal content region must scroll inside the full-height sheet.'
        );
    }

    #[Test]
    public function slides_in_from_the_inline_end_edge(): void
    {
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-submodal-slide-in\s*\{[^}]*from\s*\{\s*transform:\s*translateX\(100%\)/s',
            $this->layout,
            'The submodal must slide in via a translateX keyframe.'
        );
        $this->assertStringContainsString('animation: mw-submodal-slide-in', $this->layout,
            'The full-screen window must reference the slide-in animation.');
    }

    #[Test]
    public function honours_reduced_motion(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)\s*\{[^@]*body\.fi-panel-admin\s*>\s*\.fi-modal\s+\.fi-modal-window\s*\{[^}]*animation:\s*none\s*!important/s',
            $this->layout,
            'The slide animation must be disabled under prefers-reduced-motion.'
        );
    }
}

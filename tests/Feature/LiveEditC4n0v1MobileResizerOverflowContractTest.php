<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-27-c4n0v1 — Live Edit canvas iframe was producing
 * horizontal scrollbars on mobile (≤768px) inside the iframe body.
 *
 * Root cause: AI-1172 widened the resize handle touch target via a
 * ::before pseudo-element that extends 19px beyond the parent edges
 * (right:-19px / left:-19px / top:-19px / bottom:-19px) to grow a
 * usable touch area around the bare 5px handle. At viewport edges
 * the 19px ghost crosses the <body> boundary, inflating
 * document.body.scrollWidth by 19px and triggering an internal
 * horizontal scrollbar inside the canvas iframe.
 *
 * Drag-resize of a 5px handle is not usable on touch anyway —
 * element/spacer dimension edits happen via the right-rail Advanced
 * panel on mobile. The touch-target expansion is desktop-only by
 * design.
 *
 * Fix: collapse the four edge pseudo-elements to `display: none`
 * inside `@media (max-width: 768px)` (mirrors the existing live-edit
 * mobile breakpoint per live-edit-mobile.css). The 5px parent handle
 * itself stays — only the pseudo touch-extension is suppressed.
 *
 * Stage-3 viewport-scope leak sub-family — pseudo-element designed
 * for desktop ergonomics applied unconditionally to all viewports
 * including the mobile viewport where its 19px overshoot triggers
 * the very overflow we audit for.
 */
class LiveEditC4n0v1MobileResizerOverflowContractTest extends TestCase
{
    private string $scss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/scss/resizer.scss'
        ));
    }

    #[Test]
    public function mobile_media_block_disables_all_four_resizer_pseudo_extensions(): void
    {
        // Locate the @media (max-width: 768px) block carrying the
        // c4n0v1 marker and assert all four edge pseudo-elements are
        // collapsed to display:none.
        $start = strpos($this->scss, 'task-2026-05-27-c4n0v1');
        $this->assertNotFalse($start, 'c4n0v1 marker must be present');

        // Capture the @media block that follows the marker.
        $mediaPos = strpos($this->scss, '@media', $start);
        $this->assertNotFalse($mediaPos, '@media block must follow the c4n0v1 marker');

        // Slice ~600 chars forward — enough to capture the full block.
        $slice = substr($this->scss, $mediaPos, 600);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)/',
            $slice,
            'Resizer pseudo-element fix must be inside @media (max-width: 768px).'
        );

        foreach (['r', 'l', 't', 'b'] as $edge) {
            $this->assertMatchesRegularExpression(
                '/\.mw-le-resizer-' . $edge . '::before/',
                $slice,
                "Mobile media block must reference .mw-le-resizer-{$edge}::before."
            );
        }

        $this->assertMatchesRegularExpression(
            '/\.mw-le-resizer-[rltb]::before[^{]*,\s*\.mw-le-resizer-[rltb]::before[^{]*,\s*\.mw-le-resizer-[rltb]::before[^{]*,\s*\.mw-le-resizer-[rltb]::before\s*\{\s*display:\s*none\s*;?\s*\}/s',
            $slice,
            'All four resizer ::before pseudo-elements must collapse to display:none on mobile.'
        );
    }

    #[Test]
    public function desktop_resizer_handles_still_have_44px_touch_target_pseudos(): void
    {
        // Regression guard: AI-1172's desktop touch-target expansion
        // must remain intact outside the mobile @media block.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-resizer-r\s*\{[^}]*&::before\s*\{[^}]*width:\s*44px/s',
            $this->scss,
            'Desktop right resizer must keep its 44px ::before touch expansion (AI-1172).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-le-resizer-b\s*\{[^}]*&::before\s*\{[^}]*height:\s*44px/s',
            $this->scss,
            'Desktop bottom resizer must keep its 44px ::before touch expansion (AI-1172).'
        );
    }

    #[Test]
    public function bare_5px_resizer_handles_unchanged(): void
    {
        // The actual handle elements (not pseudo) remain 5px on all
        // viewports — only the touch-expansion pseudo is suppressed.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-resizer-r\s*\{[^}]*width:\s*5px/s',
            $this->scss
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-le-resizer-b\s*\{[^}]*height:\s*5px/s',
            $this->scss
        );
    }

    #[Test]
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-27-c4n0v1', $this->scss);
    }
}

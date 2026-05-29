<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-27-e5e443 / AI-1143 — Element Style Editor mobile
 * touch-target audit (Batch 2). The ESE panel hosts two sub-44
 * control families that fell below WCAG 2.5.5 / iOS HIG floor on
 * 390 by 844 viewport:
 *
 *   1. .mw-segmented__cell (text-align toggle cells) rendered
 *      75-76 by 28 — 16px short on vertical hit area.
 *   2. .v-input--horizontal (Vuetify slider wrappers) rendered
 *      304 by 26 — the slider track itself is 3px tall so the
 *      tap-to-set vertical hit area was only 26px.
 *
 * Fix: scoped @media (max-width: 768px) block in
 * packages/microweber-filament-theme/resources/assets/css/microweber/
 * live-edit-mobile.css that pins both selectors to min-height: 44px
 * with inline-flex centering. !important is required on the Vuetify
 * wrapper because Vuetify writes inline styles on drag.
 *
 * Family: WCAG 2.5.5 mobile touch-target floor — same family as
 * railfit AI-1136 Layer A and h3r0a8 Dashboard hero-stats fix.
 */
class LiveEditE5e443EseTouchTargetContractTest extends TestCase
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
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-27-e5e443', $this->css);
    }

    #[Test]
    public function fix_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-e5e443');
        $this->assertNotFalse($start, 'e5e443 marker must be present');

        // Walk back to find the enclosing @media boundary.
        $mediaPos = strrpos(substr($this->css, 0, $start), '@media');
        $this->assertNotFalse($mediaPos);
        $mediaLine = substr($this->css, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)/',
            $mediaLine,
            'ESE touch-target fix must be inside @media (max-width: 768px) block.'
        );
    }

    #[Test]
    public function segmented_cell_pinned_at_44px_min_height_with_inline_flex(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-e5e443');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 2000);

        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Segmented cell must declare min-height: 44px !important so it meets the WCAG 2.5.5 floor.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell\s*\{[^}]*display:\s*inline-flex\s*!important[^}]*\}/s',
            $slice,
            'Segmented cell must declare display: inline-flex !important for vertical centering.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell\s*\{[^}]*align-items:\s*center[^}]*\}/s',
            $slice,
            'Segmented cell must declare align-items: center.'
        );
    }

    #[Test]
    public function vuetify_slider_wrapper_pinned_at_44px_min_height(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-e5e443');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 2000);

        $this->assertMatchesRegularExpression(
            '/#mw-element-style-editor-app\s+\.v-input--horizontal\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Vuetify slider wrapper must declare min-height: 44px !important — Vuetify writes inline styles on drag so !important is mandatory.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-element-style-editor-app\s+\.v-input--horizontal\s*\{[^}]*align-items:\s*center[^}]*\}/s',
            $slice,
            'Vuetify slider wrapper must declare align-items: center.'
        );
    }

    #[Test]
    public function fix_scoped_to_live_edit_surfaces(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-e5e443');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 2000);

        // Three scope prefixes for the segmented-cell rule:
        // .mw-admin-live-edit-page, .mw-live-edit-page, #mw-element-style-editor-app.
        $this->assertStringContainsString('.mw-admin-live-edit-page .mw-segmented__cell', $slice);
        $this->assertStringContainsString('.mw-live-edit-page .mw-segmented__cell', $slice);
        $this->assertStringContainsString('#mw-element-style-editor-app .mw-segmented__cell', $slice);
    }
}

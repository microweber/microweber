<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-29-7d2a91 — Admin panel mobile touch-target sweep
 * (Batch 3). Tier-3 Playwright probe at 390x844 across 5 admin
 * surfaces surfaced 2 sub-44 violation families remaining inside
 * body.fi-panel-admin:
 *
 *   1. .fi-ta-cell.fi-ta-selection-cell bare-checkbox layout on
 *      Customers / Users list tables (60x35 cell, 18x22 input,
 *      distinct markup from Profile / Newsletter so the :has()
 *      label-wrapper pattern does NOT match).
 *   2. .fi-ac-icon-btn-group page-header Actions dropdown trigger
 *      on Posts / Products at 36x36 (8px short).
 *
 * Fix: scoped @media block in mobile-touch.css pinning each rule
 * family inside body.fi-panel-admin. Sibling of Batch 3 slice
 * c8b5a1 (Profile panel).
 *
 * Family: WCAG 2.5.5 mobile touch-target floor — same lineage as
 * AI-517 / AI-518 / AI-519 / AI-1135 / AI-1143 / AI-1144 / AI-1156.
 */
class Admin7d2a91MobileTouchTargetContractTest extends TestCase
{
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
    }

    #[Test]
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-29-7d2a91', $this->css);
    }

    #[Test]
    public function fix_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-7d2a91');
        $this->assertNotFalse($start, '7d2a91 marker must be present');

        // Selector-self-match guard (Layer 1, belt): the marker
        // lives INSIDE the opening docblock, so skip past the closing
        // comment delimiter BEFORE searching for the media-query rule.
        $commentEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($commentEnd, '7d2a91 docblock must close.');
        $execStart = $commentEnd + 2;

        $mediaPos = strpos($this->css, '@media', $execStart);
        $this->assertNotFalse($mediaPos, 'media-query rule must follow the 7d2a91 docblock');

        $mediaLine = substr($this->css, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)\s*,\s*\(hover:\s*none\)\s*and\s*\(pointer:\s*coarse\)/',
            $mediaLine,
            'Admin touch-target fix must be inside the canonical mobile/touch media-query block.'
        );
    }

    #[Test]
    public function selection_cell_pinned_to_44px_on_admin_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-7d2a91');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-cell\.fi-ta-selection-cell\s*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'Admin selection-cell must declare min-height: 44px.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-cell\.fi-ta-selection-cell\s*\{[^}]*height:\s*44px[^}]*\}/s',
            $slice,
            'Admin selection-cell must declare height: 44px so the cell carries the full hit area.'
        );
    }

    #[Test]
    public function selection_cell_checkbox_input_keeps_native_size(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-7d2a91');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        // The native input keeps a visually small footprint; the cell
        // wrapper (above) carries the 44x44 hit area. min-width and
        // min-height pinned to 22px guarantee a usable visible target
        // without enlarging the input box itself.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-cell\.fi-ta-selection-cell\s+\.fi-checkbox-input\s*\{[^}]*min-width:\s*22px[^}]*\}/s',
            $slice,
            'Admin selection-cell input must declare min-width: 22px.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-cell\.fi-ta-selection-cell\s+\.fi-checkbox-input\s*\{[^}]*min-height:\s*22px[^}]*\}/s',
            $slice,
            'Admin selection-cell input must declare min-height: 22px.'
        );
    }

    #[Test]
    public function action_icon_btn_group_pinned_to_44px_on_admin_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-7d2a91');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ac-icon-btn-group\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Admin fi-ac-icon-btn-group must declare min-height: 44px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ac-icon-btn-group\s*\{[^}]*min-width:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Admin fi-ac-icon-btn-group must declare min-width: 44px !important.'
        );
    }

    #[Test]
    public function fix_is_scoped_to_admin_panel_only(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-7d2a91');
        $this->assertNotFalse($start);

        // Regression guard: this block must NOT generalise to sibling
        // panel scopes (checkout / newsletter / profile). Each Batch 3
        // sibling slice owns its own panel-scoped block.
        //
        // Selector-self-match guard (Layer 1, comment-strip): the
        // surrounding docblock prose mentions sibling panel-scope
        // tokens, so skip past the closing comment delimiter BEFORE
        // the absence check. The marker lives INSIDE the opening
        // docblock, so the executable slice starts at the first
        // occurrence of the CSS comment terminator after the marker.
        $commentEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($commentEnd, '7d2a91 docblock must close.');
        $execStart = $commentEnd + 2;
        $blockEnd = strpos($this->css, "\n}\n", $execStart);
        $this->assertNotFalse($blockEnd, '7d2a91 @media block must terminate cleanly.');
        $executable = (string) preg_replace(
            '~/\*.*?\*/~s',
            '',
            substr($this->css, $execStart, $blockEnd - $execStart)
        );

        preg_match_all('/body\.fi-panel-checkout\b/', $executable, $checkoutMatches);
        $this->assertCount(
            0,
            $checkoutMatches[0],
            '7d2a91 block must NOT contain checkout selectors. Found: '
            . implode(', ', $checkoutMatches[0])
        );

        preg_match_all('/body\.fi-panel-admin-newsletter\b/', $executable, $newsletterMatches);
        $this->assertCount(
            0,
            $newsletterMatches[0],
            '7d2a91 block must NOT contain newsletter selectors. Found: '
            . implode(', ', $newsletterMatches[0])
        );

        preg_match_all('/body\.fi-panel-profile\b/', $executable, $profileMatches);
        $this->assertCount(
            0,
            $profileMatches[0],
            '7d2a91 block must NOT contain profile selectors. Found: '
            . implode(', ', $profileMatches[0])
        );
    }
}

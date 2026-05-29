<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-29-c8b5a1 — Customer Profile panel mobile
 * touch-target sweep (Batch 3). Tier-3 Playwright probe at
 * 390x844 across 5 Profile subpages surfaced 4 consistent
 * sub-44 violation families, all standard Filament + Jetstream
 * chrome rendered inside body.fi-panel-profile:
 *
 *   1. .fi-btn.fi-size-md action buttons rendered at 42px tall
 *      (2px short of the WCAG 2.5.5 floor).
 *   2. .fi-user-menu-trigger topbar avatar at 32x32 (12px short),
 *      present on every Profile page.
 *   3. .fi-ta-*-checkbox native inputs at 18x18 (26px short on
 *      the hit area) on Saved Addresses list.
 *   4. Jetstream .btn.btn-outline-primary at 38px tall (6px short).
 *
 * Fix: scoped @media block in mobile-touch.css pinning each rule
 * family inside body.fi-panel-profile. Extends the AI-518 Profile
 * coverage (login-form remember-me checkbox wrapper only).
 *
 * Family: WCAG 2.5.5 mobile touch-target floor — same lineage as
 * AI-517 / AI-518 / AI-519 / AI-1135 / AI-1143 / AI-1144 / AI-1156.
 */
class ProfileC8b5a1MobileTouchTargetContractTest extends TestCase
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
        $this->assertStringContainsString('task-2026-05-29-c8b5a1', $this->css);
    }

    #[Test]
    public function fix_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start, 'c8b5a1 marker must be present');

        // Selector-self-match guard (Layer 1, belt): the marker
        // lives INSIDE the opening docblock, so skip past the closing
        // comment delimiter BEFORE searching for the media-query rule.
        $commentEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($commentEnd, 'c8b5a1 docblock must close.');
        $execStart = $commentEnd + 2;

        $mediaPos = strpos($this->css, '@media', $execStart);
        $this->assertNotFalse($mediaPos, 'media-query rule must follow the c8b5a1 docblock');

        $mediaLine = substr($this->css, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)\s*,\s*\(hover:\s*none\)\s*and\s*\(pointer:\s*coarse\)/',
            $mediaLine,
            'Profile touch-target fix must be inside the canonical mobile/touch media-query block.'
        );
    }

    #[Test]
    public function action_buttons_pinned_to_44px_on_profile_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.fi-btn\.fi-size-md\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Profile fi-btn.fi-size-md must declare min-height: 44px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.fi-btn\.fi-size-md\s*\{[^}]*min-width:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Profile fi-btn.fi-size-md must declare min-width: 44px !important.'
        );
    }

    #[Test]
    public function user_menu_trigger_pinned_to_44px_on_profile_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.fi-user-menu-trigger\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Profile fi-user-menu-trigger must declare min-height: 44px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.fi-user-menu-trigger\s*\{[^}]*min-width:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Profile fi-user-menu-trigger must declare min-width: 44px !important.'
        );
    }

    #[Test]
    public function table_checkbox_hit_area_uses_has_wrapper_pattern(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        // Lineage with AI-517 / AI-518 / AI-519 / AI-1144: the
        // :has(.fi-checkbox-input) wrapper pattern preserves the
        // native input size while the cell carries the 44x44 hit
        // area.
        $this->assertStringContainsString(
            'body.fi-panel-profile .fi-ta-cell:has(> .fi-checkbox-input)',
            $slice,
            'Profile checkbox cell wrapper must use :has(> .fi-checkbox-input) (AI-517 / AI-518 / AI-519 / AI-1144 lineage).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile[^{]*:has\([^)]*\.fi-checkbox-input[^)]*\)[^,]*(?:,\s*body\.fi-panel-profile[^{]*)*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'Profile checkbox :has() wrapper block must declare min-height: 44px.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile[^{]*:has\([^)]*\.fi-checkbox-input[^)]*\)[^,]*(?:,\s*body\.fi-panel-profile[^{]*)*\{[^}]*display:\s*inline-flex[^}]*\}/s',
            $slice,
            'Profile checkbox :has() wrapper block must declare display: inline-flex.'
        );
    }

    #[Test]
    public function jetstream_bootstrap_buttons_pinned_to_44px(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        // Jetstream renders auth widgets (Two Factor Authentication
        // and Browser Sessions) as Bootstrap .btn.btn-outline-primary
        // inside the Filament Profile panel. They render at 38px tall
        // (6px short).
        $this->assertStringContainsString(
            'body.fi-panel-profile .btn.btn-outline-primary',
            $slice,
            'Profile Jetstream outline-primary button selector must be present.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.btn\.btn-outline-primary[^{]*(?:,\s*body\.fi-panel-profile[^{]*)*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'Profile Bootstrap button block must declare min-height: 44px.'
        );
    }

    #[Test]
    public function fix_is_scoped_to_profile_panel_only(): void
    {
        $start = strpos($this->css, 'task-2026-05-29-c8b5a1');
        $this->assertNotFalse($start);

        // Regression guard: project standard cautions against broad
        // body.fi-panel-admin touch sweeps. Every selector in this
        // block must carry a panel-scope class so cross-panel
        // generalisation is an explicit decision, not an accidental
        // side-effect.
        //
        // Selector-self-match guard (Layer 1, comment-strip): the
        // surrounding docblock prose mentions panel-scope tokens, so
        // skip past the closing comment delimiter BEFORE the absence
        // check. The marker lives INSIDE the opening docblock, so the
        // executable slice starts at the first occurrence of the CSS
        // comment terminator after the marker.
        $commentEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($commentEnd, 'c8b5a1 docblock must close.');
        $execStart = $commentEnd + 2;
        $blockEnd = strpos($this->css, "\n}\n", $execStart);
        $this->assertNotFalse($blockEnd, 'c8b5a1 @media block must terminate cleanly.');
        $executable = (string) preg_replace(
            '~/\*.*?\*/~s',
            '',
            substr($this->css, $execStart, $blockEnd - $execStart)
        );

        // body.fi-panel-admin (bare) would generalise across every
        // admin panel.
        preg_match_all('/body\.fi-panel-admin(?!-)\b/', $executable, $broadAdmin);
        $this->assertCount(
            0,
            $broadAdmin[0],
            'c8b5a1 block must NOT contain any bare body.fi-panel-admin selectors. Found: '
            . implode(', ', $broadAdmin[0])
        );

        // body.fi-panel-checkout would generalise to checkout.
        preg_match_all('/body\.fi-panel-checkout\b/', $executable, $checkoutMatches);
        $this->assertCount(
            0,
            $checkoutMatches[0],
            'c8b5a1 block must NOT contain checkout selectors. Found: '
            . implode(', ', $checkoutMatches[0])
        );
    }
}

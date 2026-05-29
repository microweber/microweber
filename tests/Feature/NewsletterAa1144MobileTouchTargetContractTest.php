<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-27-aa1144 / AI-1144 — Newsletter panel mobile
 * touch-target audit (Batch 2). Tier-3 Playwright probe at
 * 390x844 across 5 Newsletter admin pages found 3 consistent
 * sub-44 violation families, all standard Filament chrome
 * rendered inside body.fi-panel-admin-newsletter:
 *
 *   1. .fi-btn.fi-size-md action buttons rendered at 42px tall
 *      (2px short of the WCAG 2.5.5 floor).
 *   2. .fi-user-menu-trigger topbar avatar at 32x32 (12px short).
 *   3. .fi-ta-*-checkbox native inputs at 18x18 (26px short on
 *      the hit area).
 *
 * Fix: scoped @media block in mobile-touch.css pinning each rule
 * family inside body.fi-panel-admin-newsletter. The checkbox
 * hit-area uses the :has() wrapper pattern established by
 * AI-517 / AI-518 / AI-519 so the visible input keeps its
 * native size while the label/cell wrapper carries the 44x44
 * target.
 *
 * Family: WCAG 2.5.5 mobile touch-target floor — same lineage as
 * AI-517 / AI-518 / AI-519 / AI-1135 / AI-1143 / AI-1156.
 */
class NewsletterAa1144MobileTouchTargetContractTest extends TestCase
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
        $this->assertStringContainsString('task-2026-05-27-aa1144', $this->css);
    }

    #[Test]
    public function fix_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-aa1144');
        $this->assertNotFalse($start, 'aa1144 marker must be present');

        $mediaPos = strpos($this->css, '@media', $start);
        $this->assertNotFalse($mediaPos, '@media block must follow the aa1144 marker');

        $mediaLine = substr($this->css, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)\s*,\s*\(hover:\s*none\)\s*and\s*\(pointer:\s*coarse\)/',
            $mediaLine,
            'Newsletter touch-target fix must be inside the canonical mobile/touch @media block.'
        );
    }

    #[Test]
    public function action_buttons_pinned_to_44px_on_newsletter_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-aa1144');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter\s+\.fi-btn\.fi-size-md\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Newsletter fi-btn.fi-size-md must declare min-height: 44px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter\s+\.fi-btn\.fi-size-md\s*\{[^}]*min-width:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Newsletter fi-btn.fi-size-md must declare min-width: 44px !important.'
        );
    }

    #[Test]
    public function user_menu_trigger_pinned_to_44px_on_newsletter_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-aa1144');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter\s+\.fi-user-menu-trigger\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Newsletter fi-user-menu-trigger must declare min-height: 44px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter\s+\.fi-user-menu-trigger\s*\{[^}]*min-width:\s*44px\s*!important[^}]*\}/s',
            $slice,
            'Newsletter fi-user-menu-trigger must declare min-width: 44px !important.'
        );
    }

    #[Test]
    public function checkbox_hit_area_uses_has_wrapper_pattern_on_newsletter_panel(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-aa1144');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 3000);

        // Lineage with AI-517 / AI-518 / AI-519: the :has(.fi-checkbox-input)
        // label-wrapper pattern preserves the native input size while the
        // wrapper carries the 44x44 hit area.
        $this->assertStringContainsString(
            'body.fi-panel-admin-newsletter label:has(.fi-checkbox-input)',
            $slice,
            'Newsletter checkbox label wrapper must use :has(.fi-checkbox-input) (AI-517 / AI-518 / AI-519 lineage).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter[^{]*:has\([^)]*\.fi-checkbox-input[^)]*\)[^,]*(?:,\s*body\.fi-panel-admin-newsletter[^{]*)*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'Newsletter checkbox :has() wrapper block must declare min-height: 44px.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin-newsletter[^{]*:has\([^)]*\.fi-checkbox-input[^)]*\)[^,]*(?:,\s*body\.fi-panel-admin-newsletter[^{]*)*\{[^}]*display:\s*inline-flex[^}]*\}/s',
            $slice,
            'Newsletter checkbox :has() wrapper block must declare display: inline-flex for vertical centering.'
        );
    }

    #[Test]
    public function fix_is_scoped_to_newsletter_panel_only(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-aa1144');
        $this->assertNotFalse($start);

        // Regression guard: project standard cautions against broad
        // body.fi-panel-admin touch sweeps. Every selector in this
        // block must carry the -newsletter scope suffix so cross-admin
        // generalisation is an explicit decision (AI-1145 follow-up),
        // not an accidental side-effect.
        //
        // Selector-self-match guard (Layer 1, comment-strip): the
        // surrounding docblock prose mentions the bare scope token, so
        // skip past the closing comment delimiter BEFORE the absence
        // check. The marker lives INSIDE the opening docblock, so we
        // start the executable slice at the first occurrence of the
        // CSS comment terminator after the marker.
        $commentEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($commentEnd, 'aa1144 docblock must close.');
        $execStart = $commentEnd + 2;
        $blockEnd = strpos($this->css, "\n}\n", $execStart);
        $this->assertNotFalse($blockEnd, 'aa1144 @media block must terminate cleanly.');
        $executable = (string) preg_replace(
            '~/\*.*?\*/~s',
            '',
            substr($this->css, $execStart, $blockEnd - $execStart)
        );

        preg_match_all('/body\.fi-panel-admin(?!-newsletter)\b/', $executable, $broadMatches);
        $this->assertCount(
            0,
            $broadMatches[0],
            'aa1144 block must NOT contain any bare body.fi-panel-admin selectors '
            . '(would generalise across every admin panel). Found: '
            . implode(', ', $broadMatches[0])
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-01-ai1205 — AI-1205: Element Style Editor BorderRadius accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/live-edit (ElementStyleEditor → Border tab):
 *   The 4 corner number inputs (top-left / top-right / bottom-left / bottom-right) had no
 *   aria-label / no <label> / no aria-labelledby. AT users heard only "edit, blank" on each
 *   corner; the decorative angle icons carried the visual semantic but were aria-mute.
 *   WCAG 3.3.2 Level A regression (Labels or Instructions).
 *
 * Fix: aria-label + title on each input naming the corner (e.g. "Top-left corner radius");
 *      aria-hidden="true" on each decorative `<span class="mw-field-flat-prepend">` wrapper
 *      so the icon is not double-announced alongside the input label.
 *
 * Sister-fix lineage: AI-1196 (AIChatForm ariaLabel fallback chain), AI-817 slider aria-label
 * sweep, AI-1204 (mw-icon-picker clear-button) — same family of WCAG 3.3.2 Level A regressions.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip HTML comments before
 * negative regression assertions so the docblock prose describing the pre-fix
 * "edit, blank" pattern cannot false-fail.
 */
class EseAI1205BorderRadiusAriaLabelContractTest extends TestCase
{
    private string $source;
    private string $sourceStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->source = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/BorderRadius.vue'
        ));
        $this->sourceStripped = (string) preg_replace('~<!--.*?-->~s', '', $this->source);
    }

    #[Test]
    public function vue_file_exists(): void
    {
        $this->assertFileExists(
            base_path('packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/BorderRadius.vue'),
            'BorderRadius.vue MUST exist at the canonical ElementStyleEditor components path'
        );
    }

    #[Test]
    public function top_left_input_carries_aria_label_and_title(): void
    {
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderTopLeftRadius"[\s\S]*?aria-label="Top-left corner radius"[\s\S]*?\/>/',
            $this->source,
            'Top-left input MUST carry aria-label="Top-left corner radius" — WCAG 3.3.2 Level A'
        );

        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderTopLeftRadius"[\s\S]*?title="Top-left corner radius"[\s\S]*?\/>/',
            $this->source,
            'Top-left input MUST carry title="Top-left corner radius" — defence-in-depth tooltip for mouse users'
        );
    }

    #[Test]
    public function top_right_input_carries_aria_label_and_title(): void
    {
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderTopRightRadius"[\s\S]*?aria-label="Top-right corner radius"[\s\S]*?\/>/',
            $this->source,
            'Top-right input MUST carry aria-label="Top-right corner radius" — WCAG 3.3.2 Level A'
        );

        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderTopRightRadius"[\s\S]*?title="Top-right corner radius"[\s\S]*?\/>/',
            $this->source,
            'Top-right input MUST carry title="Top-right corner radius" — defence-in-depth tooltip for mouse users'
        );
    }

    #[Test]
    public function bottom_left_input_carries_aria_label_and_title(): void
    {
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderBottomLeftRadius"[\s\S]*?aria-label="Bottom-left corner radius"[\s\S]*?\/>/',
            $this->source,
            'Bottom-left input MUST carry aria-label="Bottom-left corner radius" — WCAG 3.3.2 Level A'
        );

        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderBottomLeftRadius"[\s\S]*?title="Bottom-left corner radius"[\s\S]*?\/>/',
            $this->source,
            'Bottom-left input MUST carry title="Bottom-left corner radius" — defence-in-depth tooltip for mouse users'
        );
    }

    #[Test]
    public function bottom_right_input_carries_aria_label_and_title(): void
    {
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderBottomRightRadius"[\s\S]*?aria-label="Bottom-right corner radius"[\s\S]*?\/>/',
            $this->source,
            'Bottom-right input MUST carry aria-label="Bottom-right corner radius" — WCAG 3.3.2 Level A'
        );

        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?name="borderBottomRightRadius"[\s\S]*?title="Bottom-right corner radius"[\s\S]*?\/>/',
            $this->source,
            'Bottom-right input MUST carry title="Bottom-right corner radius" — defence-in-depth tooltip for mouse users'
        );
    }

    #[Test]
    public function decorative_angle_wrappers_marked_aria_hidden(): void
    {
        $matchCount = preg_match_all(
            '/<span\s+class="mw-field\s+mw-field-flat-prepend[^"]*"\s+aria-hidden="true">/',
            $this->source
        );

        $this->assertSame(
            4,
            $matchCount,
            'All 4 decorative angle wrappers MUST carry aria-hidden="true" — otherwise screen-readers may double-announce the corner icon alongside the labelled input'
        );
    }

    #[Test]
    public function task_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-06-01-ai1205',
            $this->source,
            'Source MUST carry task-2026-06-01-ai1205 marker for grep-ability across future audits'
        );

        $this->assertStringContainsString(
            'AI-1205',
            $this->source,
            'Source MUST carry AI-1205 Jira marker so the fix lineage is discoverable'
        );
    }

    #[Test]
    public function no_empty_aria_label_regression(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label=""\s/',
            $this->sourceStripped,
            'No input MAY carry aria-label="" — empty accessible name fails WCAG 3.3.2 Level A the same way no aria-label does'
        );
    }

    #[Test]
    public function all_four_corner_inputs_present(): void
    {
        $this->assertSame(
            1,
            preg_match_all('/name="borderTopLeftRadius"/', $this->source),
            'Exactly 1 borderTopLeftRadius input MUST exist'
        );
        $this->assertSame(
            1,
            preg_match_all('/name="borderTopRightRadius"/', $this->source),
            'Exactly 1 borderTopRightRadius input MUST exist'
        );
        $this->assertSame(
            1,
            preg_match_all('/name="borderBottomLeftRadius"/', $this->source),
            'Exactly 1 borderBottomLeftRadius input MUST exist'
        );
        $this->assertSame(
            1,
            preg_match_all('/name="borderBottomRightRadius"/', $this->source),
            'Exactly 1 borderBottomRightRadius input MUST exist'
        );
    }
}

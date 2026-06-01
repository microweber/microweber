<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-bg811 — AI-1198: Background module-settings carried 3 AT
 * defects in the same Layouts admin/settings view that hosts the Background
 * surface. Probed via Playwright at /admin/background-module-settings:
 *
 *   1. 4 source tabs (Image/Video/Color/Other) rendered as <span x-on:click>
 *      with no role, tabindex, or keyboard handler. Screen readers announced
 *      "Image" as plain text; keyboard never reached the tabs. WCAG 4.1.2 A.
 *   2. "Image size" radio group: bare <label> with no `for` paired with an
 *      unannotated div container — AT could not associate label with the
 *      4 radios. WCAG 1.3.1 A.
 *   3. "This layout contains these modules": rendered as <label> despite
 *      binding to no control — screen readers reported "label, no associated
 *      form field". WCAG 1.3.1 A.
 *
 * Fix: Modules/Layouts/resources/views/admin/settings.blade.php
 *
 *   1. role="tablist" aria-label="Background source" wrapper + each tab is
 *      a real <button type="button" role="tab" x-bind:aria-selected="...">
 *      so AT announces "Image tab, selected" and keyboard Tab reaches them.
 *   2. <label id="bg-image-size-label" class="live-edit-label">Image size</label>
 *      + <div role="radiogroup" aria-labelledby="bg-image-size-label">...
 *   3. <div role="heading" aria-level="3" class="current-template-modules-list-label">
 *      (was <label> — no `for`, bound to nothing).
 *
 * AI-811 family reprise — same span→button conversion recipe shipped on
 * the Live Edit resolution switcher.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade
 * comments before negative regression assertions so docblock prose
 * mentioning the legacy patterns cannot false-fail.
 */
class BackgroundBg811AI1198TabsRadiogroupHeadingContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'Modules/Layouts/resources/views/admin/settings.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function tablist_wrapper_carries_role_and_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+role="tablist"\s+aria-label="Background source"/s',
            $this->blade,
            'Tab container must carry role="tablist" + aria-label="Background source" so AT groups the tab buttons'
        );
    }

    #[Test]
    public function all_four_tabs_render_as_real_buttons_with_role_tab(): void
    {
        foreach (['image', 'video', 'color', 'other'] as $tab) {
            $this->assertMatchesRegularExpression(
                '/<button\s+type="button"\s+role="tab"[^>]*x-on:click="activeTab\s*=\s*\'' . $tab . '\'"/s',
                $this->blade,
                sprintf('Tab "%s" must be a real <button type="button" role="tab"> not a <span>', $tab)
            );
        }
    }

    #[Test]
    public function each_tab_binds_aria_selected_to_active_tab_state(): void
    {
        foreach (['image', 'video', 'color', 'other'] as $tab) {
            $this->assertMatchesRegularExpression(
                '/<button[^>]*x-on:click="activeTab\s*=\s*\'' . $tab . '\'"[^>]*x-bind:aria-selected="activeTab\s*===\s*\'' . $tab . '\'\s*\?\s*\'true\'\s*:\s*\'false\'"/s',
                $this->blade,
                sprintf('Tab "%s" must reflect selected state via x-bind:aria-selected for AT', $tab)
            );
        }
    }

    #[Test]
    public function image_size_label_carries_id_for_radiogroup_binding(): void
    {
        $this->assertMatchesRegularExpression(
            '/<label\s+id="bg-image-size-label"\s+class="live-edit-label">Image size<\/label>/',
            $this->blade,
            '"Image size" label must carry id="bg-image-size-label" so the radiogroup can reference it via aria-labelledby'
        );
    }

    #[Test]
    public function image_size_radiogroup_binds_to_label_via_aria_labelledby(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+role="radiogroup"\s+aria-labelledby="bg-image-size-label"/s',
            $this->blade,
            'Radio container must carry role="radiogroup" + aria-labelledby="bg-image-size-label" so AT announces "Image size, radio group"'
        );
    }

    #[Test]
    public function layout_modules_list_heading_is_a_div_with_role_heading(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+role="heading"\s+aria-level="3"\s+class="current-template-modules-list-label[^"]*">This layout contains these modules<\/div>/s',
            $this->blade,
            'Layout-modules-list heading must be a <div role="heading" aria-level="3">, not a <label> (binds to no control)'
        );
    }

    #[Test]
    public function legacy_span_tab_pattern_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<span\s+x-show="supports\.includes\(\'(image|video|color|other)\'\)"\s+x-on:click="activeTab/s',
            $this->bladeStripped,
            'Legacy <span x-on:click="activeTab = ..."> pattern must not return — WCAG 4.1.2 Level A regression guard'
        );
    }

    #[Test]
    public function legacy_label_for_layout_modules_list_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<label\s+class="current-template-modules-list-label[^"]*">This layout contains these modules<\/label>/s',
            $this->bladeStripped,
            'Legacy <label class="current-template-modules-list-label">...</label> (no `for`) must not return — WCAG 1.3.1 Level A regression guard'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1198',
            $this->blade,
            'blade must carry the AI-1198 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-bg811',
            $this->blade,
            'blade must carry the task-2026-05-31-bg811 marker for grep-ability'
        );
    }
}
